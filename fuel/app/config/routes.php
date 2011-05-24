<?php
return array(
	'_root_'  => 'home',  // The default route
	'_404_'   => 'home/404',    // The main 404 route
	
	/**
	 * This is an example of a BASIC named route (used in reverse routing).
	 * The translated route MUST come first, and the 'name' element must come
	 * after it.
	 */
	// 'foo/bar' => array('welcome/foo', 'name' => 'foo'),
	'(:any)/lang/(:segment)' =>	'lang/change/$2/$1',
);