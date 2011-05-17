<?php

class Controller_Notes extends Controller_Access
{

	public function action_index($filter = 'published', $current_page = 0)
	{
		//convert filter index to its correct string representation
		if (is_int($filter))
		{
			$status = Model_Note::status_names();
			$filter = $status[$filter];
		}
		//redirect if the filter value is not valid
		if ($filter === null ||
				($filter !== 'all' && Model_Note::$status_values[$filter] === null)) {
			Request::show_404();
		}

		$total_notes = Model_Note::count_filtered_notes_by_author($this->user_id, $filter);

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
				$this->user_id, $filter,
				Pagination::$offset, Pagination::$per_page);;
		$this->data['user_rights'] = $this->user_rights;
    }

	public function action_view($id) {
		if (empty($id) || !$note = Model_Note::find($id))
		{
			Response::redirect('notes');
		}

		//redirect if the user do not have enougth privileges
		if (Model_Note::status_name($note->status) === 'draft'
				&& $note->creator->$id !== $this->user_id)
		{
			Request::show_404();
		}

		$this->title = 'View Note - '.$note->title;
		$this->data['note'] = $note;
	}

    public function action_add()
    {
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

    public function action_edit($id)
    {

		if (empty($id) ||
				!$note =  Model_Note::find_by_id_and_creator_id($id, $this->user_id))
		{
			Response::redirect('notes');
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

    public function action_publish($id)
    {
        $note = Model_Note::find_by_id_and_creator_id($id, $this->user_id);
		if ($note)
		{
			$note->status = Model_Note::$status_values['published'];
			$note->save();
		}
        Response::redirect('notes');
    }

    public function action_delete($id)
    {
        Model_Note::find_by_id_and_creator_id($id, $this->user_id)->delete();

        Response::redirect('notes');
    }
}