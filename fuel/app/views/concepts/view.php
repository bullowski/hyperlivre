<h2 class = "model_title"><?php echo $title ?></h2>

<div class = "model_informations">
	<ul class="dates">
		<li><?php echo 'Created at : '.Date::factory($concept->created_at); ?></li>
		<li><?php echo 'Updated at : '.Date::factory($concept->updated_at); ?></li>
	</ul>
</div>


<div class="model_body">
	<h3>Concept description :</h3>	
	<p><?php echo $concept->description; ?></p>
</div>
