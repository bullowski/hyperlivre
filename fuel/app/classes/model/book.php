<?php

class Model_Book extends Orm\Model
{
	public static $status = array('hidden', 'published_open', 'published_closed', 'archive');

	protected static $_table_name = 'books';
	protected static $_belongs_to = array(
		'creator' => array(
			'key_from' => 'creator_id',
			'model_to' => 'Model_User',
			'key_to' => 'id',
			'cascade_save' => false,
			'cascade_delete' => false));

	protected static $_many_many = array(
	    'users' => array(
	        'key_from' => 'id',
	        'key_through_from' => 'book_id', // column 1 from the table in between, should match a book.id
	        'table_through' => 'books_users', // both models plural without prefix in alphabetical order
	        'key_through_to' => 'user_id', // column 2 from the table in between, should match a users.id
	        'model_to' => 'Model_User',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);

	protected static $_has_many = array('users');
	protected static $_properties = array('id', 'creator_id', 'title', 'description', 'published', 'created_at', 'updated_at');
	protected static $_primary_key = array('id');

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array('before_insert'),
		'Orm\Observer_UpdatedAt' => array('before_save'),
	);

	public static function count_books($published = null)
	{
		if ($published === null || $published === 'all')
		{
			return count(Model_Book::find('all'));
		}
		else
		{
			return count(Model_Book::find_by_published($published));
		}
	}

	/**
	 * Orm call to get all books from this published status
	 * @return type array books from this published status
	 */
	public function get_books_by_published($published = 'all', $offset = 0, $limit = 10)
	{
		$options = array('offset' => $offset, 'limit' => $limit);

		if ($published !== 'all')
		{
			$options['where'] = array(array('published', '=', $published));
		}

		return Model_Book::find('all', $options);
	}

	public static function get_status($filter = 'all')
	{
		if ($filter !== 'all')
		{
			foreach(static::$status as $key => $value)
			{
				if ($value === $filter)
				{
					return $key;
				}
			}
		}

		return 'all';
	}
}

/* End of file book.php */