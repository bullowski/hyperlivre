<h2>Add Book</h2>
<p>Please fill the form below with the book information.</p>
<p> //todo add js validation</p>

<?php echo $form->validation()->show_errors(); ?>
<fieldset>
<legend>Book details</legend>
<?php echo $form->build('admin/books/add');?>
</fieldset>