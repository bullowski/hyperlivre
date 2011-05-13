<?php Lang::load('404'); ?>

<h2>404 - <?php $messages = __('messages'); echo $messages[array_rand($messages)]; ?></h2>
<p><?php echo __('not_exist'); echo Html::anchor('home', __('back')); ?></p>


