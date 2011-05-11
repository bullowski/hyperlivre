<?php

class Controller_Admin_Dashboard extends Controller_Admin 
{

	public function before() {
		parent::before();
	}

	public function action_index()
	{
		$this->title = 'Admin dashboard';
	}
	
}