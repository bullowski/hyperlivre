<?php

class Model_User extends Orm\Model
{
	protected static $_table_name = 'users';
//	protected static $_belongs_to	= array('notes');
//	protected static $_has_many 	= array('notes', 'teams');
	protected static $_properties = array('id', 'username', 'password',
		'email', 'profile_fields',
		'group', 'last_login', 'login_hash');
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