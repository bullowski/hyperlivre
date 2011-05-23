<?php

class Model_Statement extends Orm\Model {

	protected static $_table_name 	= 'statements';
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
			'cascade_save' => false,
			'cascade_delete' => false),
		'property' => array(
			'key_from' => 'property_id',
			'model_to' => 'Model_Property',
			'key_to' => 'id',
			'cascade_save' => false,
			'cascade_delete' => false),
		);

	protected static $_properties 	= array('id', 'book_id', 'creator_id', 'subject_id', 'subject_type',
											'property_id', 'object_id', 'object_type', 'created_at', 'updated_at');
	protected static $_primary_key 	= array('id');

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array('before_insert'),
		'Orm\Observer_UpdatedAt' => array('before_save'),
	);


}