<?php

/*
Plugin Name: Widget Example
Plugin URI: http://www.sitebase.be
Description: This is an example widget created with the WordPress Framework by Sitebase
Author: Sitebase
Version: 1.0
Requires at least: 2.8
Author URI: http://www.sitebase.be
*/
	
// Include library
if(!class_exists('WpFramework_Base_0_5')) include "library/wp-framework/Base.php";
if(!class_exists('WpFramework_Vo_Form')) include_once "library/wp-framework/vo/Form.php";

class WidgetExample extends WpFramework_Base_0_5 {
		
	const NAME = 'Widget Example';
	const NAME_SLUG = 'wexample';
		
	private $_form_fields_default = array(
			'title'		=> 'Example Widget',
			'text'		=> 'Hello World'
	);
	
	private $_form_validators = null;
	
	public function WidgetExample() {
		parent::__construct();
		$widget_ops = array('description' => 'This is an example widget created with WP Framework.' );
		$this->WP_Widget(self::NAME_SLUG, self::NAME, $widget_ops);
		
		// Validate input
		if(!class_exists('WpFramework_Validators_Abstract')) include_once $this->plugin_path . '/library/wp-framework/validators/Abstract.php';
		if(!class_exists('WpFramework_Validators_NotEmpty')) include_once $this->plugin_path . '/library/wp-framework/validators/NotEmpty.php';
		$this->_form_validators['title'][] = new WpFramework_Validators_NotEmpty(__('This field is required'));
		$this->_form_validators['text'][] = new WpFramework_Validators_NotEmpty(__('This field is required'));
	}
	
	/**
	 * Widget display method
	 * 
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget($args, $instance) {
		$data = array_merge($args, $instance);
		$this->load_view($this->plugin_path . "/views/widget-view.php", $data);
	}
	
	/**
	 * Widget update method
	 * 
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return void
	 */
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		foreach($this->_form_fields_default as $key => $value){
			$instance[$key] = strip_tags($_POST[$key]);
		}
		return $instance;
	}
	
	/**
	 * Widget form method
	 * 
	 * @param array $instance
	 * @return void
	 */
	public function form($instance) {
		$instance = wp_parse_args( (array) $instance, $this->_form_fields_default );

		// If not isset the form is not submitted
		$validation_results = $this->validate_fields($instance, $this->_form_validators);
		$data['wpform'] = new WpFramework_Vo_Form($instance, $validation_results);
		
		$this->load_view($this->plugin_path . "/views/widget-options.php", $data);
	}

}
	
// Instead of creating a instance like for a dashboard widget/cpt or plugin
// You trigger the widget this way
add_action('widgets_init', create_function('', 'register_widget("WidgetExample");'));