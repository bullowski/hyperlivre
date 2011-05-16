<?php

return array(

	/**
	 * DB table name for the user table
	 */
	'table_name' => 'users',

	/**
	 * Groups as id => array(name => <string>, roles => <array>)
	 */
	'groups' => array(
		0	=> array('name' => 'Banned', 'roles' => array('banned')),
		1	=> array('name' => 'Users', 'roles' => array('user')),
		50	=> array('name' => 'Moderators', 'roles' => array('user', 'moderator')),
		100	=> array('name' => 'Administrators', 'roles' => array('user', 'moderator', 'admin')),
	),

	/**
	 * Roles as name => array(location => rights)
	 */
	'roles' => array(
		'#'          => array('website' => array('view')), // default rights
		'banned'     => false,
		'user'       => array(
			'notes'	   => array('view', 'add', 'edit', 'delete'),
			'comments' => array('view'),
			'concepts' => array('view'),
			'books'	   => array('view'),
		),
		'moderator'  => array(
			'comments' => array('add', 'edit', 'delete'),
			'concepts' => array('add', 'edit', 'delete'),
		),
		'admin'      => array(
			'website'  => array('add', 'edit', 'delete'),
			'admin'    => array('view', 'add', 'edit', 'delete'),
			'books'    => array('view_hidden', 'add', 'edit', 'delete'),
		),
		'super'      => true,
	),

	/**
	 * Salt for the login hash
	 */
	'login_hash_salt' => 'put_some_salt_in_here',
);