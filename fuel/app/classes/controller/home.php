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

	public $template = 'home/template';
	protected $method = null;

	/**
	 * The index action.
	 *
	 * @access  public
	 * @return  void
	 */
	public function action_index()
	{
		if (Input::post('subscribe'))
		{
			Response::redirect('home/signup');
		}
		
		//change the redirection if the user is an admin
		if (Auth::check()) {
			$user_groups = Auth::instance()->get_groups();
			$this->user_group = $user_groups[0];
			if (Auth::acl()->has_access(
				array('admin', array('view', 'add', 'edit', 'delete')),
				$this->user_group))
			{
				Response::redirect('admin/dashboard');
			}
			else
			{
				Response::redirect('user/dashboard');
			}
		}

		$form = Model_User_Validation::login();

		if ($form->validation()->run())
		{
			if (Auth::instance()
				->login($form->validated('username'), $form->validated('password')))
			{
				Session::set_flash('user', 'Welcome back, '. $form->validated('username').' !');
			}
			else
			{
				Session::set_flash('error', 'Incorrect username or password.');
				
			}
			Response::redirect('home/index');
		}

		$this->title = 'Home';
		$this->content = 'home/index';
		$this->data['username'] = $form->validated('username');
		$this->data['form'] = $form;
		$this->data['method'] = 'index';
	}


	//TODO move this to an other controller??
	public function action_signup()
	{
		if (Input::post('cancel'))
		{
			Session::set_flash('warning', 'Subscription canceled.');
			Response::redirect('home');
		}

		Config::load('auth', true);
		if (!Config::get('auth.signup', false))
		{
			Response::redirect('home/404');
		}
		if (Auth::check())
		{
			Response::redirect('home/index');
		}

		$form = Model_User_Validation::signup();
		if ($form->validation()->run())
		{
			if ($user_id = Auth::instance()
			->create_user(	$form->validated('username'),
			$form->validated('password'),
			$form->validated('email'),
			Auth::group()->get_group('Users')))
			{
				$user = Model_User::find($user_id);
				$books = $form->validated('book');
				if (is_string($books))
				{
					$books = array($books);
				}
				foreach ($books as $book_id)
				{
					$user->books[] = Model_Book::find($book_id);
				}

				if ($user->save())
				{
					Session::set_flash('success', 'Thanks for registering!');
					Response::redirect('home/index');
				}
				else
				{
					Session::set_flash('error', 'An error has occured. Try again later.');
					Response::redirect('home');
				}
			}
			else
			{
				Session::set_flash('error', 'An error has occured. Try again later.');
				Response::redirect('home');
			}
		}

		$this->title = 'Sign up';
		$this->data['form'] = $form;
	}

	//    public function action_login()
	//	{
	//		if (Auth::check())
	//        {
	//            Response::redirect('admin/dashboard');
	//        }
	//
	//		$form = Model_User_Validation::login();
	//        if ($form->validation()->run())
	//        {
	//            if (Auth::instance()
	//					->login(	$form->validated('username'),
	//								$form->validated('password')))
	//            {
	//				Session::set_flash('user', 'Welcome back, '. $form->validated('username').' !');
	//                Response::redirect('admin/dashboard');
	//            }
	//			else
	//			{
	//				Session::set_flash('error', 'Incorrect username or password.');
	//				Response::redirect('home/login');
	//			}
	//        }
	//
	//		$this->title = 'Login';
	//		$this->data['form'] = $form;
	//	}

	public function action_logout()
	{
		Auth::instance()->logout();

		Response::redirect('home');
	}
}

/* End of file home.php */
