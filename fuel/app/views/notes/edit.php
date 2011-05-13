<h2>Edit Note</h2>
<p>Please fill the form below with the book information.</p>
<p> //todo add js validation</p>

<?php echo $form->validation()->show_errors(); ?>
<fieldset>
<legend>Concept details</legend>
<?php echo $form->build('notes/edit/'.$concept->id);?>
</fieldset>