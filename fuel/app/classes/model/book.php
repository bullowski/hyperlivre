<?php

class Model_Book extends Orm\Model
{
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
	protected static $_properties = array('id', 'creator_id', 'title', 'description', 'status', 'created_at', 'updated_at');
	protected static $_primary_key = array('id');

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array('before_insert'),
		'Orm\Observer_UpdatedAt' => array('before_save'),
	);

	public static $status_values = array('hidden' => 0, 'open' => 1, 'private' => 2, 'archive' => 3);

	public static function status_names()
	{
		return array_flip(static::$status_values);
	}

	public static function status_name($status)
	{
		$status_names = statis::status_names();
		$name = $status_names[$status];

		return ($name === null) ? 'all' : $name;
	}

	public static function count_filtered_books($filter = 'all')
	{
		return count(static::get_filtered_books_by_author('all', $filter));
	}

	public static function count_filtered_books_by_author($user_id, $filter = 'all')
	{
		return count(static::get_filtered_books_by_author($user_id, $filter));
	}

	/**
	 * Orm call to get all books given their status
	 * @return type array filtered books
	 */
	public static function get_filtered_books($filter = 'all', $offset = 0, $limit = null)
	{
		return static::get_filtered_books_by_author('all', $filter, $offset, $limit);
	}

	public static function get_filtered_books_by_author(
			$user_id, $filter = 'all', $offset = 0, $limit = null)
	{
		$options = array('include' => 'concepts');

		if (user_id !== null && $user_id !== 'all')
		{
			$options['where'][] = array(array('creator_id', '=', $user_id));
		}

		if (!is_null($limit))
		{
			$options['offset'] = $offset;
			$options['limit'] = $limit;
		}

		if ($filter !== 'all')
		{
			$options['where'][] = array(
				array('status', '=', static::$status_values[$filter])
			);
		}

		return Model_Book::find('all', $options);
	}

}

/* End of file book.php */