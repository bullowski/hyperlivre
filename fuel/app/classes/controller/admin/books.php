<?php

class Controller_Admin_Books extends Controller_Admin 
{

	public function before() {
		parent::before();
	}

	public function action_index()
	{
		$this->title = 'Admin dashboard';
	}
	
	public function action_add()
	{
		//TODO
	}
	
	public function action_edit($id)
	{
		//TODO
	}
	
	publiC function action_delete($id = null)
	{
		//TODO
	}
}