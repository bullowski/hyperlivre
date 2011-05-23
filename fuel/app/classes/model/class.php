<?php

class Model_Class extends Orm\Model
{
	protected static $_table_name = 'classes';

	protected static $_properties = array('id', 'name', 'super_id', 'description');
	protected static $_primary_key = array('id');

}

/* End of file book.php */