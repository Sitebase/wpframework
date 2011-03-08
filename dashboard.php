<?php

/*
Plugin Name: Dashboard Widget Example
Plugin URI: http://www.sitebase.be
Description: This is an example dashboard widget created with the WordPress Framework by Sitebase
Author: Sitebase
Version: 1.0
Requires at least: 2.8
Author URI: http://www.sitebase.be
*/
	
// Include library
if(!class_exists('WpFramework_Base_0_4')) include "library/wp-framework/Base.php";
include_once "library/wp-framework/vo/Form.php";

class DashboardExample extends WpFramework_Base_0_4 {
		
		const NAME = 'Dashboard Example';
		const NAME_SLUG = 'dashboard-example';
		
		/**
		 * Array of form validators for corresponding fields
		 * 
		 * @var array
		 */
		private $_form_validators = null;
		
		public function action_wp_dashboard_setup() {
			
			// Validate input
			include_once $this->plugin_path . '/library/wp-framework/validators/Abstract.php';
			include_once $this->plugin_path . '/library/wp-framework/validators/NotEmpty.php';
			$this->_form_validators['text'][] = new WpFramework_Validators_NotEmpty(__('This field is required'));
			wp_add_dashboard_widget( self::NAME_SLUG, __( self::NAME, $this->plugin_name ), array(&$this, "display"), array(&$this, "setup") );
		}
		
		public function display() {
			$data = $this->get_option(self::NAME_SLUG);
			echo 'Your text is: ' . $data['text'];
		}
		
		public function setup() {
			
			$data = $this->get_option(self::NAME_SLUG);

			// Do form validation
			if(!isset($_POST)) $_POST = array();
			if(!is_array($data)) $data = array();
			$validation_results = $this->validate_fields(array_merge($data, $_POST), $this->_form_validators);
			$data['wpform'] = new WpFramework_Vo_Form(array_merge(array_merge($data, $_POST)), $validation_results);

			// Save data if posted
			if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['widget_id'] ) && self::NAME_SLUG == $_POST['widget_id'] ) {
				$this->save_option(self::NAME_SLUG, $data['wpform']->getFields());
			}
			
			// Load view
			$this->load_view($this->plugin_path . "/views/dashboard-options.php", $data);
			
		}

}
	
$_GLOBALS['dashboard-example'] = new DashboardExample();
    