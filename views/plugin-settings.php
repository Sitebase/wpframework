<h3><a href="#" class="toggle-buttton">-</a>Some settings</h3>
<div>
  <?php if($wpform->isSaved()){ ?>
  	<div class="success">Changes are saved successfully.</div>
  <?php } ?>
  <form action="" method="post" enctype="multipart/form-data" id="frm-replacer">
    <table width="100%" border="0" class="options" cellspacing="0" cellpadding="0">
      <tr>
        <td width="20%"><label for="firstname"><?php echo __('Firstname', $this->plugin_name) ?><span>*</span></label></td>
        <td width="40%" align="right"><input type="text" id="firstname" name="firstname" value="<?php echo $wpform->getField('firstname'); ?>" /></td>
        <td width="40%" class="extra small"><?php echo $wpform->hasErrors('firstname') ? '<span class="error">' . $wpform->getErrors('firstname', 1) . '</span>' : __('Fill in your first name.', $this->plugin_name) ?></td>
      </tr>
      <tr>
        <td width="20%"><label for="lastname"><?php echo __('Lastname', $this->plugin_name) ?><span>*</span></label></td>
        <td width="40%" align="right"><input type="text" id="lastname" name="lastname" value="<?php echo $wpform->getField('lastname'); ?>" /></td>
        <td width="40%" class="extra small"><?php echo $wpform->hasErrors('lastname') ? '<span class="error">' . $wpform->getErrors('lastname', 1) . '</span>' : __('Fill in your last name.', $this->plugin_name) ?></td>
      </tr>
      <tr>
        <td width="20%"><label for="age"><?php echo __('Age', $this->plugin_name) ?></label></td>
        <td width="40%" align="right"><input type="text" id="age" name="age" value="<?php echo $wpform->getField('age'); ?>" /></td>
        <td width="40%" class="extra small"><?php echo $wpform->hasErrors('age') ? '<span class="error">' . $wpform->getErrors('age', 1) . '</span>' : __('Fill in your age. Make sure that your age is between 10 and 99.', $this->plugin_name) ?></td>
      </tr>
      <tr>
        <td width="20%"><label for="avatar"><?php echo __('Avatar', $this->plugin_name) ?></label></td>
        <td width="40%" align="right"><input type="file" id="avatar" name="avatar" /></td>
        <td width="40%" class="extra small"><?php echo $wpform->hasErrors('avatar') ? '<span class="error">' . $wpform->getErrors('avatar', 1) . '</span>' : __('Upload your avatar.', $this->plugin_name) ?></td>
      </tr>
      <tr>
        <td colspan="3"><input type="submit" value="Save" class="button-primary" name="save-settings" id="save"></td>
      </tr>
    </table>
  </form>
</div>
