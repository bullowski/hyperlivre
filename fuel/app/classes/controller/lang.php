<?php

class Controller_Lang extends Controller
{	
	public function action_change()
	{
		if (func_num_args() < 2) 
		{
			Response::redirect('home/404');
		}
		$args = func_get_args();
		$language = array_shift($args);		
		$redirection = implode('/', $args);
			
		// change the language
		static::change_language($language);		
		
		Response::redirect($redirection);
	}
	
	protected static function change_language($language = null)
	{	
		if ($language === null) 
		{
			$language = Config::get('language');
		}
		
		$current_language = Cookie::get('language', false);
		
		if ((!$current_language) || ($current_language !== $language))
		{
			Cookie::set('language', $language);
		}
	}
}