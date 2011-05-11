<?php

/**
 * The Home Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Home extends Controller_Template {

	/**
	 * The index action.
	 *
	 * @access  public
	 * @return  void
	 */
	public function action_index()
	{
		$this->title = 'Welcome';
	}


	//TODO move this to an other controller??
	public function action_signup()
	{
        if (Auth::check())
        {
            Response::redirect('/');
        }

		$form = Model_User_Validation::signup();
        if ($form->validation()->run())
        {
            if (Auth::instance()
					->create_user(	$form->validated('username'),
									$form->validated('password'),
									$form->validated('email'),
									Auth::group()->get_group('Users')))
            {
                Session::set_flash('success', 'Thanks for registering!');
                Response::redirect('/');
            }
            else
            {
                throw new Exception('An unexpected error occurred. Please try again.');
            }
        }

		$this->title = 'Sign up';
		$this->data['form'] = $form;
	}

    public function action_login()
	{
		if (Auth::check())
        {
            Response::redirect('/');
        }

		$form = Model_User_Validation::login();
        if ($form->validation()->run())
        {
            if (Auth::instance()
					->login(	$form->validated('username'),
								$form->validated('password')))
            {
				Session::set_flash('user', 'Welcome back, '. $form->validated('username').' !');
                Response::redirect('admin/dashboard');
            }
			else
			{
				Session::set_flash('error', 'Incorrect username or password.');
				Response::redirect('home/login');
			}
        }

		$this->title = 'Login';
		$this->data['form'] = $form;
	}

	public function action_logout()
	{
		Auth::instance()->logout();

        Response::redirect('home');
	}
}

/* End of file home.php */
