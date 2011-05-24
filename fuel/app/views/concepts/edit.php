<h2>Edit Concept</h2>
<p>Please fill the form below with the book information.</p>

<?php echo $form->validation()->show_errors(); ?>
<fieldset>
<legend>Concept details</legend>
<?php echo $form->build('concepts/edit/'.$concept->id);?>
</fieldset>