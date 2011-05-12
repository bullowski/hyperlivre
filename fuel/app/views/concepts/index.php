<h2>Listing concepts</h2>

<table>
	<tr>
		<th>Creator id</th>
		<th>Title</th>
		<th>Description</th>
		<?php 
			if (in_array('edit', $user_rights) || in_array('delete', $user_rights)) 
			{
				echo '<th>Options</th>';
			}
		?>
	</tr>
	
	<?php foreach ($concepts as $concept): ?>	<tr>
		<td><?php echo $concept->creator->username; ?></td>
		<td><?php echo Html::anchor('concepts/view/'.$concept->id, $concept->title); ?></td>
		<td><?php echo Str::truncate($concept->description,30); ?></td>
		
		<?php
			if (in_array('edit', $user_rights) || in_array('delete', $user_rights))
			{
				echo '<td>';
				if (in_array('edit', $user_rights)) 
				{
					echo Html::anchor('concepts/edit/'.$concept->id, 'Edit').' ';
				}
				if (in_array('delete', $user_rights))
				{
					echo Html::anchor('concepts/delete/'.$concept->id, 'Delete', array('onclick' => "return confirm('Are you sure?')"));
				}
				echo '</td>';
			}	
		?>
	</tr>
	<?php endforeach; ?></table>

<br />

<?php 
	if (in_array('add', $user_rights)) 
	{
		echo Html::anchor('concepts/add', 'Add a new Concept');
	}
?>