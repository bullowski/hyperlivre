<h2>Sign Up</h2>
<p>To sign up for a new account, please fill the form below with your account information.</p>
<p> //todo modify this view to use a Filedset: app/views/home/signup.php</p>
<p> //todo add js validation</p>

<?php //$select_groups = array(null => 'Select...', 1 => 'User', 100 => 'Admin'); ?>
<?php //echo isset($errors) ? $errors : false; ?>
<?php echo $form->validation()->show_errors(); ?>

<fieldset>
<legend>Account info</legend> 
<?php echo $form->build('home/signup');?>
<p><em class="validation-note">* required fields</em></p>
</fieldset>
