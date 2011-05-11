<?php

class Model_Concept extends Orm\Model
{
	protected static $_table_name = 'concepts';
	protected static $_has_one 	= array('book', 'user');
	protected static $_properties = array(	'id', 'book_id', 'user_id',
											'title', 'description', 
											'created_at', 'updated_at');
	protected static $_primary_key = array('id');

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array('before_insert'),
		'Orm\Observer_UpdatedAt' => array('before_save'),
	);
	
}
	
/* End of file concept.php */