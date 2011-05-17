<h2>Listing notes</h2>

<table>
	<tr>
		<th>Creator id</th>
		<th>Title</th>
		<th>Body</th>
		<?php 
			if (in_array('edit', $user_rights) || in_array('delete', $user_rights) || in_array('publish', $user_rights)) 
			{
				echo '<th>Options</th>';
			}
		?>
	</tr>
	
	<div class="filters">
    <strong>Show:</strong>
	<?php echo Html::anchor('notes/index/all', 'All'); ?>
	&middot;
	<?php echo Html::anchor('notes/index/draft', 'Draft'); ?>
	&middot;
	<?php echo Html::anchor('notes/index/published', 'Published'); ?>
    &middot;
	<?php echo Html::anchor('notes/index/archive', 'Archive'); ?>
	</div>

	<?php foreach ($notes as $note): ?>	<tr>
		<td><?php echo $note->creator->username; ?></td>
		<td><?php echo Html::anchor('notes/view/'.$note->id, $note->title); ?></td>
		<td><?php echo Str::truncate($note->body, 30); ?></td>
		
		<?php
			if (in_array('edit', $user_rights) || in_array('delete', $user_rights))
			{
				echo '<td>';
				if (in_array('edit', $user_rights)) 
				{
					echo Html::anchor('notes/edit/publish/'.$note->id, 'Publish').'  ';
					echo Html::anchor('notes/edit/'.$note->id, 'Edit').'  ';
				}
				if (in_array('delete', $user_rights))
				{
					echo Html::anchor('notes/delete/'.$note->id, 'Delete', 
							array('onclick' => "return confirm('Are you sure?')"));
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
		echo Html::anchor('notes/add', 'Add a new Note');
	}
?>