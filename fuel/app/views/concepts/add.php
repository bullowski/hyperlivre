<h2>Create Concept</h2>
<p>Please fill the form below with the Concept information.</p>

<?php echo $form->validation()->show_errors(); ?>
<fieldset>
<legend>Concepts details</legend>
<?php echo $form->build('concepts/add');?>
</fieldset>