<h2>Book list</h2>
<p>Manage books and their status.</p>

<?php if (in_array('add', $user_rights)) : ?>
<div class="options">
	<div class="option">
		<?php echo Html::anchor('books/add', 'Create a new Book'); ?>
	</div>
</div>
<?php endif;?>

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
<table class="index">
<thead>
	<tr>
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
        <td><?php echo ($book->creator !== null) ? $book->creator->username : 'anonymous'; ?></td>
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
	</tr>
	<?php endforeach; ?>
</tbody>
</table>

<div class="pagination"><?php echo Pagination::create_links(); ?></div>

<?php else: ?>
	<div class="message" id="notice">
		<span>No books to show.</span>
	</div>
	<div class="clear"></div>
<?php endif; ?>


<!-- Books slider -->
<div id="slider">

  <ul class="navigation">
    <li><a href="#book1">Book1</a></li>
    <li><a href="#book2">Book2</a></li>
    <li><a href="#book3">Books3</a></li>
  </ul>

  <!-- element with overflow applied -->
  <div class="scroll">
    <!-- the element that will be scrolled during the effect -->
    <div class="scroll_container">
      <!-- our individual book panels -->
      <div class="panel" id="book1"> ... </div>
      <div class="panel" id="book2"> ... </div>
      <div class="panel" id="book3"> ... </div>
    </div>
  </div>

</div> <!-- end of slider -->