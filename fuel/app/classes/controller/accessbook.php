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
		$this->active_book_status = Model_Book::status_name($user->active_book->status);

		//show 404 if not allowed to add notes in this book
		$active_book_status = Model_Book::status_name($user->active_book->status);
		$admin_access = Auth::acl()->has_access(
				array('admin', array('view', 'add', 'edit', 'delete')),
				$user_groups[0]);

		if ($active_book_status === 'hidden' && !$admin_access)
		{
			Request::show_404();
		}
		elseif ($active_book_status !== 'archive'
				&& !key_exists($this->active_book_id, $user->books)
				&& !$admin_access)
		{
			Request::show_404();
		}

		parent::before();
	}

}