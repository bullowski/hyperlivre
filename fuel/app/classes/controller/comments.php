<?php

class Controller_Comments extends Controller_Access
{

	public function action_add($note_id)
	{
		if (empty($id) || !$note = Model_Note::find($note_id))
		{
			Request::show_404();
		}

		if (Input::post('cancel'))
        {
            Session::set_flash('warning', 'You canceled the creation of this comment.');
			Response::redirect('notes/view/'.$note_id);
		}

		$form = Model_Comment_Validation::add();
		if ($form->validation()->run())
		{
			$comment = new Model_Comment(array(
						'title' => $form->validated('title'),
						'comment' => $form->validated('description'),
						'status' => Model_Comment::$status_values('published'),
						'user_id' => $this->user_id,
						'note_id' => $note_id,
				));

			if ($comment->save())
			{
				Session::set_flash('success', 'Comment successfully added.');
				Response::redirect('notes/view/'.note_id);
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}

			Response::redirect('comments/add');
		}

		$this->title = 'Comment';
		$this->data['form'] = $form;
	}

	public function action_edit($id, $status = null)
	{
		if (empty($id) || !$comment = Model_Comment::find($id))
		{
			Request::show_404();
		}

		if (Input::post('cancel'))
        {
            Session::set_flash('warning', 'You canceled the edition of the last comment. '.
                				'All your changes has been ignored.');
			Response::redirect('notes/view/'.$comment->note_id);
		}

		//shortcut to change the status on the fly
		if ($status !== null && key_exists($status, Model_Comment::$status_values))
		{
			$comment->status = Model_Comment::$status_values[$status];
			if ($comment->save())
			{
				Session::set_flash('success', 'Status '.$status.' was assigned to the comment '.
						$comment->title.' (#'.$id.')');
				Response::redirect('notes/view/'.$comment->note_id);
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}
		}

		$form = Model_Comment_Validation::edit($comment);
        if ($form->validation()->run())
        {
			$comment->title = $form->validated('title');
			$comment->description = $form->validated('description');
			$comment->status = $form->validated('status');

            if ($comment->save())
			{
				Session::set_flash('success', 'Comment successfully updated with status '.
						Model_Comment::status_name($comment->status));
				Response::redirect('notes/view/'.$comment->note_id);
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}

			Response::redirect('comments/edit/'.$comment->id.'/'.$comment->note_id);
		}

		$this->title = 'Edit Comment - '.$comment->title;
		$this->data['comment'] = $comment;
		$this->data['form'] = $form;
	}

	public function action_delete($id)
	{
		if (empty($id) || !$comment = Model_Comment::find($id))
		{
			Request::show_404();
		}

		if ($comment && $comment->delete())
		{
			Session::set_flash('notice', 'Deleted comment #'.$id);
		}
		else
		{
			Session::set_flash('error', 'Could not delete comment #'.$id);
		}

		Response::redirect('notes/view/'.$comment->note_id);
	}

}