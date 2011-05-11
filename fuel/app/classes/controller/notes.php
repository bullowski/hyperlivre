<?php

class Controller_Notes extends Controller_User {
	
	public function before()
	{
    	parent::before();
	}
	
	public function action_index($filter = 'published')
	{
		if ($filter === 'draft')
		{
			$published = 0;
		}
		else if ($filter === 'archive')
		{
			$published = -1;
		} 
		else
		{
			$published = 1;
		}
		
		$total_notes = Model_Note::count_user_notes($this->user_id);
		
		Pagination::set_config(array(
			'pagination_url' => 'notes/index/'.$filter.'/',
			'per_page' => 10,
			'total_items' => $total_notes,
			'num_links' => 3,
			'uri_segment' => 4,
        ));

		$notes = Model_Note::find('all', array(
			'offset' => Pagination::$offset,
			'limit' => Pagination::$per_page,
			'include' => 'concept',
			'where' => array(
				array('user_id', '=', $this->user_id),
				array('published', '=', $published),
			),
		));
        
        $this->template->title = 'Notes';
        $this->template->content = View::factory('notes/index')
			->set('total_notes', $total_notes)
			->set('notes', $notes, false)
			->set('filter', $filter);
    }
    
    public function action_new()
    {
        $val = Validation::factory('add_note');
        $val->add('concept_id', 'Concept');
        $val->add('title', 'Title')->add_rule('required');
        $val->add('body', 'Body')->add_rule('required');
        
        if ($val->run())
        {
            if (Input::post('save_draft'))
            {
                $status = 0;
            }
            else
            {
                $status = 1;
            }
            
			if (!$val->input('concept_id'))
			{
				$concept_id = null;
			}
			else
			{
				$concept_id = $val->validated('concept_id');
			}
			
            $note = new Model_Note(array(
				'user_id' => $this->user_id,
                'concept_id' => $concept_id,
                'title' => $val->validated('title'),
                'body' => $val->validated('body'),
                'created_at' => Date::factory()->get_timestamp(),
                'published' => $status,
            ));

            if ($note->save())
			{
				Session::set_flash('success', 'Note successfully added.');
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}
            
            Response::redirect('notes/add');
        }
        
        $this->template->title = 'Add Note';
        $this->template->content = View::factory('notes/add')
			->set('concepts', Model_Concept::find('all'), false)
			->set('val', Validation::instance('add_note'), false);
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