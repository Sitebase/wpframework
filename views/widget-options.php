<p class=" form-field">
	<label for="title"><?php echo __('Title', 'wpframework') ?>:</label>
	<input type="text" class="widefat" value="<?php echo $wpform->getField('title'); ?>" name="title" id="title">
	<?php if($wpform->hasErrors('title')) echo '<span class="error">' . $wpform->getErrors('title', 1) . '</span>' ?>
</p>
<p class=" form-field">
	<label for="title"><?php echo __('Text', 'wpframework') ?>:</label>
	<input type="text" class="widefat" value="<?php echo $wpform->getField('text'); ?>" name="text" id="text">
	<?php if($wpform->hasErrors('text')) echo '<span class="error">' . $wpform->getErrors('text', 1) . '</span>' ?>
</p>