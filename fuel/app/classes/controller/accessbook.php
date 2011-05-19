<?php

class Controller_Accessbook extends Controller_Access
{
	public function before()
	{
		$id = Auth::instance()->get_user_id();
		if (!$id || !$user = Model_User::find($id[1]))
		{
			Request::show_404();
		}

		$user_groups = Auth::instance()->get_groups();
		if ($user->active_book_id == null) {
			Session::set_flash('notice', 'Please select an active book first.');
			Response::redirect('books');
		}

		$this->data['active_book'] = $user->active_book;
		$this->active_book_id = $user->active_book_id;

		parent::before();
	}

}