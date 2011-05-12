<?php

class Model_Note extends Orm\Model {
    
	protected static $_table_name 	= 'notes';
    protected static $_belongs_to 	= array('concept');
	protected static $_properties 	= array('id', 'user_id', 'concept_id', 'title', 
											'body', 'published', 'created_at');
	protected static $_primary_key 	= array('id');
	
	public function _validation_unique($input, $field)
    {
		$field	= MBSTRING ? mb_strtolower($field) : strtolower($field);
		$input 	= MBSTRING ? mb_strtolower($input) : strtolower($input);
		
		$count 	= Model_Note::find()->where($field, '=', $input)->count();
        if($count != 0)
            return false;
        else
            return true;
    }
	
	public function count_notes()
	{
		return count(Model_Note::find('all'));
	}
	
	public function count_user_notes($uid)
	{
		$notes = Model_Note::find_by_user_id($uid);
		return count($notes);
	}
	
		

}