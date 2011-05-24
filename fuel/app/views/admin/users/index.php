<h2>Users List</h2>
<p>Manage users and their rights.</p>

<div class="options">
	<div class="option"><?php echo Html::anchor('admin/users/add', 'Add an User'); ?></div>
</div>

<div class="filters">
    <strong>Show:</strong>
	<?php echo Html::anchor('admin/users/index/all', 'All'); ?>
	&middot;
	<?php echo Html::anchor('admin/users/index/Users', 'Users'); ?>
	&middot;
	<?php echo Html::anchor('admin/users/index/Moderators', 'Moderators'); ?>
	&middot;
	<?php echo Html::anchor('admin/users/index/Administrators', 'Administrators'); ?>
</div>

<?php if ($total_users > 0): ?>
<table class="index">
<thead>
	<tr>
		<th>Id</th>
		<th>Username</th>
		<th>Email</th>
		<th>Group</th>
		<th>Options</th>
	</tr>
</thead>
<tbody>
	<?php
	foreach ($users as $user): ?>
	<tr>
		<td><?php echo $user->id; ?></td>
        <td><?php echo Html::anchor('admin/users/edit/'.$user->id, $user->username); ?></td>
        <td><?php echo $user->email; ?></td>
        <td><?php echo Auth::group()->get_name($user->group); ?></td>
        <td>
        	<?php echo Html::anchor('admin/users/delete/'.$user->id, 'Delete',
							array('onclick' => "return confirm('Are you sure?')")); ?>
		</td>
	</tr>
	<?php endforeach; ?>
</tbody>
</table>

<div class="pagination"><?php echo Pagination::create_links(); ?></div>

<?php else: ?>
<div class="message" id="notice">
	<?php if (!$filter): ?>
	<span>There are no users at all, which is quite outstanding because you're one! Right?
	<?php echo Html::anchor('admin/users/add', 'Add a new User'); ?>.</span>
	<?php else: ?>
	<span>There are no <?php echo $filter; ?>.
		<?php echo Html::anchor('admin/users/add', 'Add a new User'); ?>.</span>
	<?php endif; ?>
</div>
<div class="clear"></div>
<?php endif; ?>