<div class="wrap wpframework ">
	<div class="icon32" id="icon-options-general"><br></div>
	<h2 class="nav-tab-wrapper">
        <?php echo $this->getNavTabs(array('settings' => 'Settings', 'help' => 'Help')); ?>
    </h2>


    <form method="post" action="" enctype="multipart/form-data">

        <div class="wpf_header">
            <input name="reset" type="submit" value="Reset Options" class="button left" onclick="return confirm('Click OK to reset. Any settings will be lost!');">
            <input type="submit" value="Save All Changes" class="button-primary right" name="save-settings" id="save">
        </div>

        <div class="wpf_content">

            <!-- MAIN COLUMN START -->
            <div class="container">
                <div class="content">
                <?php include('plugin-' . $page . ".php"); ?>
                </div>
            </div>
            <!-- MAIN COLUMN END -->

            <!-- SIDE COLUMN START -->
            <?php include("plugin-side.php"); ?>
            <!-- SIDE COLUMN END -->
        </div>

        <div class="wpf_footer">
            <input name="reset" type="submit" value="Reset Options" class="button left" onclick="return confirm('Click OK to reset. Any settings will be lost!');">
            <input type="submit" value="Save All Changes" class="button-primary right" name="save-settings" id="save">
        </div>
    </form>

</div>

