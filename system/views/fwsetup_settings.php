<h1>Settings</h1>

	<table>
		<tr>
			<th colspan="2">Application</th>
		</tr>
		<?php foreach ($settings['application'] as $key=>$val): ?>
		<tr>
			<td><?php print $key ?></td>
			<td><?php print $val?'yes':'no' ?></td>
		</tr>
		<?php endforeach ?>
	</table>

	<table>
		<tr>
			<th colspan="2">Paths</th>
		</tr>
		<?php foreach ($settings['path'] as $key=>$val): ?>
		<tr>
			<td><?php print $key ?></td>
			<td><?php print $val ?></td>
		</tr>
		<?php endforeach ?>
	</table>
