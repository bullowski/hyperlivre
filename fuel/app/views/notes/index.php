<h2>Listing notes</h2>

<?php if (in_array('add', $user_rights)) : ?>
<div class="options">
	<div class="option">
		<?php echo Html::anchor('notes/add', 'Add a new Note'); ?>
	</div>
	<div class="option">
		<?php
			echo Html::anchor('notes/index/my_all', 'My Notes');
			echo ' &middot; ';
			echo Html::anchor('notes/index/all', 'All Notes');
		?>
	</div>
</div>
<?php endif;?>


<div class="filters">
    <strong>Show:</strong>
	<?php echo Html::anchor('notes/index/'.$my.'all', 'All'); ?>
	&middot;
	<?php
		if ($my !== '')
		{
			echo Html::anchor('notes/index/'.$my.'draft', 'Draft');
			echo ' &middot; ';
		}
	?>
	<?php echo Html::anchor('notes/index/'.$my.'published', 'Published'); ?>
    &middot;
	<?php echo Html::anchor('notes/index/'.$my.'archive', 'Archive'); ?>
</div>

<?php if (count($notes) > 0): ?>
<table>
<thead>
	<tr>
		<th>Creator</th>
		<th>Title</th>
		<th>Body</th>
		<th>Status</th>
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
	<?php $user_id = Auth::instance()->get_user_id(); ?>
	<?php foreach ($notes as $note): ?>
	<tr>
		<td><?php echo $note->creator->username; ?></td>
		<td><?php echo Html::anchor('notes/view/'.$note->id, $note->title); ?></td>
		<td><?php echo Str::truncate($note->body, 30); ?></td>
		<td><?php echo Model_Note::status_name($note->status); ?></td>
		<td><?php echo Date::factory($note->created_at); ?></td>
        <td><?php echo Date::factory($note->updated_at); ?></td>
		<?php
			if (in_array('edit', $user_rights) || in_array('delete', $user_rights))
			{
				echo '<td>';
				if (in_array('edit', $user_rights) && ($note->creator_id == $user_id[1]))
				{
					if ( Model_Note::status_name($note->status) === 'draft')
					{
						echo Html::anchor('notes/edit/'.$note->id.'/publish/', 'Publish').'  ';
					}
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
	<?php endforeach; ?>
</tbody>
</table>

<div class="pagination"><?php echo Pagination::create_links(); ?></div>

<?php else: ?>
<div class="message" id="notice">
	<span>There are no notes.</span>
</div>
<div class="clear"></div>
<?php endif; ?>
