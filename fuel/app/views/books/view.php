<h2><?php echo $title ?></h2>

<p>
	<?php echo 'Status : '.Model_Book::status_name($book->status); ?><br/>
	<?php echo 'Created at : '.Date::factory($book->created_at); ?><br/>
	<?php echo 'Updated at : '.Date::factory($book->updated_at); ?>
</p>
<h3>Book description :</h3>
<p><?php echo $book->description; ?></p>

<fieldset>
<legend></legend>
	<?php echo $form; ?>
</fieldset>

