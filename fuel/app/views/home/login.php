<h2>Login</h2>
<p>Login to your account using your username and password.</p>
<p> //todo modify this view : app/views/home/login.php</p>

<?php //echo isset($errors) ? $errors : false; ?>
<?php echo $form->validation()->show_errors(); ?>

<fieldset>
<legend>Login</legend>
<?php echo $form->build('home/login');?>
</fieldset>