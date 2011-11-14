<div class="wpframework">
<table width="100%" border="0" class="wpf_table" cellspacing="0" cellpadding="0">
      <tr>
        <td width="20%"><label for="website_url"><?php echo __('Website URL', $this->plugin_name) ?><span>*</span></label></td>
        <td width="40%" align="right"><input type="text" id="website_url" name="website_url" value="<?php echo $wpform->getField('website_url'); ?>" /></td>
        <td width="40%" class="extra small"><?php echo $wpform->hasErrors('website_url') ? '<span class="error">' . $wpform->getErrors('website_url', 1) . '</span>' : __('Fill in the url of the website.', $this->plugin_name) ?></td>
      </tr>
      <tr>
        <td width="20%"><label for="year"><?php echo __('Year', $this->plugin_name) ?><span>*</span></label></td>
        <td width="40%" align="right"><input type="text" id="year" name="year" value="<?php echo $wpform->getField('year'); ?>" /></td>
        <td width="40%" class="extra small"><?php echo $wpform->hasErrors('year') ? '<span class="error">' . $wpform->getErrors('year', 1) . '</span>' : __('When did you made this item?', $this->plugin_name) ?></td>
      </tr>
</table>
</div>