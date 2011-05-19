<?php

class Controller_Notes extends Controller_Accessbook
{
	public function action_index($filter = 'published', $current_page = 0)
	{
		//convert filter index to its correct string representation
		if (is_int($filter))
		{
			$filter = Model_Note::status_name($status);
		}

		//set defaults
		$author_id = 'all';	//no related notes by default
		$exclude_filter = 'draft';	//cannot see draft notes
		$this->data['my'] = '';

		//if the filter begins with 'my_' only consider personnal notes
		$filter_array = explode('_', $filter);
		if (in_array('my', $filter_array) && count($filter_array) == 2
				&& in_array('add', $this->user_rights))
		{
			$filter = $filter_array[1];
			$author_id = $this->user_id;
			$exclude_filter = false;
			$this->data['my'] = 'my_';
		}

		//redirect if the filter value is not valid
		if ($filter === null ||
				($filter !== 'all' && !key_exists($filter, Model_Note::$status_values)))
		{
			Request::show_404();
		}

		$total_notes = Model_Note::count_filtered_notes_by_author(
				$this->active_book_id, $author_id, $filter, $exclude_filter);

		Pagination::set_config(array(
			'pagination_url' => 'notes/index/'.$filter.'/',
			'per_page' => 10,
			'total_items' => $total_notes,
			'num_links' => 3,
			'current_page' => $current_page,
        ));

		$this->title = 'My Notes';
		$this->data['filter'] = $filter;
		$this->data['notes'] =  Model_Note::get_filtered_notes_by_author(
				$this->active_book_id, $author_id, $filter, $exclude_filter,
				Pagination::$offset, Pagination::$per_page);;
		$this->data['user_rights'] = $this->user_rights;
    }

	public function action_view($id)
	{
		if (empty($id) || !$note = Model_Note::find($id))
		{
			Request::show_404();
		}

		if ($note->book_id != $this->active_book_id)
		{
			Request::show_404();
		}

		//redirect if the user do not have enougth privileges
		if (Model_Note::status_name($note->status) === 'draft'
				&& $note->creator_id != $this->user_id)
		{
			Request::show_404();
		}

		$this->title = 'View Note - '.$note->title;
		$this->data['note'] = $note;
		$this->data['user_id'] = $this->user_id;

		$exclude_filter = 'hidden';
		$comments_rights = Auth::acl()->get_rights('comments', $this->user_roles);
		foreach ($comments_rights as $right)
		{
			$this->data['user_rights'][] = $right."_comments";
			if ($right === 'view_hidden') {
				$exclude_filter = false;
			}
		}
		$this->data['comments'] = Model_Comment::get_filtered_comments_by_note(
				$id, 'all', $exclude_filter);
	}

    public function action_add()
    {
    	if (Input::post('cancel'))
        {
            Session::set_flash('warning', 'You canceled the creation of the note.');
			Response::redirect('notes');
		}

		$form = Model_Note_Validation::add();
		if ($form->validation()->run())
		{
			if (Input::post('draft'))
            {
                $status = 'draft';
            }
            else
            {
                $status = 'published';
            }

			$note = new Model_Note(array(
						'title' => $form->validated('title'),
						'body' => $form->validated('body'),
						'status' => Model_Note::$status_values[$status],
						'creator_id' => $this->user_id,
					));

			//link the note to the active book
			$note->book = $this->data['active_book'];

			if ($note->save())
			{
				if ($status === 'draft')
				{
					Session::set_flash('success', 'Note successfully added.');
				}
				else
				{
					Session::set_flash('success', 'Note was saved as draft.');
				}
				Response::redirect('notes');
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}

			Response::redirect('notes/add');
		}

		$this->title = 'Add Note';
		$this->data['form'] = $form;
		$this->data['concepts'] = Model_Concept::find('all');

//TODO add concepts lik
//
//			if (!$val->input('concept_id'))
//			{
//				$concept_id = null;
//			}
//			else
//			{
//				$concept_id = $val->validated('concept_id');
//			}

    }

    public function action_edit($id, $status = null)
    {
		if (Input::post('cancel'))
        {
            Session::set_flash('warning', 'You canceled the update of the note.');
			Response::redirect('notes');
		}

		if (empty($id)
				|| !$note = Model_Note::find($id)
				|| $note->book_id != $this->active_book_id)
		{
			Request::show_404();
		}

		if ($note->book_id != $this->active_book_id)
		{
			Request::show_404();
		}

		if (!Auth::acl()->has_access(
				array('notes', array('super_edit')), $this->user_group))
		{
			if(!$note = Model_Note::find_by_id_and_creator_id($id, $this->user_id))
			{
				Request::show_404();
			}
		}

		//shortcut to change the status on the fly
		if ($status !== null && key_exists($status, Model_Note::$status_values))
		{
			$note->status = Model_Note::$status_values[$status];
			if ($note->save())
			{
				Session::set_flash('success', 'Status '.$status.' was assigned to the note '.
						$note->title.' (#'.$id.')');
				Response::redirect('notes');
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}
		}

		$form = Model_Note_Validation::edit($note);
        if ($form->validation()->run())
        {
			$note->title = $form->validated('title');
            $note->body = $form->validated('body');

            if (Input::post('draft'))
            {
                 $note->status = Model_Note::$status_values['draft'];
            }
            else
            {
                $note->status = Model_Note::$status_values['published'];
            }


			if ($note->save())
			{
				if ($status === 'draft')
				{
					Session::set_flash('success', 'Note successfully added.');
				}
				else
				{
					Session::set_flash('success', 'Note was saved as draft.');
				}
				Response::redirect('notes');
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}

			Response::redirect('notes/edit/'.$note->id);
		}

		$this->title = 'Edit Note - '.$note->title;
		$this->data['note'] = $note;
		$this->data['form'] = $form;
		$this->data['concepts'] = Model_Concept::find('all');

///////////
//
//TODO add concepts links
//
//        if (!$val->input('concept_id'))
//			{
//				$concept_id = null;
//			}
//			else
//			{
//				$concept_id = $val->validated('concept_id');
//			}

    }

    public function action_delete($id)
    {
		if (empty($id)
				|| !$note = Model_Note::find($id)
				|| $note->book_id != $this->active_book_id)
		{
			Request::show_404();
		}
		if ($note->book_id != $this->active_book_id)
		{
			Request::show_404();
		}

		if (!Auth::acl()->has_access(
				array('notes', array('super_delete')), $this->user_group))
		{
			if(!$note = Model_Note::find_by_id_and_creator_id($id, $this->user_id))
			{
				Request::show_404();
			}
		}

		if ($note && $note->delete())
		{
			Session::set_flash('notice', 'The note "'.$note->title.'" (#'.$id.')'
					.' was successfully deleted.');
		}
		else
		{
			Session::set_flash('error', 'Could not delete note #'.$id);
		}

		Response::redirect('notes');
	}


}