<?php

class Controller_Concepts extends Controller_Access
{

	public function action_index()
	{
		$this->title = "Concepts";
		$this->data['concepts'] = Model_Concept::find('all');
		$this->data['user_roles'] = $this->user_roles;
		$this->data['user_group'] = $this->user_group;
		$this->data['user_rights'] = $this->user_rights;
	}

	public function action_view($id = null)
	{
		$data['concepts'] = Model_Concept::find($id);

		$this->template->title = "Concepts";
		$this->template->content = View::factory('concepts/view', $data);
	}

	public function action_add($id = null)
	{
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
		if (empty($id) || !$concept = Model_Concept::find($id))
		{
			Response::redirect('concepts');
		}

		$form = Model_Concept_Validation::edit($concept);
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