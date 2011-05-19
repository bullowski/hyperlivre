<h2><?php echo $title; ?></h2>
<p>Edit the comment "<?php echo $comment->title; ?>" using the form below.</p>

<?php echo $form->validation()->show_errors(); ?>
<fieldset>
<legend>Comment</legend>
<?php echo $form->build('comments/edit/'.$comment->id);?>
</fieldset>