<?php

/*
Plugin Name: Plugin Example
Plugin URI: http://www.sitebase.be
Description: This is an example plugin created with the WordPress Framework by Sitebase
Author: Sitebase
Version: 1.0
Requires at least: 2.8
Author URI: http://www.sitebase.be
*/
	
// Include library
if(!class_exists('WpFramework_Base_0_6')) include "library/wp-framework/Base.php";
if(!class_exists('WpFramework_Vo_Form')) include_once "library/wp-framework/Vo/Form.php";

class PluginExample extends WpFramework_Base_0_6 {
		
		const NAME = 'Plugin Example';
		const NAME_SLUG = 'plugin-example';
	
		/**
		 * Constructor
		 * 
		 * @return void
		 */
		public function __construct(){

			// Call parent constructor
			parent::__construct();

			// Define form handlers
			$this->load(array('Abstract', 'NotEmpty', 'Integer', 'FileSize'), 'WpFramework_Validators_');
			$validators['firstname'][] = new WpFramework_Validators_NotEmpty(__('This field is required'));
			$validators['lastname'][] = new WpFramework_Validators_NotEmpty(__('This field is required'));
			$validators['age'][] = new WpFramework_Validators_Integer(__('Make sure this field is between 10 and 99.'));
			$validators['age'][] = new WpFramework_Validators_NotEmpty(__('This field is required.'));
			$validators['avatar'][] = new WpFramework_Validators_FileSize(__('Maximum 200'), 100000);
			$this->add_form_handler('save-settings', $validators, array(&$this, 'save_settings'));
			
		}
		
		/**
		 * Add item to admin menu
		 *
		 * @return void
		 */
		public function action_admin_menu(){
			$plugin_page = $this->add_options_page('Framework Options', 'Framework Example', self::USER_LEVEL_SUBSCRIBER, self::NAME_SLUG, array(&$this, "admin_page_show"));
		}
		
		/**
		 * Load stylesheet
		 * 
		 * @return void
		 */
		public function action_admin_print_styles() {
			if(isset($_GET['page']) && $_GET['page'] == self::NAME_SLUG) {
				$this->enqueue_style('wpframeworktest-style',  $this->plugin_url . '/assets/css/wpf.css', null, '1.0');
			}
		}
		
		/**
		 * Load javascript
		 * 
		 * @return void
		 */
		public function action_admin_print_scripts() {
			if(isset($_GET['page']) && $_GET['page'] == self::NAME_SLUG) {
				$this->enqueue_script('jquery');
				$this->enqueue_script('wpframeworktest-style',  $this->plugin_url . '/assets/script.js', array("jquery"), '1.0'); 
			}
		}
		
		/**
		 * Load settings page & handle form submit
		 *
		 * @return void
		 */
		public function admin_page_show(){

			// Add selected page
			// @todo In framework
			$data['page'] = isset($_GET['tab']) && in_array($_GET['tab'], array("settings", "help")) ? $_GET['tab'] : "settings";

			$data['options'] = $this->get_option( self::NAME_SLUG );

			// Validate fields and trigger form handler
			//$data['validation'] = $this->handle_form();
			$data['wpform'] = $this->auto_handle_forms($data['options']);

			// Make sure the data is secure to display
			$clean_data = $this->clean_display_array($data);
		
			// Load view
			// The tab to show is handled in the index.php view
			$this->load_view($this->plugin_path . "/views/plugin-index.php", $clean_data);
		}
		
		/**
		 * Handle save settings
		 *
		 * @param array $data
		 * @return void
		 */
		public function save_settings(&$form){
			$this->save_option(self::NAME_SLUG, $form->getFields());
		}
		
		/**
		 * Add a settings link to the plugin overview page
		 * 
		 * @param array $links
		 * @return array
		 */
		public function filter_plugin_action_links($links){
			$settings_link = '<a href="options-general.php?page=' . self::NAME_SLUG . '">Settings</a>'; 
  			array_unshift($links, $settings_link); 
			return $links;
		}
		
		/**
		 * Add some content to the footer
		 * 
		 * @return void
		 */
		public function action_get_footer() {
			$options = $this->get_option(self::NAME_SLUG);
			echo 'My name is ' . $options['firstname'] . ' ' . $options['lastname'];
			if(is_numeric($options['age'])) {
				echo ' and my age is ' . $options['age'];
			}
			echo '.';
		}
		
		/**
		 * Delete option when the plugin is deactivated
		 * Clean up your garbage!
		 * 
		 * @return void
		 */
		public function deactivate() {
			$this->delete_option( self::NAME_SLUG );
		}

}
	
$_GLOBALS['plugin-example'] = new PluginExample();
    