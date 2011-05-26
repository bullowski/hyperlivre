<?php

class Controller_Concepts extends Controller_Accessbook
{

	public function action_index($current_page = 0)
	{
		$concepts = Model_Concept::find('all');

		Pagination::set_config(array(
			'pagination_url' => 'concepts/index',
			'per_page' => 10,
			'total_items' => count($concepts),
			'num_links' => 3,
			'current_page' => $current_page,
        ));

		$this->title = "Concepts";
		$this->data['concepts'] = $concepts;
		$this->data['user_rights'] = $this->user_rights;
	}

	public function action_view($id = null)
	{
		if (empty($id) || !$concept = Model_Concept::find($id))
		{
			Request::show_404();
		}

		$this->title = $concept->title;
		$this->data['concept'] = $concept;
	}

	public function action_add($id = null)
	{
		if (Input::post('cancel'))
        {
            Session::set_flash('warning', 'You canceled the creation of the concept.');
			Response::redirect('concepts');
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
		if (Input::post('cancel'))
        {
            Session::set_flash('warning', 'You canceled the edition of the concept.');
			Response::redirect('concepts');
		}

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