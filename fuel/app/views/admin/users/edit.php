<h2>Edit User</h2>
<p>Please fill the form below with the user account information.</p>

<?php //$select_groups = array(null => 'Select...', 1 => 'User', 100 => 'Admin'); ?>
<?php echo $form->validation()->show_errors(); ?>
<fieldset>
<legend>User details</legend>
<?php echo $form->build('admin/users/edit/'.$user->id);?>
</fieldset>