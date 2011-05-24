<h2>Listing concepts</h2>
<p>Manage concepts.</p>

<div class="options">
	<?php if (in_array('add', $user_rights)) : ?>
	<div class="option"><?php echo Html::anchor('concepts/add', 'Add a new Concept'); ?></div>
	<?php endif; ?>
</div>

<?php if (count($concepts) > 0): ?>
<table class="index">
<thead>
	<tr>
		<th>Creator</th>
		<th>Title</th>
		<th>Description</th>
		<th>Creation date</th>
        <th>Last update</th>
		<?php
			if (in_array('edit', $user_rights) || in_array('delete', $user_rights))
			{
				echo '<th>Options</th>';
			}
		?>
	</tr>
</thead>
<tbody>
	<?php foreach ($concepts as $concept): ?>
	<tr>
		<td><?php echo ($concept->creator !== null) ? $concept->creator->username : 'anonymous'; ?></td>
		<td><?php echo Html::anchor('concepts/view/'.$concept->id, $concept->title); ?></td>
		<td><?php echo Str::truncate($concept->description,30); ?></td>
        <td><?php echo Date::factory($concept->created_at); ?></td>
        <td><?php echo Date::factory($concept->updated_at); ?></td>

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
					echo Html::anchor('concepts/delete/'.$concept->id, 'Delete',
							array('onclick' => "return confirm('Are you sure?')"));
				}
				echo '</td>';
			}
		?>
	</tr>
	<?php endforeach; ?>
</tbody>
</table>

<div class="pagination"><?php echo Pagination::create_links(); ?></div>
<?php else: ?>
<div class="message" id="notice">
	<span>There are no concepts. </span>
</div>
<?php endif; ?>
