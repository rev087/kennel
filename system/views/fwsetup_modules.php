<h1>Modules</h1>

<table>
	<tr>
		<th>Module ID</th>
		<th>Dependencies</th>
	</tr>
	<?php foreach ($modules as $module): ?>
	<tr>
		<td><?php print $module['id']; ?></td>
		<td><?php if (isset($module['dependencies'])) print join(', ', $module['dependencies']); ?></td>
	</tr>
	<?php endforeach ?>
</table>
