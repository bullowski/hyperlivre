<?php

class Model_User_Validation
{
	protected static $_common_rules = array(
		'username' => array(
			array('required'),
			array('min_length', 3),
			array('max_length', 20),
			array('valid_string',
				array('alpha', 'numeric', 'dashes', 'dots')),
		),
		'password' => array(
			array('required'),
			array('min_length', 3),
			array('max_length', 20),
			array('valid_string',
				array('alpha', 'numeric', 'punctuation', 'dashes'))
		),
		'email' => array(
			array('required'),
			array('min_length', 3),
			array('max_length', 80),
			array('trim'),
			array('valid_email'),
		),
		'group' => array(
			array('required'),
			array('trim'),
			array('valid_group'),
		),
		'book' => array(
			array('required'),
		)
	);

	public static function get_common_rules($field)
	{
		return static::$_common_rules[$field];
	}

	public static function login()
	{
		return Fieldset::factory('login_user')
				->add_model('Model_User_Validation', null, 'set_login_form')
				->repopulate();
	}

	public static function set_login_form(Fieldset $form, $user = null)
	{
		$form->add('username', 'Username',
				array(	'id' => 'username',
						'type' => 'text',
						'value' => !empty($user) ? $user->username : ''),
				static::get_common_rules('username'));

		$form->add('password', 'Password',
				array(	'id' => 'password',
						'type' => 'password',
						'value' => !empty($user) ? $user->password : ''),
				static::get_common_rules('password'));

		$form->add('submit', null,
				array(	'type' => 'submit',
						'value' => 'Login'));
	}

	public static function add()
	{
		return Fieldset::factory('add_user')
				->add_model('Model_User_Validation', null, 'set_add_form')
				->repopulate();
	}

	public static function set_add_form(Fieldset $form, $user = null)
	{
		$form->add('username', '* Username <em class="validation-info">(3 to 20 caracters long)</em>',
				array(	'id' => 'username',
						'type' => 'text',
						'value' => !empty($user) ? $user->username : ''),
				array_merge(
						static::get_common_rules('username'),
						array(array('unique', 'username'))));


		$form->add('password', '* Password <em class="validation-info">(3 to 20 caracters long)</em>',
				array(	'id' => 'password',
						'type' => 'password',
						'value' => !empty($user) ? $user->password : ''),
				static::get_common_rules('password'));

		$form->add('email', '* Email Address <em class="validation-info">(valid)</em>',
				array(	'id' => 'email',
						'type' => 'text',
						'value' => !empty($user) ? $user->email : ''),
				array_merge(
						static::get_common_rules('email'),
						array(array('unique', 'email'))));

		$form->add('group', 'Group',
				array(	'type' => 'select',
						'options' => Auth::group()->get_group_names(),
						'value' => !empty($user) ? $user->group : null),
				static::get_common_rules('group'));

		$form->add('submit', null,
				array(	'type' => 'submit',
						'value' => 'Add'));

	}

	public static function signup()
	{
		return Fieldset::factory('signup_user')
				->add_model('Model_User_Validation', null, 'set_signup_form')
				->repopulate();
	}

	public static function set_signup_form(Fieldset $form, $user = null)
	{
		$form->add('username', '* Username <em class="validation-info">(3 to 20 caracters long)</em>',
				array(	'id' => 'username',
						'type' => 'text',
						'value' => !empty($user) ? $user->username : ''),
				array_merge(
						static::get_common_rules('username'),
						array(array('unique', 'username'))));

		$form->add('password', '* Password <em class="validation-info">(3 to 20 caracters long)</em>',
				array(	'id' => 'password',
						'type' => 'password',
						'value' => !empty($user) ? $user->password : ''),
				static::get_common_rules('password'));

		$form->add('email', '* Email Address <em class="validation-info">(valid)</em>',
				array(	'id' => 'email',
						'type' => 'text',
						'value' => !empty($user) ? $user->email : ''),
				array_merge(
						static::get_common_rules('email'),
						array(array('unique', 'email'))));

		$open_books = Model_Book::get_filtered_books('open');
		$books_select = array();
		foreach ($open_books as $book)
		{
			$books_select[$book->id] = $book->title;
		}
		$form->add('book', 'Open Book(s)',
				array(	'type' => 'checkboxes',
						'name' => $books_select,
						'options' => $books_select),
				array_merge(
					static::get_common_rules('book'),
					array(array('valid_book', $books_select))));


		$form->add('submit', null,
				array(	'type' => 'submit',
						'value' => 'Sign Up'));

	}

	public static function edit($user)
	{
		return Fieldset::factory('edit_user')
				->add_model('Model_User_Validation', $user, 'set_edit_form')
				->repopulate();
	}

	//FIXME add unique rule
	public static function set_edit_form(Fieldset $form, $user = null)
	{
		$form->add('username', 'Username',
				array(	'id' => 'username',
						'type' => 'text',
						'value' => !empty($user) ? $user->username : ''),
				static::get_common_rules('username'));

		$form->add('email', 'Email Address',
				array(	'type' => 'text',
						'value' => !empty($user) ? $user->email : ''),
				static::get_common_rules('email'));

		$form->add('group', 'Group',
				array(	'type' => 'select',
						'options' => Auth::group()->get_group_names(),
						'value' => !empty($user) ? $user->group : null),
				static::get_common_rules('group'));
		
		$accessible_books = Model_Book::get_filtered_books('all','archive');
		$books_select = array();
		foreach ($accessible_books as $book)
		{
			$books_select[$book->id] = $book->title;
		}
		$form->add('book', 'Accessible Book(s)', 
				array(	'type' => 'checkboxes',
						'name' => $books_select,
						'value' => array_keys($user->books),
						'options' => $books_select),
				array(array('valid_book', $books_select)));

		$form->add('submit', null,
				array(	'type' => 'submit',
						'value' => 'Edit'));

	}

	public function _validation_unique($value, $field, $updated_id = false)
    {
		if($updated_id)
		{
			$same = Model_User::find()->where($field, '=', $value)->get_one();
			//FIXME test this method call
			return ($same->id == $updated_id);
		}
		else
		{
			$count = Model_User::find()->where($field, '=', $value)->count();
			return ($count == 0);
		}
    }

	//FIXME check group names
	public function _validation_valid_group($value)
    {
		 return (Auth::group()->get_name($value) !== null);
	}

	//FIXME check book published status
	public function _validation_valid_book($values, Array $select_book)
    {
		if (is_string($values))
		{
			$values = array($values);
		}
    	foreach ($values as $value)
    	{
    		if ($select_book[$value] === null)
    		{
    			return false;
    		}
    	}

    	return true;
	}
}