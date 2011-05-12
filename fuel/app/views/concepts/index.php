<h2>Listing concepts</h2>

<table>
	<tr>
		<th>Creator id</th>
		<th>Title</th>
		<th>Description</th>
		<th></th>
		<th></th>
		<th></th>
	</tr>

	<?php foreach ($concepts as $concept): ?>	<tr>

		<td><?php echo $concept->creator->username; ?></td>
		<td><?php echo $concept->title; ?></td>
		<td><?php echo Str::truncate($concept->description,30); ?></td>
		<td><?php echo Html::anchor('concepts/view/'.$concept->id, 'View'); ?></td>
		<td><?php echo Html::anchor('concepts/edit/'.$concept->id, 'Edit'); ?></td>
		<td><?php echo Html::anchor('concepts/delete/'.$concept->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?></td>
	</tr>
	<?php endforeach; ?></table>

<br />

<?php echo Html::anchor('concepts/add', 'Add new Concepts'); ?>