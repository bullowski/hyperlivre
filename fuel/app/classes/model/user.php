<?php

class Model_User extends Orm\Model
{
	protected static $_table_name = 'users';
	protected static $_belongs_to = array(
		'active_book' => array(
			'key_from' => 'active_book_id',
			'model_to' => 'Model_Book',
			'key_to' => 'id',
			'cascade_save' => false,
			'cascade_delete' => false),
		);
	protected static $_has_many = array(
		'added_books' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Book',
			'key_to' => 'creator_id',
			'cascade_save' => true,
			'cascade_delete' => false),
		'concepts' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Concept',
			'key_to' => 'creator_id',
			'cascade_save' => true,
			'cascade_delete' => false),
		'notes' => array(
			'key_from' => 'id',
			'model_to' => 'Model_Note',
			'key_to' => 'creator_id',
			'cascade_save' => true,
			'cascade_delete' => false),
		);
	protected static $_many_many = array(
	    'books' => array(
	        'key_from' => 'id',
	        'key_through_from' => 'user_id', // column 1 from the table in between, should match a users.id
	        'table_through' => 'books_users', // both models plural without prefix in alphabetical order
	        'key_through_to' => 'book_id', // column 2 from the table in between, should match a book.id
	        'model_to' => 'Model_Book',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);


//	protected static $_belongs_to = array(
//		'added_concept' => array('key_from' => 'id',
//			'model_to' => 'Model_Concept',
//			'key_to' => 'creator_id',
//			'cascade_save' => false,
//			'cascade_delete' => false));

//	protected static $_has_many = array('books');//'notes', 'teams');
	protected static $_properties = array('id', 'username', 'password',
		'email', 'profile_fields',
		'group', 'active_book_id', 
		'last_login', 'login_hash');
	protected static $_primary_key = array('id');


	public static function count_users($group = null)
	{
		if ($group === null || $group === 'all')
		{
			return count(Model_User::find('all'));
		}
		else
		{
			return count(Model_User::find_by_group($group));
		}
	}

	/**
	 * Orm call to get all users from this group
	 * @return type array users from this group
	 */
	public function get_users_by_group($group = 'all', $offset = 0, $limit = 10)
	{
		$options = array('offset' => $offset, 'limit' => $limit);

		if ($group !== 'all')
		{
			$options['where'] = array(array('group', '=', $group));
		}

		return Model_User::find('all', $options);
	}
}

/* End of file user.php */