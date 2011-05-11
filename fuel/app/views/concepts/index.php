<h2>Listing concepts</h2>

<table>
	<tr>
		<th>User id</th>
		<th>Name</th>
		<th>Description</th>
		<th></th>
		<th></th>
		<th></th>
	</tr>

	<?php foreach ($concepts as $concepts): ?>	<tr>

		<td><?php echo $concepts->user_id; ?></td>
		<td><?php echo $concepts->name; ?></td>
		<td><?php echo $concepts->description; ?></td>
		<td><?php echo Html::anchor('concepts/view/'.$concepts->id, 'View'); ?></td>
		<td><?php echo Html::anchor('concepts/edit/'.$concepts->id, 'Edit'); ?></td>
		<td><?php echo Html::anchor('concepts/delete/'.$concepts->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?></td>
	</tr>
	<?php endforeach; ?></table>

<br />

<?php echo Html::anchor('concepts/create', 'Add new Concepts'); ?>