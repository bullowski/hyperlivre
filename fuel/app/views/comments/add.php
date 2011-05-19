<h2><?php echo $title; ?></h2>
<p>Please enter your comment for the note "<?php echo $note->title; ?>" :</p>

<?php echo $form->validation()->show_errors(); ?>
<fieldset>
<legend>Comment</legend>
<?php echo $form->build('comments/add/'.$note->id);?>
</fieldset>