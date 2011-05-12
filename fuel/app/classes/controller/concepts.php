<?php

class Controller_Concepts extends Controller_Template
{
	protected $user_group = null;
	protected $user_id = null;

	public function router($method = 'index', $args = null)
	{
		//set the full method name
		$full_method = 'action_'.$method;
		$user_groups = Auth::instance()->get_groups();
		if ( ! method_exists($this, $full_method) || ! $user_groups)
		{
			Response::redirect('home/404');
		}

		//set user details
		if($user_groups && $user_groups !== null)
		{
			$this->user_group = $user_groups[0];
			$driver_n_user = Auth::instance()->get_user_id();
			$this->user_id = $driver_n_user[1];
		}

		//get the class name
		$class_array = explode('_', get_class($this));
		unset($class_array[0]);
		$class_array = array_map("strtolower", $class_array);

		//useful for the template view
		$this->page_id = implode('_', $class_array);
		$this->content = implode(DS, $class_array).DS.$method;

		//logs
		Log::debug('user_group: '.var_export($this->user_group,true));
		Log::debug('user_id: '.$this->user_id);


		$permission = ($method === 'index') ? 'view' : $method;
		if ($this->user_group &&
				!Auth::acl()->has_access(
						array($this->page_id, array($permission)), $this->user_group))
		{
			Response::redirect('home/404');
		}

		//change the template if the user is an admin
		if (Auth::acl()->has_access(
				array('admin', array('view', 'add', 'edit', 'delete')),
				$this->user_group))
		{
			$this->template = 'admin/template';
			parent::before();
		}

		return call_user_func_array(array($this, $full_method), $args);
	}

	public function action_index()
	{
		$this->title = "Concepts";
		$this->data['concepts'] = Model_Concept::find('all');
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