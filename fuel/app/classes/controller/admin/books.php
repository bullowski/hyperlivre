<?php

class Controller_Admin_Books extends Controller_Admin 
{

	public function before() {
		parent::before();
	}

	public function action_index($filter = 'all', $offset = 0)
	{
		$published = ($filter === 'all') ? 'all' : Model_Book::get_status($filter);
		$this->data['total_books'] = Model_Book::count_books($published);

		Pagination::$current_page = $offset;
		Pagination::set_config(array(
			'pagination_url' => 'admin/books/index/'.$published.'/',
			'per_page' => 10,
			'total_items' => $this->data['total_books'],
			'num_links' => 3,
			'uri_segment' => 5
		));

		$this->title = 'Admin Books Index';

		$this->data['filter'] = $filter;
		$this->data['books'] =
				Model_Book::get_books_by_published(
						$published, Pagination::$offset, Pagination::$per_page);
	}
	
	public function action_add()
	{
		// TODO
	}
	
	public function action_edit($id)
	{
		//TODO
	}
	
	public function action_delete($id = null)
	{
		//TODO
	}
}