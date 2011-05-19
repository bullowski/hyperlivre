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

	public function action_assign_book($id)
	{
		if (Input::post('activate'))
		{
			return $this->action_activate_book($id);
		}
		else if (Input::post('deactivate'))
		{
			return $this->action_deactivate_book($id);
		}
		else
		{
			Request::show_404();
		}
	}

	/*
	 * Selects the active book. The user must previously subscribe to a book
	 * before selecting it as his active book.
	 */
    public function action_activate_book($id)
    {
    	if (empty($id) || !$book = Model_Book::find($id))
		{
			Request::show_404();
		}

		// only set the active book if the user subscribed to it
		$user = Model_User::find($this->user_id);
		$user->active_book = $book;

		if ($user->save())
		{
			Session::set_flash('user', 'Book '.$book->title.' is now your active book.');
			Response::redirect('notes');
		}
		else
		{
			Session::set_flash('error', 'Something went wrong, please try again!');
			Response::redirect('books');
		}

    }

	//TODO test if the active book was also removed from the active_users array in the Model_Book
	public function action_deactivate_book($id)
	{
		$user = Model_User::find($this->user_id);
		if (empty($id) || $id !== $user->active_book_id)
		{
			Request::show_404();
		}

		// only deactivate book if the user previously activated it
		//--- this works but doc says : unset($user->active_book) which does not work
		//--- so, test if the active book was also removed from the active_users array
		$user->active_book_id = null;

		if ($user->save())
		{
			Session::set_flash('user', 'You do not have an active book anymore.');
			Response::redirect('books');
		}
		else
		{
			Session::set_flash('error', 'Something went wrong, please try again!');
			Response::redirect('books');
		}

	}


}