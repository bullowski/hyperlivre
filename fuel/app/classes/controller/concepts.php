<?php

class Controller_Concepts extends Controller_Template
{
	protected static $user_group = null;

	public  function before()
	{
		parent::before();
		$user_groups = Auth::get_groups();
		if($user_groups !== null)
		{
			$this->user_group = $user_groups[0];
		}
	}

	public function action_index()
	{
		$this->title = "Concepts";
		$this->data['concepts'] = Model_Concept::find('all');
	}

	public function action_view($id = null)
	{
		$data['concepts'] = Model_Concepts::find($id);

		$this->template->title = "Concepts";
		$this->template->content = View::factory('concepts/view', $data);
	}

	public function action_create($id = null)
	{
		if ($this->user_group &&
				!Auth::acl()->has_access(
						array('concepts', array('create')), $this->user_group))
		{
			Response::redirect('/');
		}

		$form = Model_Concept_Validation::add();
		if ($form->validation()->run())
		{
			$concept = new Model_Concept(array(
						'title' => $form->validated('title'),
						'description' => $form->validated('description'),
						'creator_id' => $this->user_id,
					));

			if ($concept->save())
			{
				Session::set_flash('success', 'Concept successfully added.');
				Response::redirect('concepts');
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}

			Response::redirect('concepts/add');
		}

		$this->title = 'Add Concept';
		$this->data['form'] = $form;
	}

	public function action_edit($id = null)
	{
		if ($this->user_group &&
				!Auth::acl()->has_access(
						array('concepts', array('update')), $this->user_group))
		{
			Response::redirect('/');
		}

		if (empty($id) || !$concept = Model_Concept::find($id))
		{
			Response::redirect('concepts');
		}

		$form = Model_Book_Validation::edit($concept);
        if ($form->validation()->run())
        {
			$concept->title = $form->validated('title');
			$concept->description = $form->validated('description');

            if ($concept->save())
			{
				Session::set_flash('success', 'Concept successfully added.');
				Response::redirect('concepts');
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}

			Response::redirect('concepts/edit/'.$concept->id);
		}

		$this->title = 'Edit Concept - '.$concept->title;
		$this->data['concept'] = $concept;
		$this->data['form'] = $form;

	}

	//TODO archive notes?
	public function action_delete($id = null)
	{
		if ($this->user_group &&
				!Auth::acl()->has_access(
						array('concepts', array('delete')), $this->user_group))
		{
			Response::redirect('/');
		}

		$concept = Model_Concept::find($id);
		if ($concept && $concept->delete())
		{
			Session::set_flash('notice', 'Deleted concept #'.$id);
		}
		else
		{
			Session::set_flash('error', 'Could not delete concept #'.$id);
		}

		Response::redirect('concepts');
	}

}

/* End of file concepts.php */