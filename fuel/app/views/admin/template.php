<?php Lang::load('template'); ?>

<!DOCTYPE html>
<html>
    <head>
		<meta charset="utf-8">
        <title>HyperLivre<?php echo isset($title) ? ' - '.$title : null; ?></title>
		
		<?php echo Asset::css(array('screen.css')); ?>
		<?php echo Asset::js(array('jquery-1.6.1.min.js')); ?>
		<script type="text/javascript"> 
      		$(document).ready( function() {
        		$('.message').delay(3000).fadeOut();
      		});
    	</script>
    </head>
	
    <body <?php echo isset($page_id) ? 'id="'.$page_id.'"' : ''; ?>>
        <div class="header">
            <!-- Menu -->
            <ul class="menu">
				<li><?php echo Html::anchor('admin/dashboard', __('dashboard')); ?></li>
				<li><?php echo Html::anchor('admin/users', __('users')); ?></li>
                <li><?php echo Html::anchor('books', __('books')); ?></li>
                <li><?php echo Html::anchor('concepts', __('concepts')); ?></li>
                <li><?php echo Html::anchor('notes', __('notes')); ?></li>
				<li><?php echo Html::anchor('home/logout', __('logout')); ?></li>
            </ul>

            <div class="logo">
				<?php echo Html::anchor('admin/dashboard',Asset::img(array('admin/admin-logo.gif'))); ?>
            </div>

			<?php if (Auth::check()): ?>
            <div id="active_book">
            	<ul>
            		<li><?php echo '<strong>Active book</strong>'; ?></li>
            		<li>
            	<?php
            		$user_id = Auth::instance()->get_user_id();
            		$user = Model_User::find($user_id[1], array('related' => array('active_book')));
            		if ($user->active_book !== null)
            			echo Html::anchor('books/view/'.$user->active_book->id, $user->active_book->title);
            		else
            			echo Html::anchor('books/index/','<em>No active book selected.</em>');
            	?></li>
            	</ul>
            </div>
            <?php endif; ?>

			<!-- Messages -->
			<?php //if (Session::get_flash()): ?>
				<!--<div class="messages">-->
					<?php if (Session::get_flash('success')): ?>
						<div class="message success"><?php echo Session::get_flash('success'); ?></div>
					<?php elseif (Session::get_flash('notice')): ?>
						<div class="message notice"><?php echo Session::get_flash('notice'); ?></div>
					<?php elseif (Session::get_flash('user')): ?>
						<div class="message user"><?php echo Session::get_flash('user'); ?></div>
					<?php elseif (Session::get_flash('add')): ?>
						<div class="message add"><?php echo Session::get_flash('add'); ?></div>
					<?php elseif (Session::get_flash('warning')): ?>
						<div class="message warning"><?php echo Session::get_flash('warning'); ?></div>
					<?php elseif (Session::get_flash('error')): ?>
						<div class="message error"><?php echo Session::get_flash('error'); ?></div>
					<?php endif; ?>
<!--				</div>-->
			<?php //endif; ?>
        </div>
        

        <div class="content">
        
            <div id="main">
                <!-- Main content -->
				<?php echo $content; ?>
				<!-- test CKEditor -->
				<?php
					$editor = new CKEditor();
					$editor->basePath = Uri::create('../fuel/packages/ckeditor/vendor/');
					$editor->replaceAll();
				?> <!-- test CKEditor -->
            </div>
            
        </div>

        <div class="footer">
            <div class="copyright">
				<?php echo __('possible'); ?><a href="http://fuelphp.com/">FuelPHP</a>
                <br/>
				<?php echo __('author'); ?>
            </div>
            <div class="stats">
            	<?php echo __('render'); ?>{exec_time}s &middot; <?php echo __('memory'); ?>{mem_usage}MB
            	<br/>
            	<?php echo Html::anchor(Uri::string().'/lang/fr/', __('french')).' '.Html::anchor(Uri::string().'/lang/en/', __('english')); ?>
            </div>
        </div>
    </body>
</html>