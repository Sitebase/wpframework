<table width="100%" border="0" class="options" cellspacing="0" cellpadding="0">
      <tr>
        <td width="20%"><label for="text"><?php echo __('Text', $this->plugin_name) ?><span>*</span></label></td>
        <td width="10%" align="right"><input type="text" id="text" name="text" value="<?php echo $wpform->getField('text'); ?>" /></td>
        <td width="70%" class="extra small"><?php echo $wpform->hasErrors('text') ? '<span class="error">' . $wpform->getErrors('text', 1) . '</span>' : __('Text to show', $this->plugin_name) ?></td>
      </tr>
</table>