<?php

class Controller_Admin_Users extends Controller_Admin
{

	public function before()
	{
		parent::before();
	}

	public function action_index($filter = 'all', $offset = 0)
	{
		$group = ($filter === 'all') ? 'all' : Auth::group()->get_group($filter);
		$this->data['total_users'] = Model_User::count_users($group);

		Pagination::$current_page = $offset;
		Pagination::set_config(array(
			'pagination_url' => 'admin/users/index/'.$group.'/',
			'per_page' => 10,
			'total_items' => $this->data['total_users'],
			'num_links' => 3,
			'uri_segment' => 5
		));

		$this->title = 'Admin Users Index';

		$this->data['filter'] = $filter;
		$this->data['users'] =
				Model_User::get_users_by_group(
						$group, Pagination::$offset, Pagination::$per_page);
	}


	public function action_add()
	{
		if (Input::post('cancel'))
        {
            Session::set_flash('warning', 'You canceled the creation of the user.');
			Response::redirect('admin/users');
		}

		$form = Model_User_Validation::add();
        if ($form->validation()->run())
        {
            if ($user_id = Auth::instance()
					->create_user(	$form->validated('username'),
									$form->validated('password'),
									$form->validated('email'),
									$form->validated('group')))
			{

				$user = Model_User::find($user_id);
				$selected_books = $form->validated('book');
				if (is_string($selected_books))
				{
					$selected_books = array($selected_books);
				}

				// add/update all the selected books
				foreach ($selected_books as $book_id)
				{
					$user->books[$book_id] = Model_Book::find($book_id);
				}

				if ($user->save())
				{
					Session::set_flash('success', 'User successfully added.');
					Response::redirect('admin/users');
				}
				else
				{
					Session::set_flash('error', 'Something went wrong, please try again!');
				}
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}

			Response::redirect('admin/users/add');
		}

		$this->title = 'Add User';
		$this->data['form'] = $form;
	}

	public function action_edit($id)
	{
		if (Input::post('cancel'))
        {
			Session::set_flash('warning', 'You canceled the edition of the user. '.
                				'All your changes has been ignored.');
			Response::redirect('admin/users');
        }

		if (empty($id) || !$user = Model_User::find($id))
		{
			Response::redirect('admin/users');
		}

		$form = Model_User_Validation::edit($user);
        if ($form->validation()->run())
        {
			$user->username = $form->validated('username');
			$user->email = $form->validated('email');
			$user->group = $form->validated('group');

            $selected_books = $form->validated('book');
			if (is_string($selected_books))
			{
				$selected_books = array($selected_books);
			}
			// remove the unselected books if previously linked
            foreach ($user->books as $book) {
            	if (! in_array($book->id, $selected_books))
            	{
            		unset($user->books[$book->id]);
            		// unsubscribe
            		if ($user->active_book_id == $book->id)
            		{
            			$user->active_book_id = null;
            		}
            	}
            }

			// add/update all the selected books
            foreach ($selected_books as $book_id)
            {
            	$user->books[$book_id] = Model_Book::find($book_id);
            }

            if ($user->save())
			{
				Session::set_flash('success', 'User successfully updated.');
				Response::redirect('admin/users');
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}

			Response::redirect('admin/users/edit/'.$user->id);
		}

		$this->title = 'Edit User - '.$user->username;
		$this->data['user'] = $user;
		$this->data['form'] = $form;
	}


	//TODO threat notes dependencies
	publiC function action_delete($id = null)
	{
		$user = Model_User::find($id);

		if ($user and $user->delete())
		{
			Session::set_flash('notice', 'User '.$user->username.
					' (#'.$id.') was successfully removed.');
		}
		else
		{
			Session::set_flash('error', 'Something went wrong, please try again!');
		}

		//TODO keep the same filter by checking the uri segment
		Response::redirect('admin/users');
	}

}

/* End of file users.php */