<?php

class Model_Concept extends Orm\Model
{
	protected static $_table_name = 'concepts';

//	protected static $_has_many = array('books')
	protected static $_has_one = array(
		'creator' => array('key_from' => 'creator_id',
			'model_to' => 'Model_User',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => false));

	protected static $_properties = array(
		'id', 'creator_id',
		'title', 'description',
		'created_at', 'updated_at');
	protected static $_primary_key = array('id');

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array('before_insert'),
		'Orm\Observer_UpdatedAt' => array('before_save'),
	);

}

/* End of file concept.php */