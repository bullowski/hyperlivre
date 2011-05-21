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
			'cascade_save' => false,
			'cascade_delete' => false),
		'notes' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Note',
			'key_to' => 'book_id',
			'cascade_save' => false,
			'cascade_delete' => false),
		'statements' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Statement',
			'key_to' => 'book_id',
			'cascade_save' => false,
			'cascade_delete' => false),
	);

	protected static $_many_many = array(
	    'users' => array(
	        'key_from' => 'id',
	        'key_through_from' => 'book_id', // column 1 from the table in between, should match a book.id
	        'table_through' => 'books_users', // both models plural without prefix in alphabetical order
	        'key_through_to' => 'user_id', // column 2 from the table in between, should match a users.id
	        'model_to' => 'Model_User',
	        'key_to' => 'id',
	        'cascade_save' => false,
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
		$archives = Model_Book::get_filtered_books('archive');
		if (!is_array($activable_books))
		{
			$activable_books = array($activable_books);
		}
		if (!is_array($archives))
		{
			$archives = array($archives);
		}

		//merged with archives
		$activable_books = array_merge($activable_books, $archives);

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


	public static function remove($id, $archive = false)
	{
		if (!$book = Model_Book::find($id))
		{
			Request::show_404();
		}

		$success = true;
		foreach ($book->notes as $note)
		{
			$success = $success && Model_Note::remove($note->id, $archive); //cascade
			if (!$success)
			{
				return false;
			}
		}

		//TODO cascade delete concepts
//			foreach ($book->concepts as $concept)
//			{
//				$success = $success && Model_Concept::remove($concept->id, $archive); //cascade
//			    if (!$success)
	//			{
	//				return false;
	//			}
//			}

		//deselect active users
		foreach ($book->active_users as $user)
		{
			$user->active_book_id = null;
			$success = $success && $user->save();
			if (!$success)
			{
				return false;
			}
		}

		//unsubscibe users
		foreach ($book->users as $user)
		{
			unset($user->books[$id]);
			$success = $success && $user->save();
			if (!$success)
			{
				return false;
			}
		}


		if ($archive)
		{
			$book->status = static::$status_values['archive'];
			return $book->save();
		}

		return $book->delete();
	}

}

/* End of file book.php */