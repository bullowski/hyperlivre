<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Auth;


class Auth_Group_SimpleGroup extends \Auth_Group_Driver {

	public static $_valid_groups = array();

	public static function _init()
	{
		static::$_valid_groups = array_keys(\Config::get('simpleauth.groups', array()));
	}

	protected $config = array(
		'drivers' => array('acl' => array('SimpleAcl'))
	);

	public function member($group, $user = null)
	{
		if ($user === null)
		{
			$groups = \Auth::instance()->get_groups();
		}
		else
		{
			// to be written...
			// $groups = \Auth::instance($user[0])->get_user_groups();
		}

		if ( ! $groups || ! in_array((int) $group, static::$_valid_groups))
		{
			return false;
		}

		return in_array(array($this->id, $group), $groups);
	}

	public function get_name($group)
	{
		return \Config::get('simpleauth.groups.'.$group.'.name', null);
	}

	public function get_group($name)
	{
		$groups = \Config::get('simpleauth.groups', array());
		foreach ($groups as $group => $params)
		{
			if($groups[$group]['name'] === $name) {
				return $group;
			}
		}
	}

	public static function get_group_names()
	{
		$groups = \Config::get('simpleauth.groups', array());
		foreach ($groups as $group => $params)
		{
			$groups[$group] = \Inflector::singularize($params['name']);
		}
		return $groups;
	}

	public function get_roles($group)
	{
		if ( ! in_array((int) $group, static::$_valid_groups))
		{
			return null;
		}

		$groups = \Config::get('simpleauth.groups');
		return $groups[(int) $group]['roles'];
	}
}

/* end of file simplegroup.php */
