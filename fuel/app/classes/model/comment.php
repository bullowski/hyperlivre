<?php

class Model_Comment extends Orm\Model
{
	protected static $_table_name = 'comments';
	protected static $_belongs_to = array(
		'note' => array(
			'key_from' => 'note_id',
			'model_to' => 'Model_Note',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => false),
		'user' => array(
			'key_from' => 'user_id',
			'model_to' => 'Model_User',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => false),
	);

	protected static $_properties = array('id', 'user_id', 'note_id',
		'title', 'comment', 'status', 'created_at', 'updated_at');
	protected static $_primary_key = array('id');

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array('before_insert'),
		'Orm\Observer_UpdatedAt' => array('before_save'),
	);

	public static $status_values = array('hidden' => 0, 'published' => 1, 'archive' => 3);

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

	public static function count_filtered_comments($filter = 'all', $exclude_filter = false)
	{
		return count(static::get_filtered_comments_by_author('all', $filter, $exclude_filter));
	}

	public static function count_filtered_comments_by_author($user_id, $filter = 'all', $exclude_filter = false)
	{
		return count(static::get_filtered_comments_by_author($user_id, $filter, $exclude_filter));
	}

	/**
	 * Orm call to get all comments given their status
	 * @return type array filtered comments
	 */
	public static function get_filtered_comments(
			$filter = 'all', $exclude_filter = false, $offset = 0, $limit = null)
	{
		return static::get_filtered_comments_by_author(
				'all', $filter, $exclude_filter, $offset, $limit);
	}

	/**
	 * $exclude_filter is only valid with filter === 'all'
	 * This parameter is used to exclude statuses from the 'all' bag
	 */
	public static function get_filtered_comments_by_author(
			$user_id, $filter = 'all', $exclude_filter = false, $offset = 0, $limit = null)
	{
		if ($user_id !== null && $user_id !== 'all')
		{
			$options['where'][] = array(array('user_id', '=', $user_id));
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

		return Model_Comment::find('all', $options);
	}

}

/* End of file book.php */