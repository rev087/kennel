<h1>Database</h1>

<?php if (isset($msg)): ?>
	<p class="msg ok"><?php print $msg; ?></p>
<?php endif; ?>

<table>
	<tr>
		<th>Model</th>
		<th>Source</th>
		<th>Table</th>
	</tr>
	<?php foreach ($models as $model): ?>
	<tr>
		<td><?php print substr($model['info']['basename'], 0, strpos($model['info']['basename'], '.xml')); ?></td>
		<td><?php print $model['source'] ?></td>
		<td><?php print $model['status'] ?></td>
	</tr>
	<?php endforeach ?>
</table>

<p>
	<a href="<?php print url('ksetup/createmodels'); ?>" class="action">Create Models</a>
</p>
