<?php
class Controller_Concepts extends Controller_Template {
	
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
	
	
	
}

/* End of file concepts.php */