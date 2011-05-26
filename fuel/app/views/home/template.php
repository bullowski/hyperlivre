<?php Lang::load('template'); ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>HyperLivre<?php echo ' - '.$content->title; ?></title>
		<?php echo Asset::css(array('screen.css')); ?>
    </head>
    <body <?php echo 'id="'.$content->page_id.'"'; ?>>
        <div class="header">
            <!-- Menu -->
            <?php if (isset($content->method) && ($content->method == 'index')):?>
            	<div id="login_home">
					<?php
						echo Form::open('home/index');

						echo '<div id="labels">';

						echo Form::label('Username', 'username');
						echo Form::label('Password', 'password');

						echo '</div><div id="fields">';

						echo Form::input('username',
							isset($content->username)? $content->username: '',
							$attributes = array('id' => 'username')
						);
						echo Form::password('password',
							'',
							$attributes = array('id' => 'password')
						);

						echo '</div>';

						if (count($content->form->validation()->errors()) > 0)
						{
							echo '<span>Invalid login!</span>';
						}
						else
						{
							echo '<br />';
						}

						echo '<div id="commands">';
						echo Form::submit('submit', 'Login', $attributes = array());

						Config::load('auth');
						if (Config::get('auth.signup', false))
						{
							echo Form::submit('subscribe', 'Sign up', $attributes = array());
						}
						echo '</div>';
						echo Form::close();
					?>
				</div>
			<?php endif;?>
			<div class="logo_home">
				<?php echo Asset::img(array('logo2_big.gif')); ?>
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