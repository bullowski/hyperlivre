<?php
class Controller_Concepts extends Controller_Template {

	protected static $access = array();

	

	/*
		$user_groups = Auth::get_groups();
		if($this->user_groups &&
				!Auth::acl()->has_access(
						array('concepts', array('delete')),
						$user_groups[0]))
		{

		}

	*/

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
		if ($this->user_groups &&
				!Auth::acl()->has_access(
						array('concepts', array('create')),
						$this->user_groups[0]))
		{
			Response::redirect('/');
		}



	}

	public function action_edit($id = null)
	{
		if ($this->user_groups &&
				!Auth::acl()->has_access(
						array('concepts', array('update')),
						$this->user_groups[0]))
		{
			Response::redirect('/');
		}



	}

	//TODO archive notes?
	public function action_delete($id = null)
	{


		if ($concepts = Model_Concept::find($id))
		{
			$concepts->delete();

			Session::set_flash('notice', 'Deleted concept #' . $id);
		}

		else
		{
			Session::set_flash('error', 'Could not delete concept #' . $id);
		}

		Response::redirect('concepts');
	}

}

/* End of file concepts.php */