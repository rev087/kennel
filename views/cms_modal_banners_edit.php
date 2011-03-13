<?php /*
<script type="text/javascript">
	function togglePublishDates() {
		if ($('programmed').get('checked')) $('publish_dates').setStyle('display', 'block');
		else $('publish_dates').setStyle('display', 'none');
	}

	(function() {
		datePickerOptions = {
			format: 'd/m/Y H:i',
			inputOutputFormat: 'Y-m-d H:i',
			animationDuration: 250,
			startDay: 0,
			allowEmpty: true,
			timePicker: true,
			zIndex: $$('.modal_window')[0].getStyle('zIndex') + 1
		};
		publish = new DatePicker($('publish_date'), datePickerOptions);
		unpublish = new DatePicker($('unpublish_date'), datePickerOptions);
		
		$('urlpicker').addEvent('click', function(e) {
			e.preventDefault();
			modal.load(this.get('href'), function(data) {
				if(data.typedURL && data.typedURL !== 'http://') {
					$('urlpicker').innerHTML = data.typedURL;
					$('url').set('value', data.typedURL);
				} else if(data.pageURL) {
					$('urlpicker').innerHTML = data.pageURL;
					$('url').set('value', data.pageURL);
				} else {
					$('urlpicker').innerHTML = 'No link.';
					$('url').set('value', '');
				}
			});
		});
		
		//togglePublishDates();
		//$$('input[type=radio]').addEvent('change', togglePublishDates);
	}).delay(10);
</script>
*/ ?>

<form action="<?php print url('cms/banners/save'); ?>" method="post">
	<h1><?php print i18n::get('Edit Banner'); ?></h1>

	<div class="messages"></div>
	
	<input type="hidden" name="position_id" id="position_id" value="<?php print $position_id; ?>" />
	<input type="hidden" name="image_id" id="image_id" value="<?php if ($banner->image_id) print $banner->image->id; ?>" />
	<input type="hidden" name="id" id="id" value="<?php print $banner->id ?>" />
	
	<div class="image-selection">
		<?php if(!$banner->image_id): ?>
			<p class="placeholder">
				No image selected yet. <br />Click on Set Image to select one.
			</p>
		<?php else: ?>
			<img src="<?php print $banner->image->thumb(150, 150); ?>" class="image_selection" />
		<?php endif; ?>
	</div>
	
	<p>
		<a href="<?php print url('cms/images/picker/banners'); ?>" class="modal" id="imagepicker">Set Image</a>
	</p>
	
	<label for="title"><?php print i18n::get('Title'); ?></label>
	<input type="text" name="title" id="title" class="text wide" value="<?php print esc::attr($banner->title); ?>" />
	
	<label for="description"><?php print i18n::get('Description'); ?></label>
	<textarea name="description" id="description" class="small"><?php print $banner->description; ?></textarea>
	
	<label>URL</label>
	<?php /*
	<div id="url_field">
		<?php if (!$banner->url): ?>
			<p>
				<p><small><a id="urlpicker" class="modal editable" href="<?php print url('cms/pages/picker'); ?>">No link.</a> (click to edit)</small></p>
			</p>
		<?php else: ?>
			<p><small><a id="urlpicker" class="modal editable" href="<?php print url('cms/pages/picker'); ?>"><?php print $banner->url; ?></a> (click to edit)</small></p>
		<?php endif; ?>
	</div>
	*/ ?>
	<input type="text" class="text" name="url" id="url" value="<?php print esc::attr($banner->url); ?>" />
	
	<label>Publishing Status</label>
	<div>
		<?php $draft = $banner->status === "0" || !$banner->id ? 'checked="checked"' : ''; ?>
		<input type="radio" id="draft" name="status" value="0" <?php print $draft; ?>/>
		<label for="draft" class="status_unpublished radio">Unpublished</label>
	</div>
	<div>
		<?php $published = $banner->status === "1" ? 'checked="checked"' : ''; ?>
		<input type="radio" id="published" name="status" name="status" value="1" <?php print $published; ?> />
		<label for="published" class="status_published radio">Published</label>
	</div>
	<?php /*
	<div>
		<?php $programmed = $banner->status === "2" ? 'checked="checked"' : ''; ?>
		<input type="radio" id="programmed" name="status" name="status" value="2" <?php print $programmed; ?> />
		<label for="programmed" class="status_standby radio">Programmed</label>
	</div>

	<p id="publish_dates">
		<span class="inline">
			<label for="publish_date">Publish Date</label>
			<input type="text" name="publish_date" id="publish_date" class="text wide" value="<?php print $banner->publish_date; ?>" />		
		</span>
		<span class="inline">
			<label for="unpublish_date">Unpublish Date</label>
			<input type="text" name="unpublish_date" id="unpublish_date" class="text wide" value="<?php print $banner->unpublish_date; ?>" />		
		</span>
	</p>
	*/ ?>
	
	<div class="actions">
		<input type="reset" class="cancel modal-dismiss" value="Cancel" />
		<input type="submit" class="ok" value="Save" />
	</div>
	
</form>
