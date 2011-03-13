<div class="full_pane">
	<h1>Banners</h1>

	<?php if (isset($message)) print $message; ?>

	<?php foreach($positions as $position): ?>
	<table>
		<tr>
			<th colspan="4"><?php print "{$position->title} <small>({$position->width}x{$position->height})</small>"; ?></th>
		</tr>
		<?php $n=0; foreach($banners[$position->id] as $banner): $n++; ?>
		<tr class="odd">
			<td>
				<a class="lightbox" href="<?php print $banner->image->path() ?>">
					<img src="<?php print $banner->image->thumb(150, 150); ?>" width="35" height="35" alt="<?php print $banner->title; ?>" />
				</a>
			</td>
			<?php if($banner->status == 0): ?>
			<td><span class="status_unpublished">unpublished</span></td>
			<?php elseif($banner->status == 1): ?>
			<td><span class="status_published">published</span></td>
			<?php elseif($banner->status == 2): ?>
			<td><span class="status_standby">programmed</span></td>
			<?php endif ?>
			<td><span id="<?php print "title_{$banner->id}"; ?>"><?php print $banner->title ?></span></td>
			<td class="row_actions">
				<a href="<?php print url("cms/banners/edit/{$position->id}/{$banner->id}"); ?>" class="action_edit modal">Edit</a>
				<a rel="<?php print "title_{$banner->id}"; ?>" href="<?php print url("cms/banners/delete/$banner->id"); ?>" class="action_delete delete_cancel_dialog">Delete</a>
			</td>
		</tr>
		<?php endforeach; ?>
		<?php if(count($banners[$position->id]) == 0): ?>
		<tr>
			<td colspan="5">
				<p class="placeholder">There are no banners in this position yet.</p>
			</td>
		</tr>
		<?php endif ?>
		<tr>
			<td colspan="4">
				<a href="<?php print url("cms/banners/edit/{$position->id}"); ?>" class="add modal">Add Banner</a>
			</td>
		</tr>
	</table>
	<?php endforeach; ?>
</div>
