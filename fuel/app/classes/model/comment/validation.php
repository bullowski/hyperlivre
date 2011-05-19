<?php

class Model_Comment_Validation
{
	protected static $_common_rules = array(
		'title' => array(
			array('required'),
			array('min_length', 2),
			array('max_length', 128)
		),
		'comment' => array(
			array('required'),
			array('min_length', 3)
		),
		'status' => array(
			array('required'),
			array('valid_status')
		),
		'user' => array(
			array('required'),
		)
	);

	public static function get_common_rules($field)
	{
		return static::$_common_rules[$field];
	}

	public static function add()
	{
		return Fieldset::factory('add_comment')
				->add_model('Model_Comment_Validation', null, 'set_add_form')
				->repopulate();
	}

	public static function set_add_form(Fieldset $form, $concept = null)
	{
		$form->add('title', '* Title <em class="validation-info">(2 to 128 caracters long)</em>',
				array(	'id' => 'title',
						'type' => 'text',
						'value' => !empty($comment) ? $comment->title : ''),
				array_merge(
						static::get_common_rules('title'),
						array(array('unique', 'title'))));

		$form->add('comment', '* Comment',
				array(	'id' => 'comment',
						'type' => 'textarea',
						'value' => !empty($comment) ? $comment->description : ''),
				static::get_common_rules('comment'));

		$form->add('submit', null,
				array(	'type' => 'submit',
						'value' => 'Add'));
		$form->add('cancel', null,
				array(	'type' => 'submit',
						'value' => 'Cancel'));

	}

	public static function edit($comment)
	{
		return Fieldset::factory('edit_comment')
				->add_model('Model_Comment_Validation', $comment, 'set_edit_form')
				->repopulate();
	}

	//FIXME ckeck unique_update rule
	public static function set_edit_form(Fieldset $form, $comment = null)
	{
		$form->add('title', '* Title <em class="validation-info">(2 to 128 caracters long)</em>',
				array(	'id' => 'title',
						'type' => 'text',
						'value' => !empty($comment) ? $comment->title : ''),
				array_merge(
						static::get_common_rules('title'),
						array(array('unique_update', 'title'))));

		$form->add('comment', '* Comment',
				array(	'id' => 'comment',
						'type' => 'textarea',
						'value' => !empty($comment) ? $comment->comment : ''),
				static::get_common_rules('comment'));

		$form->add('status', 'Status',
				array(	'id' => 'status',
						'type' => 'select',
						'options' => Model_Comment::status_names(),
						'value' => !empty($comment) ? $comment->status : null),
				static::get_common_rules('status'));

		$form->add('submit', null,
				array(	'type' => 'submit',
						'value' => 'Edit'));
		$form->add('cancel', null,
				array(	'type' => 'submit',
						'value' => 'Cancel'));

	}

	//TODO test
	public function _validation_unique($value, $field, $updated_id = false)
    {
		if($updated_id)
		{
			$same = Model_Comment::find()->where($field, '=', $value)->get_one();
			//FIXME test this method call
			return ($same->id == $updated_id);
		}
		else
		{
			$count = Model_Comment::find()->where($field, '=', $value)->count();
			return ($count == 0);
		}
    }

	//TODO
	public function _validation_unique_update($value, $field, $updated_id = false)
    {
		return true;
	}

	// Check if the status is in the possible status
	public function _validation_valid_status($value)
    {
		 return (Model_Comment::status_name($value) !== 'all');
	}

}