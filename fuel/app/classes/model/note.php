<?php

class Model_Note extends Orm\Model {

	protected static $_table_name 	= 'notes';
	protected static $_belongs_to = array(
		'creator' => array(
			'key_from' => 'creator_id',
			'model_to' => 'Model_User',
			'key_to' => 'id',
			'cascade_save' => false,
			'cascade_delete' => false),
		'book' => array(
			'key_from' => 'book_id',
			'model_to' => 'Model_Book',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => false),
		);

	protected static $_has_many = array(
		'comments' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Comment',
			'key_to' => 'note_id',
			'cascade_save' => true,
			'cascade_delete' => true),
	);

	protected static $_properties 	= array('id', 'creator_id', 'book_id', 'title', 'body',
											'status', 'created_at', 'updated_at');
	protected static $_primary_key 	= array('id');

	public static $status_values = array('draft' => 0, 'published' => 1, 'archive' => 2);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array('before_insert'),
		'Orm\Observer_UpdatedAt' => array('before_save'),
	);

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

	public static function count_filtered_notes_by_author($active_book_id, $user_id, $filter = 'all', $exclude_filter = false)
	{
		return count(static::get_filtered_notes_by_author($active_book_id, $user_id, $filter, $exclude_filter));
	}

	public static function get_filtered_notes_by_author(
			$active_book_id, $user_id, $filter = 'all', $exclude_filter = false, $offset = 0, $limit = null)
	{
		$options = array('include' => 'concepts',
						 'where' => array(
							 array('book_id', '=', $active_book_id)));

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

		return Model_Note::find('all', $options);
	}

}