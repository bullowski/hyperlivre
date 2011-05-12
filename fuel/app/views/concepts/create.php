<h2>Create Concept</h2>
<p>Please fill the form below with the Concept information.</p>
<p> //todo add js validation</p>

<?php echo $form->validation()->show_errors(); ?>
<fieldset>
<legend>Concepts details</legend>
<?php echo $form->build('concepts/create');?>
</fieldset>