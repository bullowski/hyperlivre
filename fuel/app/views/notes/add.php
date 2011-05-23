<h2>Create Note</h2>
<p>Please enter your new note in the form below.</p>

<?php echo $form->validation()->show_errors(); ?>
<fieldset>
<legend>Note</legend>
<?php echo $form->build('notes/add');?>
</fieldset>
