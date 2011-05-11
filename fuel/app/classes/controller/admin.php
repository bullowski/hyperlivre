<?php

abstract class Controller_Admin extends Controller_Template
{
	protected $user_id = null;
	//TODO test
	protected $user_group = null;

	public function before()
	{
		$this->template = 'admin/template';
		parent::before();

		$user_groups = Auth::get_groups();
		if ($user_groups &&
				Auth::acl()->has_access(
						array('admin', array('create', 'read', 'update', 'delete')),
						$user_groups[0]))
		{
			$user = Auth::instance()->get_user_id();

            $this->user_id = $user[1];
			$this->user_group = Auth::group()->get_name($user_groups[0][1]);
		}
		else
		{
			Response::redirect('/');
		}

	}

}