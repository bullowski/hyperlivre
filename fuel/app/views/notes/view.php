<h2 class="model_title"><?php echo $title ?></h2>

<div class = "model_informations">
	<ul class="dates">
		<li><?php echo 'Created at : '.Date::factory($note->created_at); ?></li>
		<li><?php echo 'Updated at : '.Date::factory($note->updated_at); ?></li>
	</ul><ul class="stats">
		<li>by <strong><?php echo $note->creator->username; ?></strong></li>
		<li><?php echo 'Status : <em>'.Model_Note::status_name($note->status).'</em>'; ?></li>
	</ul>
</div>

<div class="model_body"><?php echo $note->body; ?></div>

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
				<?php echo 'Created at : '.Date::factory($comment->created_at); ?><br />
				<?php echo 'Updated at : '.Date::factory($comment->updated_at); ?>
				</span>

				<div class="comment_body"><?php echo $comment->comment; ?></div>

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

