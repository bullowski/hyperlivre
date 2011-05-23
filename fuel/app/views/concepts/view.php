<h2><?php echo $title ?></h2>

<p><?php echo $concept->title; ?></p>
<p>
	<?php echo 'Created at : '.Date::factory($concept->created_at); ?></br>
	<?php echo 'Updated at : '.Date::factory($concept->updated_at); ?>
</p>
<h3>Concept description :</h3>

<p><?php echo $concept->description; ?></p>
