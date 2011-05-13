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
		if ($filter === null || Model_Note::$status_values[$filter] === null) {
			\Response::redirect('home/404');
		}

		$total_notes = Model_Note::count_filtered_notes_by_author($this->user_id);

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
						'creator_id' => $this->user_id,
						'title' => $val->validated('title'),
						'body' => $val->validated('body'),
						'status' => $status,
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


		//////////////
//
//        $val->add('concept_id', 'Concept');
//        $val->add('title', 'Title')->add_rule('required');
//        $val->add('body', 'Body')->add_rule('required');


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
        $note = Model_Note::find_by_id_and_user_id($id, $this->user_id);

       	$val = Validation::factory('edit_note');
        $val->add('concept_id');
        $val->add('title')->add_rule('required');
        $val->add('body')->add_rule('required');

        if ($val->run())
        {
			if (!$val->input('concept_id'))
			{
				$concept_id = null;
			}
			else
			{
				$concept_id = $val->validated('concept_id');
			}

            $note->concept_id = $concept_id;
            $note->title = $val->validated('title');
            $note->body = $val->validated('body');

			if ($note->save())
			{
				Session::set_flash('success', 'Note successfully updated.');
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}

            Response::redirect('notes/edit/'.$note->id);
        }

        $this->template->title = 'Edit Note - '.$note->title;
        $this->template->content = View::factory('notes/edit')
			->set('concepts', Model_Concept::find('all'), false)
			->set('note', $note, false)
			->set('val', Validation::instance('edit_note'), false);
    }

    public function action_publish($id)
    {
        $note = Model_Note::find_by_id_and_user_id($id, $this->user_id);
        $note->published = 1;
        $note->save();

        Response::redirect('notes');
    }

    public function action_delete($id)
    {
        Model_Note::find_by_id_and_user_id($id, $this->user_id)->delete();

        Response::redirect('notes');
    }
}