<h2><?php echo $title ?></h2>

<h4>by <?php echo $note->creator->username; ?></h4>
<p>
	<?php echo 'Status : '.Model_Note::status_name($note->status); ?></br>
	<span class="timestamps">
	<?php echo 'Created at : '.Date::factory($note->created_at); ?></br>
	<?php echo 'Updated at : '.Date::factory($note->updated_at); ?>
	</span>
</p>

<p><?php echo $note->body; ?></p>

<?php if (in_array('add_comments', $user_rights)) : ?>
	<div class="options">
	<div class="option">
		<?php echo Html::anchor('comments/add/'.$note->id, 'Add a new Comment'); ?>
	</div>
	</div>
<?php endif; ?>

<?php if (in_array('view_comments', $user_rights) && count($comments) > 0): ?>
<div class="comments">
	<h4>Comments</h4>
	<?php
		$comment_count = 0;
		foreach ($comments as $comment): ?>

			<div class="comment">
				<h4 class="comment_title">
					<?php
						$comment_count++;
						echo $comment_count.'. |   '.$comment->title.
								' by <span class="author">'.$comment->user->username.'</span>';
						if(Model_Comment::status_name($comment->status) !== 'published')
						{
							echo '<em class="status"> ('.Model_Comment::status_name($comment->status).')</em>';
						}
					?>
				</h4>
				<span class="timestamps">
				<?php echo 'Created at : '.Date::factory($comment->created_at); ?></br>
				<?php echo 'Updated at : '.Date::factory($comment->updated_at); ?>
				</span>

				<p><?php echo $comment->comment; ?></p>

				<?php
					if (in_array('edit_comments', $user_rights) || in_array('delete_comments', $user_rights))
					{
						echo '<div class="options">';
						if (in_array('edit_comments', $user_rights))
						{
							if ($comment->user_id == $user_id)
							{
								if ( Model_Note::status_name($comment->status) === 'hidden')
								{
									echo Html::anchor('comments/edit/'.$comment->id.'/published/', 'Publish').'  ';
								}
								echo Html::anchor('comments/edit/'.$comment->id, 'Edit').'  ';
							}
							elseif (in_array('super_edit_comments', $user_rights))
							{
								echo Html::anchor('comments/edit/'.$comment->id, 'Edit').'  ';
							}
						}
						if (in_array('delete_comments', $user_rights))
						{
							if ($comment->user_id == $user_id
								|| (in_array('super_delete_comments', $user_rights)))
							{
								echo Html::anchor('comments/delete/'.$comment->id, 'Delete',
									array('onclick' => "return confirm('Are you sure?')"));
							}
						}
						echo '</div>';
					}
				?>
			</div>	<!-- end of comment -->

	<?php endforeach; ?>
</div>	  <!-- end of comments -->

<?php endif; ?>

