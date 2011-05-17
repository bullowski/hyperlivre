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
			\Response::redirect('home/404');
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

		$this->title = 'Books Index';
		$this->data['filter'] = $filter;
		$this->data['books'] =  Model_Book::get_filtered_books(
				$filter, $exclude_filter, Pagination::$offset, Pagination::$per_page);;
		$this->data['user_rights'] = $this->user_rights;
	}

	public function action_view($id) {
		if (empty($id) || !$book = Model_Book::find($id))
		{
			Response::redirect('books');
		}

		$hidden_books_access = Auth::acl()->has_access(
				array('books', array('view_hidden')), $this->user_group);

		//redirect if the user do not have enougth privileges
		if (Model_Book::status_name($book->status) === 'hidden' && !$hidden_books_access)
		{
			\Response::redirect('home/404');
		}

		$this->title = 'View Book - '.$book->title;
		$this->data['book'] = $book;
	}

	public function action_add()
	{
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
		if (empty($id) || !$book = Model_Book::find($id))
		{
			Response::redirect('books');
		}

		//shortcut to change the status on the fly
		if ($status !== null && Model_Book::$status_values[$status] !== null)
		{
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

		$form = Model_Book_Validation::edit($book);
        if ($form->validation()->run())
        {
			$book->title = $form->validated('title');
			$book->description = $form->validated('description');
			$book->status = $form->validated('status');
			
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

	//TODO archive notes?
	public function action_delete($id = null)
	{
		$book = Model_Book::find($id);
		if ($book && $book->delete())
		{
			Session::set_flash('notice', 'Deleted book #'.$id);
		}
		else
		{
			Session::set_flash('error', 'Could not delete book #'.$id);
		}

		Response::redirect('books');
	}

}