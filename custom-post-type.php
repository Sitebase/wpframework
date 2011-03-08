<?php

/*
Plugin Name: CPT Portfolio
Plugin URI: http://www.sitebase.be
Description: This is a custom post type example created with the WordPress Framework by Sitebase
Author: Sitebase
Version: 1.0
Requires at least: 2.8
Author URI: http://www.sitebase.be
*/
	
// Include library
if(!class_exists('WpFramework_Base_0_4')) include "library/wp-framework/Base.php";
include_once "library/wp-framework/vo/Form.php";

class CptExample extends WpFramework_Base_0_4 {
		
		const NAME = 'CPT Example';
		const NAME_SLUG = 'cpt-example';
	
		/**
		 * Array of form validators for corresponding fields
		 * 
		 * @var array
		 */
		private $_form_validators = null;
		
		/**
		 * Form fields an default values
		 * 
		 * @var array
		 */
		private $_form_fields_default = array(
				'website_url'		=> '',
				'year'				=> ''
		);
		
		/**
		 * Constructor
		 * 
		 * @return void
		 */
		public function __construct(){
			
			// Call parent constructor
			parent::__construct();
			
			// Validate input
			include_once $this->plugin_path . '/library/wp-framework/validators/Abstract.php';
			include_once $this->plugin_path . '/library/wp-framework/validators/NotEmpty.php';
			include_once $this->plugin_path . '/library/wp-framework/validators/Url.php';
			include_once $this->plugin_path . '/library/wp-framework/validators/Integer.php';
			$this->_form_validators['website_url'][] = new WpFramework_Validators_NotEmpty(__('This field is required'));
			$this->_form_validators['website_url'][] = new WpFramework_Validators_Url(__('This isn\'t a valid URL'));
			$this->_form_validators['year'][] = new WpFramework_Validators_Integer(__('This input must be a numeric value'));
			
		}
		
		/**********************************************************
		 * PRIVATE METHODS
		 **********************************************************/
		
		
		/**********************************************************
		 * PUBLIC METHODS
		 **********************************************************/
		
		public function action_init() {
			$portfolio_args = array(
	        	'label' => __('Portfolio'),
	        	'singular_label' => __('Portfolio'),
	        	'public' => true,
	        	'show_ui' => true,
	        	'capability_type' => 'post',
	        	'hierarchical' => false,
	        	'rewrite' => true,
	        	'supports' => array('title', 'editor', 'thumbnail')
	        );
	        
	    	register_post_type('portfolio',$portfolio_args);
		}
		
		public function action_admin_init() {
			add_meta_box("details", "Options", array($this, "options"), "portfolio", "normal", "low");
		}
		
		public function action_save_post($postpage_id) {
			$this->save_post_meta($postpage_id, array_keys($this->_form_fields_default), $_POST);
		}
		
		function options(){
			global $post;
			$data = $this->get_post_meta($post->ID, array_keys($this->_form_fields_default));
			
			// If not isset the form is not submitted
			$validation_results = $this->validate_fields(array_merge($this->_form_fields_default, $data), $this->_form_validators);
			$data['wpform'] = new WpFramework_Vo_Form(array_merge($this->_form_fields_default, $data), $validation_results);
			$this->load_view($this->plugin_path . "/views/cpt-options.php", $data);
		}
		
		/**
		 * Load stylesheet
		 * 
		 * @return void
		 */
		public function action_admin_print_styles() {
			$this->enqueue_style('cpt-portfolio-style',  $this->plugin_url . '/assets/cpt.css', null, '1.0'); 
		}

}
	
$_GLOBALS['cpt-example'] = new CptExample();
    