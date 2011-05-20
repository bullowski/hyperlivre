<?php

class Model_Property extends Orm\Model
{
	protected static $_table_name = 'properties';
	protected static $_has_many = array(
		'statements' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Statement',
			'key_to' => 'property_id',
			'cascade_save' => false,
			'cascade_delete' => false),
	);

	protected static $_properties = array('id', 'name', 'super_id', 'description');
	protected static $_primary_key = array('id');

}

/* End of file book.php */