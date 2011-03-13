<form action="<?php print url('cms/banner_settings/save_position'); ?>" method="post">
	<h1>Edit Banner Position</h1>
	
	<input type="hidden" name="id" value="<?php print $pos->id; ?>" />
	
	<label for="title">Title</label>
	<input type="text" class="text" name="title" id="title" value="<?php print $pos->title; ?>" />
	
	<label for="width">Width</label>
	<input type="text" class="text" name="width" id="width" value="<?php print $pos->width; ?>" />
	
	<label for="height">Height</label>
	<input type="text" class="text" name="height" id="height" value="<?php print $pos->height; ?>" />
	
	<label for="rotation">Rotation</label>
	<input type="text" class="text" name="rotation" id="rotation" value="<?php print $pos->rotation; ?>" />
	
	<div class="actions">
		<input type="reset" class="cancel modal-dismiss" value="Cancel" />
		<input type="submit" class="ok" value="Save" />
	</div>
</form>
