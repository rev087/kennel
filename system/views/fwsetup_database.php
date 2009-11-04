<h1>Database</h1>

<?php if (isset($msg)): ?>
	<p class="msg"><?php print $msg; ?></p>
<?php endif; ?>

<table>
	<tr>
		<th>Model</th>
		<th>Source</th>
		<th>Status</th>
	</tr>
	<?php foreach ($models as $model): ?>
	<tr>
		<td><?php print $model['info']['filename'] ?></td>
		<td><?php print $model['source'] ?></td>
		<td><?php print $model['status'] ?></td>
	</tr>
	<?php endforeach ?>
</table>

<p>
	<a href="<?php print url('fwsetup/createmodels'); ?>" class="action">Create Models</a>
</p>
