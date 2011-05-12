<?php

class Model_Book extends Orm\Model
{
	public static $status = array(-1 => 'archive', 0 => 'hidden', 1 => 'published_open', 2 => 'published_closed');

	protected static $_table_name = 'books';
//	protected static $_has_one = array('creator' => array(
//											'key_from' => 'id',
//											'model_to' => 'Model_User',
//											'key_to' => 'creator_id'));
	protected static $_has_many = array('users');
	protected static $_properties = array('id', 'user_id', 'title', 'description', 'published', 'created_at', 'updated_at');
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