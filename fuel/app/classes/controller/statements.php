<?php

class Controller_Statements extends Controller_Accessbook
{
	public function action_add($subject_type, $subject_id, $property_name,
			$object_type, $object_id)
    {
		if ($this->active_book_status === 'archive')
		{
			Request::show_404();
		}

		if (!$property = Model_Property::find_by_name($property_name))
		{
			Request::show_404();
		}
		if (!$subject_class = Model_Class::find_by_name($subject_type))
		{
			Request::show_404();
		}
		$subject_model = 'Model_'.ucfirst($subject_class->name);
		if (class_exists($subject_model) && method_exists($subject_model, 'find'))
		{
			if (!$subject = $subject_model::find($subject_id))
			{
				Request::show_404();
			}
		}

		$object_model = 'Model_'.ucfirst($object_class->name);
		if (class_exists($object_model) && method_exists($object_model, 'find'))
		{
			if (!$object = $object_model::find($object_id))
			{
				Request::show_404();
			}
		}

		$statement = new Model_Statement(array(
			'subject_id' => $subject_id,
			'subject_type' => $subject_class->id,
			'object_id' => $object_id,
			'object_type' => $object_class->id
		));
		//link the statement to the current book and user
		$statement->property = $property;
		$statement->creator = $this->user_id;
		$statement->book = $this->data['active_book'];

		if ($statement->save())
		{
			Session::set_flash('success', 'The statement was added to the active book');
		}
		else
		{
			Session::set_flash('error', 'Something went wrong, please try again!');
		}

		Response::redirect('user/dashboard');
	}

}
