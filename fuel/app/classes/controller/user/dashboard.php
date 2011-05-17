<?php

class Controller_User_Dashboard extends Controller_User {

    public function action_index()
    {
        $this->title = 'Dashboard';
    }
	
    public function action_active_book($id)
    {
    	if (empty($id) || !$book = Model_Book::find($id))
		{
			Response::redirect('books');
		}
		
		$current_user = Model_User::find($this->user_id);
		$current_user->active_book = $book;
		
    	if ($current_user->save())
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