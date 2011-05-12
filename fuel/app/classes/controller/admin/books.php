<?php

class Controller_Admin_Books extends Controller_Admin
{

	public function before()
	{
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
		$form = Model_Book_Validation::add();
		if ($form->validation()->run())
		{
			$book = new Model_Book(array(
						'title' => $form->validated('title'),
						'description' => $form->validated('description'),
						'published' => $form->validated('published'),
						'creator_id' => $this->user_id,
				));

			if ($book->save())
			{
				Session::set_flash('success', 'Book successfully added with status '.
						Model_Book::$status[$book->published]);
				Response::redirect('admin/books');
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}

			Response::redirect('admin/books/add');
		}

		$this->title = 'Add Book';
		$this->data['form'] = $form;
	}

	public function action_edit($id)
	{
		if (empty($id) || !$book= Model_Book::find($id))
		{
			Response::redirect('admin/books');
		}

		$form = Model_Book_Validation::edit($book);
        if ($form->validation()->run())
        {
			$book->title = $form->validated('title');
			$book->description = $form->validated('description');
			$book->published = $form->validated('published');

            if ($book->save())
			{
				Session::set_flash('success', 'Book successfully added with status '.
						Model_Book::$status[$book->published]);
				Response::redirect('admin/books');
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}

			Response::redirect('admin/books/edit/'.$user->id);
		}

		$this->title = 'Edit Book - '.$book->title;
		$this->data['book'] = $book;
		$this->data['form'] = $form;
	}

	public function action_delete($id = null)
	{
		//TODO
	}

}