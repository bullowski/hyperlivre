<?php

class Model_Book_Validation
{
	protected static $_common_rules = array(
		'title' => array(
			array('required'),
			array('min_length', 2),
			array('max_length', 128)
		),
		'description' => array(
			array('required'),
			array('min_length', 3)
		),
		'published' => array(
			array('required'),
			array('valid_published')
		)
	);

	public static function get_common_rules($field)
	{
		return static::$_common_rules[$field];
	}

	public static function add()
	{
		return Fieldset::factory('add_book')
				->add_model('Model_Book_Validation', null, 'set_add_form')
				->repopulate();
	}

	public static function set_add_form(Fieldset $form, $concept = null)
	{
		$form->add('title', '* Title <em class="validation-info">(2 to 128 caracters long)</em>',
				array(	'id' => 'title',
						'type' => 'text',
						'value' => !empty($book) ? $book->title : ''),
				array_merge(
						static::get_common_rules('title'),
						array(array('unique', 'title'))));

		$form->add('description', '* Description',
				array(	'id' => 'description',
						'type' => 'textarea',
						'value' => !empty($book) ? $book->description : ''),
				static::get_common_rules('description'));

		$form->add('published', 'Status',
				array(	'type' => 'select',
						'options' => Model_Book::$status,
						'value' => !empty($book) ? $book->published : null),
				static::get_common_rules('published'));

		$form->add('submit', null,
				array(	'type' => 'submit',
						'value' => 'Add'));

	}

	public static function edit($book)
	{
		return Fieldset::factory('edit_book')
				->add_model('Model_Book_Validation', $book, 'set_edit_form')
				->repopulate();
	}

	//FIXME ckeck unique_update rule
	public static function set_edit_form(Fieldset $form, $book = null)
	{
		$form->add('title', '* Title <em class="validation-info">(2 to 128 caracters long)</em>',
				array(	'id' => 'title',
						'type' => 'text',
						'value' => !empty($book) ? $book->title : ''),
				array_merge(
						static::get_common_rules('title'),
						array(array('unique_update', 'title'))));

		$form->add('description', '* Description',
				array(	'id' => 'description',
						'type' => 'textarea',
						'value' => !empty($book) ? $book->description : ''),
				static::get_common_rules('description'));

		$form->add('published', 'Status',
				array(	'id' => 'published',
						'type' => 'select',
						'options' => Model_Book::$status,
						'value' => !empty($book) ? $book->published : null),
				static::get_common_rules('published'));

		$form->add('submit', null,
				array(	'type' => 'submit',
						'value' => 'Edit'));

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
	public function _validation_valid_published($value)
    {
		 return (Model_Book::$status[$value] !== null);
	}

}