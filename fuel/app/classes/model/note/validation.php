<?php

class Model_Note_Validation
{
	protected static $_common_rules = array(
		'title' => array(
			array('required'),
			array('min_length', 2),
			array('max_length', 128)
		),
		'body' => array(
			array('required'),
			array('min_length', 3)
		),
		'status' => array(
			array('required'),
			array('valid_status')
		)
	);

	public static function get_common_rules($field)
	{
		return static::$_common_rules[$field];
	}

	public static function add()
	{
		return Fieldset::factory('add_note')
				->add_model('Model_Note_Validation', null, 'set_add_form')
				->repopulate();
	}

	public static function set_add_form(Fieldset $form, $concept = null)
	{
		$form->add('title', '* Title <em class="validation-info">(2 to 128 caracters long)</em>',
				array(	'id' => 'title',
						'type' => 'text',
						'value' => !empty($note) ? $note->title : ''),
				array_merge(
						static::get_common_rules('title'),
						array(array('unique', 'title'))));

		$form->add('body', '* Body',
				array(	'id' => 'body',
						'type' => 'textarea',
						'value' => !empty($note) ? $note->body : ''),
				static::get_common_rules('body'));

		$form->add('draft', null,
				array(	'type' => 'submit',
						'value' => 'draft'));
		$form->add('publish', null,
				array(	'type' => 'submit',
						'value' => 'publish'));
	}

	public static function edit($note)
	{
		return Fieldset::factory('edit_note')
				->add_model('Model_Note_Validation', $note, 'set_edit_form')
				->repopulate();
	}

	//FIXME ckeck unique_update rule
	public static function set_edit_form(Fieldset $form, $note = null)
	{
		$form->add('title', '* Title <em class="validation-info">(2 to 128 caracters long)</em>',
				array(	'id' => 'title',
						'type' => 'text',
						'value' => !empty($note) ? $note->title : ''),
				array_merge(
						static::get_common_rules('title'),
						array(array('unique_update', 'title'))));

		$form->add('body', '* Body',
				array(	'id' => 'body',
						'type' => 'textarea',
						'value' => !empty($note) ? $note->body : ''),
				static::get_common_rules('body'));

		$form->add('draft', null,
				array(	'type' => 'submit',
						'value' => 'draft'));
		$form->add('publish', null,
				array(	'type' => 'submit',
						'value' => 'publish'));

	}

	//TODO test
	public function _validation_unique($value, $field, $updated_id = false)
    {
		if($updated_id)
		{
			$same = Model_Book::find()->where($field, '=', $value)->get_one();
			//FIXME test this method call
			return ($same->id == $updated_id);
		}
		else
		{
			$count = Model_Book::find()->where($field, '=', $value)->count();
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
		 return (Model_Note::$status_values[$value] !== null);
	}

}