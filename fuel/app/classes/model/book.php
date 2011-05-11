<?php

class Model_Book extends Orm\Model
{
	protected static $_table_name = 'books';
	protected static $_has_one = array('creator' => array(
											'key_from' => 'id',
											'model_to' => 'Model_User',
											'key_to' => 'user_id'));
	//protected static $_has_one = array('user');
	protected static $_properties = array('id', 'user_id', 'title', 'description', 'published', 'created_at', 'updated_at');
	protected static $_primary_key = array('id');

	public static function count_books($published = null)
	{
		if ($published === null || $published === 'all')
		{
			return count(Model_Book::find('all'));
		}
		else
		{
			return count(Model_Book::find_by_published($published));
		}
	}

	/**
	 * Orm call to get all books from this published status
	 * @return type array books from this published status
	 */
	public function get_books_by_published($published = 'all', $offset = 0, $limit = 10)
	{
		$options = array('offset' => $offset, 'limit' => $limit);

		if ($published !== 'all')
		{
			$options['where'] = array(array('published', '=', $published));
		}

		return Model_Book::find('all', $options);
	}
	
	public static function get_status($filter = 'all')
	{
		$status = array('hidden'=>0,'published_open'=>1,'published_closed'=>2,'archive'=>-1);
		if ($filter !== 'all')
		{
			return $status[$filter];
		}
		
		return 'all';
	}

}

/* End of file book.php */