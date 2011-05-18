<?php

class Controller_Access extends Controller_Template
{
	protected $user_group = null;
	protected $user_id = null;
	protected $user_roles = null;
	protected $user_rights = null;

	public function router($method = 'index', $args = null)
	{
		//set the full method name
		$full_method = 'action_'.$method;
		$user_groups = Auth::instance()->get_groups();
		if ( ! method_exists($this, $full_method) || ! $user_groups)
		{
			Request::show_404();
		}

		//set user details
		if($user_groups && $user_groups !== null)
		{
			$this->user_group = $user_groups[0];
			$driver_n_user = Auth::instance()->get_user_id();
			$this->user_id = $driver_n_user[1];
		}

		//get the class name
		$class_array = explode('_', get_class($this));
		unset($class_array[0]);
		$class_array = array_map("strtolower", $class_array);

		//useful for the template view
		$this->page_id = implode('_', $class_array);
		$this->content = implode('/', $class_array).'/'.$method;

		$right = ($method === 'index') ? 'view' : $method;
		if ($this->user_group
				&& !Auth::acl()->has_access(
						array($this->page_id, array($right)), $this->user_group))
		{
			Request::show_404();
		}

		$this->user_roles = Auth::group()->get_roles($this->user_group[1]);

		$this->user_rights = Auth::acl()->get_rights($this->page_id, $this->user_roles);

		//change the template if the user is an admin
		if (Auth::acl()->has_access(
				array('admin', array('view', 'add', 'edit', 'delete')),
				$this->user_group))
		{
			$this->template = 'admin/template';
			parent::before();
		}

		// change the config if there is a cookie for the language
		Config::set('language', Cookie::get('language', 'fr'));

		// load the language file
		Lang::load($this->content);

		return call_user_func_array(array($this, $full_method), $args);
	}

}