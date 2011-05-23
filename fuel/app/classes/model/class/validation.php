<?php

class Model_Class_Validation
{
	protected static $_common_rules = array(
		'name' => array(
			array('required'),
			array('min_length', 2),
			array('max_length', 45),
		),
	);

	public static function get_common_rules($field)
	{
		return static::$_common_rules[$field];
	}

	public static function add()
	{
		return Fieldset::factory('add_class')
				->add_model('Model_Class_Validation', null, 'set_add_form')
				->repopulate();
	}

	public static function set_add_form(Fieldset $form, $property = null)
	{
		$form->add('name', '* Name <em class="validation-info">(2 to 45 caracters long)</em>',
				array(	'id' => 'name',
						'type' => 'text',
						'value' => !empty($property) ? $property->name : ''),
				array_merge(
						static::get_common_rules('name'),
						array(array('unique', 'name'))));

		$form->add('description', 'Description',
				array(	'id' => 'description',
						'type' => 'textarea',
						'value' => !empty($property) ? $property->description : ''));

		$form->add('submit', null,
				array(	'type' => 'submit',
						'value' => 'Add'));
		$form->add('cancel', null,
				array(	'type' => 'submit',
						'value' => 'Cancel'));

	}

	public static function edit($property)
	{
		return Fieldset::factory('edit_class')
				->add_model('Model_Class_Validation', $property, 'set_edit_form')
				->repopulate();
	}

	//FIXME ckeck unique_update rule
	public static function set_edit_form(Fieldset $form, $property = null)
	{
		$form->add('name', '* Name <em class="validation-info">(2 to 65 caracters long)</em>',
				array(	'id' => 'name',
						'type' => 'text',
						'value' => !empty($property) ? $property->name : ''),
				array_merge(
						static::get_common_rules('name'),
						array(array('unique_update', 'name'))));

		$form->add('description', 'Description',
				array(	'id' => 'description',
						'type' => 'textarea',
						'value' => !empty($property) ? $property->description : ''));

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
			$same = Model_Class::find()->where($field, '=', $value)->get_one();
			//FIXME test this method call
			return ($same->id == $updated_id);
		}
		else
		{
			$count = Model_Class::find()->where($field, '=', $value)->count();
			return ($count == 0);
		}
    }

	//TODO
	public function _validation_unique_update($value, $field, $updated_id = false)
    {
		return true;
	}

}