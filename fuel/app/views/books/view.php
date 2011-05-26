<h2 class = "model_title"><?php echo $title ?></h2>

<div class = "model_informations">
	<ul class="dates">
		<li><?php echo 'Created at : '.Date::factory($book->created_at); ?></li>
		<li><?php echo 'Updated at : '.Date::factory($book->updated_at); ?></li>
	</ul><ul class="stats">
		<li>by <strong><?php echo $book->creator->username; ?></strong></li>
		<li><?php echo 'Status : <em>'.Model_Note::status_name($book->status).'</em>'; ?></li>
	</ul>
</div>

<div class="model_body">
<h3>Book description :</h3>
<p><?php echo $book->description; ?></p>
</div>

<fieldset>
<legend></legend>
	<?php echo $form; ?>
</fieldset>

