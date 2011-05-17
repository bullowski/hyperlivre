<h2><?php echo $title ?></h2>

<p>
	<?php echo 'Status : '.Model_Book::status_name($book->status); ?></br>
	<?php echo 'Created at : '.Date::factory($book->created_at); ?></br>
	<?php echo 'Updated at : '.Date::factory($book->updated_at); ?>
</p>
<h3>Book description :</h3>
<p><?php echo $book->description; ?></p>

<?php 
	echo Form::open('user/dashboard/active_book/'.$book->id);
	echo Form::submit('active_book', 'Select this book');
	echo Form::close();
?>


