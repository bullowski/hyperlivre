<?php

class Controller_User_Dashboard extends Controller_User {

    public function action_index()
    {
        $this->title = 'Dashboard';
    }

	public function action_assign_book($id)
	{
		if (Input::post('activate'))
		{
			return $this->action_active_book($id);
		}
		else if (Input::post('deactivate'))
		{
			return $this->action_deactive_book($id);
		}
		else if (Input::post('subscribe'))
		{
			return $this->action_subscribe_book($id);
		}
		else if (Input::post('unsubscribe'))
		{
			return $this->action_unsubscribe_book($id);
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
    public function action_active_book($id)
    {
    	if (empty($id) || !$book = Model_Book::find($id))
		{
			Request::show_404();
		}

		$user_groups = Auth::instance()->get_groups();
		$hidden_books_access = Auth::acl()->has_access(
				array('books', array('view_hidden')), $user_groups[0]);
		$exclude_filter = $hidden_books_access ? false : 'hidden';

		$activable_books = Model_Book::get_activable_books_by_user(
				$this->user_id, 'all', $exclude_filter);

		if (!in_array($book, $activable_books))
		{
			Request::show_404();
		}

		// only set the active book if the user subscribed to it
		$user = Model_User::find($this->user_id);
		$user->active_book = $book;


		if ($user->save())
		{
			Session::set_flash('user', 'Book '.$book->title.' is now your active book.');
			Response::redirect('/');
		}
		else
		{
			Session::set_flash('error', 'Something went wrong, please try again!');
			Response::redirect('books');
		}

    }


	public function action_deactive_book($id){
		//TODO
	}

	public function action_subscribe_book($id)
	{
		Config::load('auth', true);
		if (empty($id)
				|| !Config::get('auth.subscribe', false)
				|| !key_exists($id, Model_Book::get_filtered_books('open')))
		{
			Request::show_404();
		}

		$user = Model_User::find($this->user_id);
		$user->books[$id] = Model_Book::find($id);
		if ($user->save())
		{
			Session::set_flash('user', 'You successfully subscribed to the Book "'.$book->title.'".');
			Response::redirect('/');
		}
		else
		{
			Session::set_flash('error', 'Something went wrong, please try again!');
			Response::redirect('books');
		}

	}

	public function action_unsubscribe_book($id){
		//TODO
	}

}