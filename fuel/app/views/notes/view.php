<h2><?php echo $title ?></h2>

<h4>by <?php echo $note->creator->username; ?></h4>
<p>
	<?php echo 'Status : '.Model_Note::status_name($note->status); ?></br>
	<?php echo 'Created at : '.Date::factory($note->created_at); ?></br>
	<?php echo 'Updated at : '.Date::factory($note->updated_at); ?>
</p>


<p><?php echo $note->body; ?></p>


