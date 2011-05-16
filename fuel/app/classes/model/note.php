<?php

class Model_Note extends Orm\Model {

	protected static $_table_name 	= 'notes';
	protected static $_belongs_to = array(
		'creator' => array(
			'key_from' => 'creator_id',
			'model_to' => 'Model_User',
			'key_to' => 'id',
			'cascade_save' => false,
			'cascade_delete' => false));

	protected static $_properties 	= array('id', 'creator_id', 'title', 'body',
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
		$status_names = statis::status_names();
		$name = $status_names[$status];

		return ($name === null) ? 'all' : $name;
	}

	public static function count_filtered_notes_by_author($user_id, $filter = 'all')
	{
		return count(static::get_filtered_notes_by_author($user_id, $filter));
	}

	public static function get_filtered_notes_by_author(
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

		return Model_Note::find('all', $options);
	}

}