<?php

class Controller_Books extends Controller_Access
{

	public function action_index($filter = 'all', $current_page = 0)
	{
		//convert filter index to its correct string representation
		if (is_int($filter))
		{
			$status = Model_Book::status_names();
			$filter = $status[$filter];
		}

		$hidden_books_access = Auth::acl()->has_access(
				array('books', array('view_hidden')), $this->user_group);
		//redirect if the filter value is not valid
		if ($filter === null
				|| ($filter !== 'all' && Model_Book::$status_values[$filter] === null)
				|| ($filter === 'hidden' && !$hidden_books_access))
		{
			Request::show_404();
		}

		$exclude_filter = $hidden_books_access ? false : 'hidden';
		$total_books = Model_Book::count_filtered_books($filter, $exclude_filter);

		Pagination::set_config(array(
			'pagination_url' => 'books/index/'.$filter.'/',
			'per_page' => 10,
			'total_items' => $total_books,
			'num_links' => 3,
			'current_page' => $current_page,
        ));

		$books = Model_Book::get_filtered_books(
				$filter, $exclude_filter, Pagination::$offset, Pagination::$per_page);

		$forms = array();
		foreach ($books as $id => $book)
		{
			$forms[$id] = $this->build_view_form($book);
		}

		$this->title = 'Books Index';
		$this->data['filter'] = $filter;
		$this->data['books'] = $books;
		$this->data['user_rights'] = $this->user_rights;
		$this->data['forms'] = $forms;
		$this->data['hidden_books_access'] = $hidden_books_access;
	}

	public function action_view($id) {
		if (empty($id) || !$book = Model_Book::find($id))
		{
			Response::redirect('books');
		}

		$hidden_books_access = Auth::acl()->has_access(
				array('books', array('view_hidden')), $this->user_group);

		//redirect if the user do not have 'view_hidden' privileges
		if (Model_Book::status_name($book->status) === 'hidden' && !$hidden_books_access)
		{
			Request::show_404();
		}

		$form = $this->build_view_form($book);

		$this->title = 'View Book - '.$book->title;
		$this->data['book'] = $book;
		$this->data['form'] = $form;
	}

	public function build_view_form($book)
	{
		$user = Model_User::find($this->user_id);
		$form = Fieldset::factory('view_book'.$book->id);
		Config::load('auth', true);

		$area = 'user';
		if (Auth::acl()->has_access(array('admin', array('view')), $this->user_group))
		{
			if ($book->id !== $user->active_book_id)
			{
				$form->add('activate', null,
						array(	'type' => 'submit',
								'value' => 'Select as active'));
			}
			else
			{
				$form->add('deactivate', null,
						array(	'type' => 'submit',
								'value' => 'Unselect'));
			}
			$area = 'admin';

		}
		else if (in_array($book, $user->books))
		{
			if (Config::get('auth.unsubscribe', false))
			{
				$form->add('unsubscribe', null,
							array(	'type' => 'submit',
									'value' => 'Un-subscribe'));
			}

			if ($book->id !== $user->active_book_id)
			{
				$form->add('activate', null,
						array(	'type' => 'submit',
								'value' => 'Select as active'));
			}
			else
			{
				$form->add('deactivate', null,
						array(	'type' => 'submit',
								'value' => 'Unselect'));
			}
		}
		else if (in_array($book, Model_Book::get_filtered_books('open'))
				&& Config::get('auth.subscribe', false))
		{
			// can subscribe to an 'open' status book.
			$form->add('subscribe', null,
						array(	'type' => 'submit',
								'value' => 'Subscribe'));
		}
		else if (in_array($book, Model_Book::get_filtered_books('archive')))
		{
			if ($book->id !== $user->active_book_id)
			{
				$form->add('activate', null,
						array(	'type' => 'submit',
								'value' => 'Select as active'));
			}
			else
			{
				$form->add('deactivate', null,
						array(	'type' => 'submit',
								'value' => 'Unselect'));
			}
		}

		return $form->build($area.'/dashboard/assign_book/'.$book->id);
	}


	public function action_add()
	{
		if (Input::post('cancel'))
        {
            Session::set_flash('warning', 'You canceled the creation of the book.');
			Response::redirect('books');
		}

		$form = Model_Book_Validation::add();
		if ($form->validation()->run())
		{
			$book = new Model_Book(array(
						'title' => $form->validated('title'),
						'description' => $form->validated('description'),
						'status' => $form->validated('status'),
						'creator_id' => $this->user_id,
				));

			if ($book->save())
			{
				$selected_users = $form->validated('user');
				if (is_string($selected_users))
				{
					$selected_users = array($selected_users);
				}
				// add/update all the selected books
	            foreach ($selected_users as $user_id)
	            {
	            	$book->users[$user_id] = Model_User::find($user_id);
	            }

	            if ($book->save())
				{

					Session::set_flash('success', 'Book successfully added with status '.
							Model_Book::status_name($book->status));
					Response::redirect('books');
				}
				else {
					Session::set_flash('error', 'Something went wrong, please try again!');
				}
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}

			Response::redirect('books/add');
		}

		$this->title = 'Add Book';
		$this->data['form'] = $form;
	}

	public function action_edit($id, $status = null)
	{
		if (Input::post('cancel'))
        {
            Session::set_flash('warning', 'You canceled the edition of the book. '.
                				'All your changes has been ignored.');
			Response::redirect('books');
		}

		if (empty($id) || !$book = Model_Book::find($id))
		{
			Response::redirect('books');
		}

		//shortcut to change the status on the fly
		if ($book !== null && $status !== null
				&& key_exists($status, Model_Book::$status_values))
		{
			$this->change_status($book, $status);
		}

		$form = Model_Book_Validation::edit($book);
        if ($form->validation()->run())
        {
			$book->title = $form->validated('title');
			$book->description = $form->validated('description');

			$this->change_status($book,
					Model_Book::status_name($form->validated('status')));

        	$selected_users = $form->validated('user');
			if (is_string($selected_users))
			{
				$selected_users = array($selected_users);
			}
			// remove the unselected users if previously linked
            foreach ($book->users as $user) {
            	if (! in_array($user->id, $selected_users))
            	{
            		unset($book->users[$user->id]);
            		// unsubscribe
            		if ($user->active_book_id == $book->id)
            		{
            			unset($book->active_users[$user->id]);
            		}
            	}
            }

        	// add/update all the selected users
            foreach ($selected_users as $user_id)
            {
            	$book->users[$user_id] = Model_User::find($user_id);
            }

            if ($book->save())
			{
				Session::set_flash('success', 'Book successfully updated with status '.
						Model_Book::status_name($book->status));
				Response::redirect('books');
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}

			Response::redirect('books/edit/'.$book->id);
		}

		$this->title = 'Edit Book - '.$book->title;
		$this->data['book'] = $book;
		$this->data['form'] = $form;
	}

	// delete cascade
	public function action_delete($id = null, $archive = false)
	{
		if (Model_Book::remove($id, $archive))
		{
			if ($archive)
			{
				Session::set_flash('notice', 'The book #'.$id.' was archived.');
			}
			else
			{
				Session::set_flash('success', 'The book #'.$id.' was deleted.');
			}
		}
		else
		{
			Session::set_flash('error', 'Could not delete book #'.$id);
		}

		Response::redirect('books');
	}


	private function change_status($book = null, $status = null)
	{
		if ($status === 'archive')
		{
			return $this->action_delete($id, true);
		}
		elseif ($status === 'hidden')
		{
			$driver = $this->user_group[0];
			foreach ($book->active_users as $user)
			{
				if (!Auth::acl()->has_access(
						array('books', array('view_hidden')),
						array($driver, $user->group)))
				{
					unset($book->active_users[$user->id]);
				}
			}
		}

		$book->status = Model_Book::$status_values[$status];
		if ($book->save())
		{
			Session::set_flash('success', 'Status '.$status.' was assigned to the book'.
					$book->title.' (#'.$book->id.')');
			Response::redirect('books');
		}
		else
		{
			Session::set_flash('error', 'Something went wrong, please try again!');
		}

	}

}