<?php

class Controller_User_Dashboard extends Controller_User {

    public function action_index()
    {
        $this->title = 'Dashboard';
    }

	/*
	 * Selects the active book. The user must previously subscribe to a book
	 * before activating it.
	 */
    public function action_active_book($id)
    {
    	if (empty($id) || !$book = Model_Book::find($id))
		{
			Response::redirect('books');
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
		}

    }

}