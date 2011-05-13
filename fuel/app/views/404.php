<?php Lang::load('404'); ?>

<h2>404 - <?php $messages = Lang::line('messages'); echo $messages[array_rand($messages)]; ?></h2>
<p><?php echo Lang::line('not_exist'); echo Html::anchor('home', Lang::line('back')); ?></p>


