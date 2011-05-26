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

<?php if ($hidden_books_access) : ?>
<?php if (count($books) > 0) : ?>
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
        <td><?php echo Html::anchor('books/view/'.$book->id, $book->title); ?></td>
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
<?php endif; ?>

<?php if (!$hidden_books_access): ?>
<?php if (count($books) > 0): ?>

	<!-- Books slider -->
	<div id="slider">

	  <ul class="navigation">
		  <?php foreach ($books as $book): ?>
			<li>
				<?php echo Html::anchor(Uri::current().'#book'.$book->id, Str::truncate($book->title, 16)); ?>
			</li>
		  <?php endforeach; ?>
	  </ul>


	  <?php
			if (count($books) > 1)
			{
				echo Asset::img(array('slider/scroll_left.png'),
						array ('class' => 'scroll_buttons left hide'));
			}
	  ?>

	  <!-- element with overflow applied -->
	  <div class="scroll">
		<!-- element that will be scrolled during the effect -->
		<div class="scroll_container">
		  <!-- individual book panels -->
		  <?php foreach ($books as $book): ?>
			<div class="panel" <?php echo 'id="book'.$book->id.'"'; ?>>
				<h3 class="title"><?php echo $book->title; ?></h3>
				<div class="book_info">
					by <em class="author">
							<?php echo ($book->creator !== null) ? $book->creator->username : 'anonymous'; ?>
					</em><br/>
					<?php echo 'Status : '.Model_Book::status_name($book->status); ?><br/>
					<?php echo 'Created at : '.Date::factory($book->created_at); ?><br/>
					<?php echo 'Updated at : '.Date::factory($book->updated_at); ?>
				</div>
<!--				<div class="clear"></div>-->
				<p class="description">
					<?php echo Str::truncate($book->description, 320); ?>
					<?php echo Html::anchor('books/view/'.$book->id, '[More...]', array('class' => 'more')); ?>
				</p>
				<div class="book_options">
					<?php echo $forms[$book->id]; ?>
				</div>
			</div>
		  <?php endforeach; ?>
		</div>
	  </div>

	    <?php
			if (count($books) > 1)
			{
				echo Asset::img(array('slider/scroll_right.png'),
						array ('class' => 'scroll_buttons right hide'));
			}
		?>

	  <div id="shade"></div>

	</div> <!-- end of slider -->
<?php else: ?>
	<div>
		<span>No books to show.</span>
	</div>
<?php endif; ?>
<?php endif; ?>