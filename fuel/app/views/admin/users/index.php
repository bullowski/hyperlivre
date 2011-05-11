<h2>Users List</h2>
<p>Manage users and their rights.</p>
<p>//todo modify view app/views/admin/users.php</p>

<div class="options">
	<div class="option"><?php echo Html::anchor('admin/users/add', 'Add an User'); ?></div>
</div>

<div class="filters">
    <strong>Show:</strong>
	<?php echo Html::anchor('admin/users/index/all', 'All'); ?>
	&middot;
	<?php echo Html::anchor('admin/users/index/Users', 'Users'); ?>
	&middot;
	<?php echo Html::anchor('admin/users/index/Administrators', 'Administrators'); ?>
	&middot;
	<?php echo Html::anchor('admin/users/index/Moderators', 'Moderators'); ?>
</div>

<?php if ($total_users > 0): ?>
<table>
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
        <td><?php echo $user->group; ?></td>
        <td width="11%">
     		<?php echo Html::anchor('admin/users/delete/'.$user->id, 'delete'); ?>
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