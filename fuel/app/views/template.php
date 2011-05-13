<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>HyperLivre<?php echo isset($title) ? ' - '.$title : null; ?></title>
		<?php echo Asset::css(array('screen.css')); ?>
    </head>
    <body <?php echo isset($page_id) ? 'id="'.$page_id.'"' : ''; ?>>
        <div class="header">
            <!-- Menu -->
            <ul class="menu">
				<?php if (Auth::check()): ?>
					<li><?php echo Html::anchor('user/notes', 'My Notes'); ?></li>
					<li><?php echo Html::anchor('home/logout', 'Logout'); ?></li>
				<?php else: ?>
					<li><div class="wrap"><?php echo Html::anchor('home', 'Home'); ?></div></li>
					<li><div class="wrap"><?php echo Html::anchor('home/login', 'Login'); ?></div></li>
					<li><div class="wrap"><?php echo Html::anchor('home/signup', 'Sign Up'); ?></div></li>
				<?php endif; ?>
            </ul>

            <div class="logo">
				<?php echo Asset::img(array('logo.gif')); ?>
            </div>

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
            </div>

            <!-- Sidebar -->
            <div id="sidebar">
                <p>lorem ipsum</p>
            </div>

        </div>

        <div class="footer">
            <div class="copyright">
				Made possible with <a href="http://fuelphp.com/">FuelPHP</a>
                <br/>
				Developed by Alex Bulla &amp; Michael Gumowski.
            </div>
            <div class="stats">Page renedered in {exec_time}s &middot; Memory Usage {mem_usage}MB</div>
        </div>
        
        <p><?php print_r('language='.Config::get('language')); ?></p>
		<p><?php echo Html::anchor(Uri::string().'/lang/fr/', 'French').' '.Html::anchor(Uri::string().'/lang/en/', 'English'); ?></p>

    </body>
</html>