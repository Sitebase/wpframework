<h2>Some settings</h2>
<div>
  <?php if($wpform->isSaved()){ ?>
  	<div class="wpf_message success"><p>Changes are saved successfully.</p></div>
  <?php } ?>

        <div class="wpf_message warning legend-required"><p>Fields marked with <span>*</span> are required.</p></div>

        <fieldset>
            <legend>Global settings</legend>
            <ul class="field-holder">
                <li>
                    <div class="form-label"><label for="firstname"><?php echo __('Firstname', $this->plugin_name) ?><span>*</span></label></div>
                    <div class="form-field"><input type="text" id="firstname" name="firstname" value="<?php echo $wpform->getField('firstname'); ?>" /></div>
                    <div class="form-description wpfextra wpfsmall"><?php echo $wpform->hasErrors('firstname') ? '<span class="error">' . $wpform->getErrors('firstname', 1) . '</span>' : __('Fill in your first name.', $this->plugin_name) ?></div>
                </li>
                <li>
                    <div class="form-label"><label for="lastname"><?php echo __('Lastname', $this->plugin_name) ?><span>*</span></label></div>
                    <div class="form-field"><input type="text" id="lastname" name="lastname" value="<?php echo $wpform->getField('lastname'); ?>" /></div>
                    <div class="form-description wpfextra wpfsmall"><?php echo $wpform->hasErrors('lastname') ? '<span class="error">' . $wpform->getErrors('lastname', 1) . '</span>' : __('Fill in your last name.', $this->plugin_name) ?></div>
                </li>
                <li>
                    <div class="form-label"><label for="age"><?php echo __('Age', $this->plugin_name) ?></label></div>
                    <div class="form-field"><input type="text" id="age" name="age" value="<?php echo $wpform->getField('age'); ?>" /></div>
                    <div class="form-description wpfextra wpfsmall"><?php echo $wpform->hasErrors('age') ? '<span class="error">' . $wpform->getErrors('age', 1) . '</span>' : __('Fill in your age. Make sure that your age is between 10 and 99.', $this->plugin_name) ?></div>
                </li>
            </ul>
        </fieldset>

</div>
