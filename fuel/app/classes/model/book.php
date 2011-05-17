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
			'cascade_delete' => false)
	);

	protected static $_has_many = array(
		'active_users' => array(
			'key_from' => 'id',
			'model_to' => 'Model_User',
			'key_to' => 'active_book_id',
			'cascade_save' => true,
			'cascade_delete' => false)
	);

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
		$status_names = static::status_names();
		$name = $status_names[$status];

		return ($name === null) ? 'all' : $name;
	}

	public static function count_filtered_books($filter = 'all', $exclude_filter = false)
	{
		return count(static::get_filtered_books_by_author('all', $filter, $exclude_filter));
	}

	public static function count_filtered_books_by_author($user_id, $filter = 'all', $exclude_filter = false)
	{
		return count(static::get_filtered_books_by_author($user_id, $filter, $exclude_filter));
	}

	/**
	 * Orm call to get all books given their status
	 * @return type array filtered books
	 */
	public static function get_filtered_books(
			$filter = 'all', $exclude_filter = false, $offset = 0, $limit = null)
	{
		return static::get_filtered_books_by_author(
				'all', $filter, $exclude_filter, $offset, $limit);
	}

	/**
	 * $exclude_filter is only valid with filter === 'all'
	 * This parameter is used to exclude statuses from the 'all' bag
	 */
	public static function get_filtered_books_by_author(
			$user_id, $filter = 'all', $exclude_filter = false, $offset = 0, $limit = null)
	{
		$options = array('include' => 'concepts');

		if ($user_id !== null && $user_id !== 'all')
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
		else
		{	//use this only to exclude statuses from the 'all' bag
			if ($exclude_filter)
			{
				$options['where'][] = array(
				array('status', '<>', static::$status_values[$exclude_filter]));
			}
		}

		return Model_Book::find('all', $options);
	}

	// TODO test this
	public static function get_activable_books_by_user(
			$user_id, $filter = 'all', $exclude_filter = false)
	{
		if ($user_id === null || $user_id === 'all')
		{
			return false;
		}

		$activable_books = Model_User::find($user_id)->books;

		if ($filter !== 'all')
		{
			foreach ($activable_books as $id => $book)
			{
				if (Model_Book::status_name($book->status) !== $filter)
				{
					unset($activable_books[$id]);
				}
			}
		}
		else
		{	//use this only to exclude status from the 'all' bag
			if ($exclude_filter)
			{
				foreach ($activable_books as $id => $book)
				{
					if (Model_Book::status_name($book->status) === $exclude_filter)
					{
						unset($activable_books[$id]);
					}
				}
			}
		}

		return $activable_books;
	}

}

/* End of file book.php */