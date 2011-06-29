<?php
	$rotation = array(
		0 => 'Natural',
		1 => 'Random'
	);
?>
<div class="main_pane">
	<h1>Banner Settings</h1>
	
	<?php print message::alert('<strong>Warning:</strong> Changing these settings will require changes in the website layout'); ?>
	
	<?php if (isset($msg)) print $msg; ?>
	
	<table>
		<caption>Banner Positions</caption>
		<tr>
			<th>Title</th>
			<th>Keyword</th>
			<th>Size</th>
			<th>Rotation</th>
			<th></th>
		</tr>
		<?php foreach ($positions as $pos): ?>
			<tr>
				<td id="<?php print "i{$pos->id}"; ?>"><?php print $pos->title; ?></td>
				<td><?php print $pos->keyword; ?></td>
				<td><?php print "{$pos->width}x{$pos->height}"; ?></td>
				<td><?php print $rotation[$pos->rotation]; ?></td>
				<td class="row_actions">
					<a href="<?php print url("cms/banner_settings/edit_position/{$pos->id}"); ?>" class="action_edit modal">Edit</a>
					<a href="<?php print url("cms/banner_settings/delete_position/{$pos->id}"); ?>" class="action_delete delete_cancel_dialog" rel="<?php print "i{$pos->id}"; ?>">Delete</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	
	<div class="pagination"><?php print $pagination; ?></div>
	
</div>

<div class="side_pane">
	<h2>Actions</h2>
	
	<p>
		<a href="<?php print url('cms/banner_settings/add_position'); ?>" class="modal">Add Banner Position</a>
	</p>
	
</div>
