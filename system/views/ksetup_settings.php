<h1>Settings</h1>

<table>
	<tr>
		<th colspan="2">Application</th>
	</tr>
	<?php foreach ($settings['application'] as $key=>$val): ?>
	<tr>
		<td><?php print $key ?></td>
		<td>
			<?php
			if(is_bool($val)) print $val?'yes':'no';
			else print $val;
			?>
		</td>
	</tr>
	<?php endforeach ?>
</table>