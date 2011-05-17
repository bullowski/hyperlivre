<h2>Book list</h2>
<p>Manage books and their status.</p>
<p>//todo modify view app/views/admin/books.php</p>

<div class="options">
	<?php if (in_array('add', $user_rights)) : ?>
		<div class="option">
			<?php echo Html::anchor('books/add', 'Create a new Book'); ?>
		</div>
	<?php endif;?>
</div>

<div class="filters">
    <strong>Show:</strong>
	<?php echo Html::anchor('books/index/all', 'All'); ?>
	&middot;
	<?php 
		if (in_array('view_hidden', $user_rights))
		{
			echo Html::anchor('books/index/hidden', 'Hidden');
			echo '&middot;';
		}
	?>
	<?php echo Html::anchor('books/index/open', 'Opened to subscription'); ?>
	&middot;
	<?php echo Html::anchor('books/index/private', 'Private'); ?>
    &middot;
	<?php echo Html::anchor('books/index/archive', 'Archive'); ?>
</div>

<?php if (count($books) > 0): ?>
<table>
<thead>
	<tr>
		<th>Id</th>
		<th>Creator</th>
		<th>Title</th>
		<th>Description</th>
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
	<?php
	foreach ($books as $book): ?>
	<tr>
		<td><?php echo $book->id; ?></td>
        <td><?php echo $book->creator->username; ?></td>
        <td><?php echo Html::anchor('books/view/'.$book->id, $book->title) ?></td>
        <td><?php echo Str::truncate($book->description,30); ?></td>
        <td><?php echo Model_Book::status_name($book->status); ?></td>
        <td><?php echo Date::factory($book->created_at); ?></td>
        <td><?php echo Date::factory($book->updated_at); ?></td>
		<?php
			if (in_array('edit', $user_rights) || in_array('delete', $user_rights))
			{
			echo '<td>';
				if (in_array('edit', $user_rights)) 
				{
					if (Model_Book::status_name($book->status) === 'hidden') {
						echo Html::anchor('books/edit/'.$book->id.'/open/', 'Open').'  ';
					}
					echo Html::anchor('books/edit/'.$book->id, 'Edit').'  ';
				}
				if (in_array('delete', $user_rights))
				{
					echo Html::anchor('books/delete/'.$book->id, 'Delete', 
							array('onclick' => "return confirm('Are you sure?')"));
				}
			}
			echo '</td>';
		?>
		</td>
	</tr>
	<?php endforeach; ?>
</tbody>
</table>

<div class="pagination"><?php echo Pagination::create_links(); ?></div>

<?php else: ?>
<div class="message" id="notice">
	<?php if (!$filter): ?>
	<span>There are no books at all, which is quite outstanding because you should use one! Right?
	<?php echo Html::anchor('books/add', 'Add a new Book'); ?>.</span>
	<?php else: ?>
	<span>There are no <?php echo $filter; ?>.
		<?php echo Html::anchor('books/add', 'Add a new Book'); ?>.</span>
	<?php endif; ?>
</div>
<div class="clear"></div>
<?php endif; ?>