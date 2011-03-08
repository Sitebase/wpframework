<?php

/**
 * Basic framework for Wordpress plugins
 * Handles some actions that are very commonly used in
 * Wordpress plugins
 * 
 * @see Plugin API
 * http://codex.wordpress.org/Plugin_API
 * 
 * @see Action Reference
 * http://codex.wordpress.org/Plugin_API/Action_Reference
 * 
 * @see Filter Reference
 * http://codex.wordpress.org/Plugin_API/Filter_Reference
 * 
 * 
 * @author 		Sitebase (Wim Mostmans)
 * @copyright  	Copyright (c) 2011, Sitebase (http://www.sitebase.be)
 * @license    	http://www.opensource.org/licenses/bsd-license.php    BSD License
 * @version 	0.3
 */
if(!class_exists('WpBase_0_3')){
abstract class WpBase_0_3  {
	
	/**
	 * The plugin main file
	 * @var string
	 */
	 protected $plugin_file;
	 
	 /**
	  * The plugin path
	  * @var string
	  */
	 protected $plugin_path;
	 
	 /**
	  * The plugin url
	  * @var string
	  */
	 protected $plugin_url;
	 
	 /**
	  * The plugin name
	  * @var string
	  */
	 protected $plugin_name;
	 
	 /**
	  * Debug mode
	  * If this is on log will be written to file
	  * If this is off the logs will be send to the black hole of doom
	  * @var bool
	  */
	 protected $debug_mode = true;
	 
	 /**
	  * Form handler
	  * With this array you can define based on a field that is set wich form handler method
	  * must be called.
	  * Best practice for this is to use the submit button name, because this is always set 
	  * when a form is submitted.
	  * 
	  * Example:
	  * array('save-settings', array($this, 'save_settings'));
	  * 
	  * So when 'save-settings' is found in the POST vars en the rest of the fields are valid then
	  * the method 'save_settings' is called.
	  * 
	  * @var array
	  */
	 protected $form_handler = array();
	 
	 /**
	  * Form field validators
	  * With this array you can apply validators to specific fields
	  * 
	  * For example:
	  * array('email' => array(VALIDATE_REQUIRED, VALIDATE_EMAIL));
	  * When a form is submitted and there is a field in the form with name "email" then this
	  * field is checked with to validator methods (validator_required and validator_email) 
	  * if they both return valid than the field has a valid id and the form will be processed
	  * if all other fields are valid.
	  * 
	  * You can also use a custom validator. If you for example add "custom_password" then
	  * the method "validator_custom_password" is called to validate the field.
	  * Offcourse you will need to add this method to your plugin class.
	  * 
	  * @var array
	  */
	 protected $form_field_validators = array();
	 
	/**
	 * Wordpress var types
	 */
	const ACTION									= "ACTION";
	const FILTER									= "FILTER";
	const OPTION									= "OPTION";

	/**
	 * User level
	 */
	const USER_LEVEL_ADMINISTRATOR					= 8;
	const USER_LEVEL_EDITOR							= 3;
	const USER_LEVEL_AUTHOR							= 2;
	const USER_LEVEL_CONTRIBUTOR					= 1;
	const USER_LEVEL_SUBSCRIBER						= 0;
	
	/**
	 * Option constants that can be retrieved with get_option(CONSTANT);
	 */
	const OPTION_ACTIVE_PLUGINS						= "active_plugins";
	const OPTION_ADMIN_EMAIL						= "admin_email";
	const OPTION_SITE_URL							= "siteurl";
	const OPTION_BLOG_NAME							= "blogname";
	const OPTION_BLOG_DESCRIPTION					= "blogdescription";
	const OPTION_DATE_FORMAT						= "date_format";
	const OPTION_TIME_FORMAT						= "time_format";
	const OPTION_UPLOAD_PATH						= "upload_PATH";
	const OPTION_UPLOAD_URL							= "upload_url";
	const OPTION_PLUGIN_URL							= "plugin_url";
	const OPTION_WORDPRESS_PATH						= "wordpress_path";
	const OPTION_DATABASE_INSTANCE					= "database_instance";
	const OPTION_USERNAME							= "username";
	const OPTION_USER_LEVEL							= "user_level";
	const OPTION_USER_ID							= "user_id";
	const OPTION_LOCALE								= "locale";
	
	/**
	 * Slug constanct
	 * Use these to add menu items to existing menus
	 */
	const SLUG_DASHBOARD							= "index.php";
	const SLUG_POSTS								= "edit.php";
	const SLUG_MEDIA								= "upload.php";
	const SLUG_LINKS								= "link-manager.php";
	const SLUG_PAGES								= "edit.php?post_type=page";
	const SLUG_COMMENTS								= "edit-comment.php";
	const SLUG_APPEARANCE							= "themes.php";
	const SLUG_PLUGINS								= "plugins.php";
	const SLUG_USERS								= "users.php";
	const SLUG_TOOLS								= "tools.php";
	const SLUG_SETTINGS								= "options-general.php";
	
	/**
	 * Validators
	 * @var string
	 */
	const VALIDATE_REQUIRED							= "required";
	const VALIDATE_NUMERIC							= "numeric";
	const VALIDATE_EMAIL							= "email";
	const VALIDATE_URL								= "url";
	const VALIDATE_IP								= "ip";
	const VALIDATE_ALPHA							= "alpha";
	const VALIDATE_ALPHA_NUMERIC					= "alpha_numeric";
	const VALIDATE_LENGTH							= "length";
	const VALIDATE_HEX_COLOR						= "hex_color";
	const VALIDATE_DATE								= "date";
	const VALIDATE_PHONE							= "phone";
	
	/**
	 * Stylesheet media types
	 * @var string
	 */
	const MEDIA_ALL									= "all";
	const MEDIA_SCREEN								= "screen";
	const MEDIA_HANDHELD							= "handheld";
	const MEDIA_PRINT								= "print";
	
	/**
	 * Filter constants
	 */
	//const FILTER_THE_CONTENT						= "the_content";
	
	public function __construct(){
		
		// Set plugin path information
		$full_path = $this->get_child_path();
		$this->plugin_file = basename($full_path);
		$this->plugin_name = basename($full_path, '.php');
		$this->plugin_path = dirname($full_path);
		$this->plugin_url = $this->get_option(self::OPTION_PLUGIN_URL);

		// Call overwritten methods
		$this->call_hooks(self::ACTION);
		$this->call_hooks(self::FILTER);
		
		// Register for activation/deactivation hook if activate is overriden
		$overriden_methods = $this->get_overriden_methods(get_class($this));

		if(in_array('activate', $overriden_methods)){
			register_activation_hook($full_path, array(&$this, "activate"));
		}
		if(in_array('deactivate', $overriden_methods)){
			register_deactivation_hook($full_path, array(&$this, "deactivate"));
		}

		// Enable multilanguage
		add_action('init', array(&$this, 'add_textdomain'));

	}
	
	/**
	 * This function returns a list of usable wordpress variables
	 * from a specific type
	 * 
	 * For example: it can return a list a ACTION constants you can use
	 * 
	 * This method is for internal use. It is need to auto check wich actions
	 * are overwritten in the child class
	 * 
	 * @param string $type
	 * @return array
	 */
	private function get_wp_type_methods($type=self::ACTION){
		
		// Get class object
		$oClass = new ReflectionClass(get_class($this));
		$parent = $oClass->getParentClass();
		$methods = $parent->getMethods();

    	// Filter out other types than $type
    	$vars = array();
    	foreach($methods as $method){
    		if(substr($method->name, 0, 6) == strtolower($type) && strlen($method->name) > 6){
    			$vars[] = $method->name;
    		}
    	}

    	// Return result
    	return $vars;
		
	}
	
	/**
	 * Get overriden methods for a specific class
	 * 
	 * @param string $class_name
	 * @return array
	 */
	private function get_overriden_methods($class_name){
		$class = new ReflectionClass($class_name);
		$parent = $class->getParentClass();
		$methods = $class->getMethods();
		$overriden = array();
		foreach ($methods as $method)
		{
		    try
		    {
		        new ReflectionMethod($parent->getName(), $method->getName());
		        $decClass = $method->getDeclaringClass();
		        if ($decClass->getName() == $class_name)
		        {
					$overriden[] = $method->getName();
		        }
		    }
		    catch (exception $e){}
		}
		return $overriden;
	}
	
	/**
	 * This function will loop throuh all actions and see if it is 
	 * overwritten in the child calls.
	 * If that's the case it will call that method
	 * 
	 * @return void
	 */
	private function call_hooks($type=self::ACTION){
		
		// Get type vars
		$calls = $this->get_wp_type_methods($type);
		
		// Get a list of overriden methods
		$overriden_methods = $this->get_overriden_methods(get_class($this));
		
		// Check if the action or filter is implemented
		// If so, call it
		foreach($calls as $method){
			if(in_array($method, $overriden_methods)){
				switch($method){
					case 'filter_plugin_action_links':
						$method_string = $method . '_' . plugin_basename($this->plugin_path . '/' . $this->plugin_file);
						break;
					default:
						$method_string = $method;
						break;
				}
				add_action( str_replace(strtolower($type) . '_', '', $method_string), array(&$this, $method) );
			}
		}
		
	}
	
	/**
	 * Get the path of the child class
	 * 
	 * @return string
	 */
	private function get_child_path(){
		$reflector = new ReflectionClass(get_class($this));
        return $this->clean_path($reflector->getFileName());
	}
	
	/**
	 * Path cleanup function
	 * - Replaces backslashes with forward slashes
	 * - Removes trail slashes
	 * 
	 * @param string $path
	 * @return string
	 */
	public static function clean_path($path){
		$path = str_replace('\\','/', $path);
		if(substr($path, -1) == '/'){
			return substr($path, 0, -1);
		}
		return $path;
	}
	
	/**
	 * Read value from a file
	 * 
	 * @param string $path
	 * @param string $method
	 */
	public static function file_read($path, $method='r'){
		if(file_exists($path)) {
			$handle = fopen($path, "r");
			$content = fread($handle, filesize($path));
			fclose($handle);
			return $content;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Read remote file
	 * 
	 * @param string $url
	 * @param bool $force_curl
	 */
	public static function file_read_remote($url, $force_curl=false){

		if(function_exists('file_get_contents') && !$force_curl){
			return file_get_contents($url);
		}else{
			if (function_exists('curl_init')) {
			   $ch = curl_init(); 
			   curl_setopt($ch, CURLOPT_URL, $url); 
			   curl_setopt($ch, CURLOPT_HEADER, 0); 
			   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0'); 
			   $content = curl_exec($ch); 
			   curl_close($ch); 
			   return $content;
			}
		}
		trigger_error('Could not read remote file.', E_USER_WARNING);
	}
	
	/**
	 * Write value to a file
	 *
	 * @param string $path
	 * @param string $value
	 * @param string $method
	 * @return void
	 */
	public static function file_write($path, $value, $method='a'){
		if(!file_exists($path)) self::file_create($path);
		$fh = fopen($path, $method); 
		fwrite($fh, $value); 
		fclose($fh); 
	}
	
	/**
	 * Create a file
	 *
	 * @param string $path
	 * @return bool
	 */
	public static function file_create($path){
		$fh = @fopen($path, 'w');
		if($fh == NULL) return false;
		fclose($fh);
		if(file_exists($path)) return true;
		return false;
	}
	
	/**
	 * Get file and directory permissions
	 * 
	 * @param string $path
	 * @return string
	 */
	public static function file_permission($path){
		return substr(fileperms($path), -3);
	}
	
	/**
	 * Proxy for the get_option function
	 * This is needed to handle some of the WPBase option constants
	 * because they are not directly accisble by get_option
	 * 
	 * @param string $option
	 * @return *
	 */
	protected function get_option($option){
		
		switch($option){
			case self::OPTION_UPLOAD_PATH:
				$upload_dir_info = wp_upload_dir();
				return $upload_dir_info['basedir'];
			case self::OPTION_UPLOAD_URL:
				$upload_dir_info = wp_upload_dir();
				return $upload_dir_info['baseurl'];
			case self::OPTION_PLUGIN_URL:
				return WP_PLUGIN_URL . "/" . basename($this->plugin_path);
			case self::OPTION_WORDPRESS_PATH:
				return $this->clean_path(ABSPATH);
			case self::OPTION_DATABASE_INSTANCE:
				global $wpdb;
				return $wpdb;
			case self::OPTION_USER_ID:
				$user_info = get_userdata(1);
				return $user_info->ID;
			case self::OPTION_USERNAME:
				$user_info = get_userdata(1);
				return $user_info->user_login;
			case self::OPTION_USER_LEVEL:
				$user_info = get_userdata(1);
				return $user_info->user_level;
			case self::OPTION_LOCALE:
				return get_locale();
			default:
				return get_option($option);
		}
		
	}
	
	/**
	 * Set opions in the database
	 * with this method you can save/update an array/object in the WP database
	 * 
	 * @param string $name	Unique name to save the data to. For example use the plugin name
	 * @param * $data
	 * @return void
	 */
	public function save_option($name, $data){
    	if (!get_option($name)){
      		add_option($name, $data);
    	}else{
      		update_option($name, $data);
    	}
	}
	
	/**
	 * Delete options from the database
	 * 
	 * @param string $name
	 * @return bool
	 */
	public function delete_option($name){
		delete_option($name);
		return !get_option($name);
	}
	
	/**
	 * Get the current opened url
	 * 
	 * @param array $strip_attributes The attributes you want to strip from the returned url. For example ?name=sitebase&age=24 strip age will return ?name=sitebase
	 * @return string
	 */
	public function get_current_url($strip_attributes=array()) {
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") $pageURL .= "s";
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
	    	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}else {
	    	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		
		$parts = explode("&", $pageURL);
		$clean_parts = array();
		foreach($parts as $part){
			$key_value = explode("=", $part);
			if(!in_array($key_value[0], $strip)){
				$clean_parts[] = $part;	
			}
		}
		
	 	return implode("&", $clean_parts);
	}

	/**
	 * Get the total table name
	 * this adds the prefix to the name
	 * 
	 * @param string	$table
	 * @return string
	 */
	protected function get_table_name($table){
		return $this->get_option(self::OPTION_DATABASE_INSTANCE)->prefix . $table;
	}
	
	/**
	 * Check if a database exists
	 * 
	 * @param string $ptable Prefixed table name
	 * @return bool
	 */
	protected function table_exists($ptable){
		return ($this->get_option(self::OPTION_DATABASE_INSTANCE)->get_var("SHOW TABLES LIKE '" . $ptable . "'") == $ptable );
	}
	
	/**
	 * Create table and check if the table exists
	 * This functions uses the dbDelta
	 * The advantage of this is that it can change the 
	 * structure of a table if it is changed
	 * 
	 * @param string $create	The SQL create statement
	 * @param string $ptable		Fill in this if you want the method to check if the table is created
	 * @return bool
	 */
	protected function table_create($create, $ptable=null){
		require_once( $this->get_option(self::OPTION_WORDPRESS_PATH) . '/wp-admin/includes/upgrade.pgp');
		dbDelta($create);
		if($table == null) return true;
		return $this->table_exists($ptable);
	}
	
	/**
	 * Delete a table
	 * 
	 * @param string $ptable
	 * @return bool
	 */
	protected function table_delete($ptable){
		$database = $this->get_option(self::OPTION_DATABASE_INSTANCE);
		$database->query("DROP TABLE '" . $database->escape($ptable) . "'");
		return !$this->table_exists($ptable);
	}
	
	/**
	 * Writes a logtext to a file in the plugin root directory
	 * 
	 * @todo Correct code for writing to browser javascript console
	 * @param string $value
	 */
	public function log($value){
		
		// If debug mode off stop
		if(!$this->debug_mode) return;
		
		// Get backtrace
		$trace = debug_backtrace();
		
		// If value is object or array convert it to string
		if(is_object($value) || is_array($value)) $value = print_r($value, true);
		
		// Vars
		$find = array('%ip%', '%date%', '%time%', '%message%', '%file%', '%line%');
		$replace = array($_SERVER['REMOTE_ADDR'], date("m/d/y"), date('H:i:s'), $value, $trace[0]['file'], $trace[0]['line']);
		$line_format = '%ip% - [%date% %time%] %message% (%file%:%line%)' . "\n";
		$line = str_replace( $find, $replace, $line_format);
		
		// Create log file path
		$logfile = $this->plugin_path . "/logs/" . date('Ymd') . ".log";
		
		// Write to log
		$this->file_write($line, $logfile);
		
	}
	
	/**
	 * Check if we are in the admin panel
	 * 
	 * @return bool
	 */
	protected function is_backend(){
		return is_admin();
	}
	
	/**
	 * Check if a file is still valid as cache 
	 * 
	 * @param string $file
	 * @param int $ttl	Time to live in seconds
	 * @
	 */
	protected function is_valid_cache($file, $ttl){
		if(file_exists($file) && (filemtime($file) > (time() - $ttl))){
			return TRUE;
		}else{
			return FALSE;	
		}
	}
	
	/**
	 * Add shortcode
	 * 
	 * The callback method must have 2 parameters
	 * the first parameter are the attributes and the second the content
	 * 
	 * @param string $shortcode
	 * @param array $method
	 * @return void
	 */
	protected function add_shortcode($shortcode, $call){
		add_shortcode($shortcode, $call);
	}
	
	/**
	 * Add an options page
	 * 
	 * @param string $page_title The page title
	 * @param string $menu_title The text to use as menu link
	 * @param int $user_level Use the USER_LEVEL constants
	 * @param string $menu_slug The string used in the url
	 * @param array $function The function to call when the menu link is clicked
	 * @return void
	 */
	protected function add_options_page($page_title, $menu_title, $user_level, $menu_slug, $function){
		return add_options_page($page_title, $menu_title, $user_level, $menu_slug, $function);
	}
	
	/**
	 * Add a new menu
	 * 
	 * @param string $page_title The page title
	 * @param string $menu_title The text to use as menu link
	 * @param int $user_level Use the USER_LEVEL constants
	 * @param string $menu_slug The string used in the url
	 * @param array $function The function to call when the menu link is clicked
	 * @param string $icon_url Url to an icon
	 * @param int $position Menu order
	 * @return void
	 */
	protected function add_menu_page($page_title, $menu_title, $user_level, $menu_slug, $function, $icon_url, $position){
		return add_menu_page($page_title, $menu_title, $user_level, $menu_slug, $function, $icon_url, $position);
	}
	
	/**
	 * Add a sub menu
	 * 
	 * @param string $parent_slug The slug of the parent menu (Use the SLUG_... constants to add to existing menus)
	 * @param string $page_title The page title
	 * @param string $menu_title The text to use as menu link
	 * @param int $user_level Use the USER_LEVEL constants
	 * @param string $menu_slug The string used in the url
	 * @param array $function The function to call when the menu link is clicked
	 * @return void
	 */
	protected function add_submenu_page($parent_slug, $page_title, $menu_title, $user_level, $menu_slug, $function){
		return add_submenu_page($parent_slug, $page_title, $menu_title, $user_level, $menu_slug, $function);
	}
	
	/**
	 * Parse a view file
	 * 
	 * @param string $file	The view file you want to use
	 * @param array $data	Data that you want to use in the template
	 * @return void
	 */
	protected function load_view($file, $data=array()){
		
		// Make variables
		extract( $data );
		
		// Check if template exists
		if( file_exists($file) ){
			ob_start();
			include($file);
			$parsed = ob_get_contents();
			ob_end_clean();
			echo $parsed;
		} else {
			throw new Exception('Template file doesn\'t exist');
		}
	}
	
	/**
	 * Add a validator to a specific form field
	 * 
	 * @param string $field Form field to apply the validator to
	 * @param string $validator The validator to use. If custom validator for example "age" than create method "validator_age".
	 * @param array $params Optitionaly some parameters for the validator
	 * @return bool
	 */
	protected function add_validator($field, $validator, $params=array()){
		
		// Check if validator is callable
		
		// Add to validator array
		
	}
	
	/**
	 * VALIDATOR REQUIRED
	 * Check if a field is filled in
	 * 
	 * @param string $value
	 * @return bool
	 */
	protected function validator_required($value){
		$trim_value = trim($value);
		return !empty($trim_value) ? true : false;
	}
	
	/**
	 * VALIDATOR NUMERIC
	 * Check if a field is numeric
	 * Optional you can check if it is between a min and a max
	 * 
	 * @param int $value
	 * @param int $max
	 * @param int $min
	 * @return bool
	 */
	protected function validator_numeric($value, $max=null, $min=0){
		if(!is_numeric($value)) return false;
		if(is_numeric($min) && $value < $min) return false;
		if(is_numeric($max) && $value >= $max) return false;
		return true;
	}
	
	/**
	 * VALIDATOR EMAIL
	 * Check if it's a valid email address
	 * 
	 * @param string $string
	 * @return bool
	 */
	protected function validator_email($string){
		return (bool)preg_match("/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i", $string);
	}
	
	/**
	 * VALIDATOR URL
	 * Check if it's a valid url address
	 * 
	 * @param string $string
	 * @return bool
	 */
	protected function validator_url($string){
		return (bool)preg_match("/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i", $string); 
	}

	/**
	 * VALIDATOR IP
	 * Check if it's a valid ip address
	 * 
	 * @param string $string
	 * @return bool
	 */
	protected function validator_ip($string){
		return (bool)preg_match("/^(1?\d{1,2}|2([0-4]\d|5[0-5]))(\.(1?\d{1,2}|2([0-4]\d|5[0-5]))){3}$/", $string);
	}
	
	/**
	 * Clean up a string to show on the screen.
	 * This can be used to secure your plugins against XSS and other security bugs
	 * 
	 * I use wp_specialchars instead of htmlspecialchars
	 * htmlspecialchars will double encode html entities if run twice
	 * 
	 * @param string $value
	 * @return string
	 */
	protected function clean_display_string($value){
		return stripslashes(wp_specialchars($value));
	}
	
	/**
	 * The same as clean_display_string but applies this
	 * to all values in the array
	 * 
	 * @param array $values
	 * @return array
	 */
	protected function clean_display_array($values){
		return array_map(array(&$this, 'clean_display_string'), $values);
	}
	
	/**
	 * Handle forms
	 * The first thing this method does is check if the submitted values are valid
	 * If not it returns an array with invalid fields
	 * else it calls the correct form handler base on the form_handler array
	 * 
	 * @return array
	 */
	protected function handle_form(){
		
		// Validate form fields
		$validation_results = $this->validate_fields($_POST, $this->form_field_validators);

		// Get the form handler to use for this form
		$current_form_handler = ($this->get_current_form_handler());
		
		// Call form handler method if there is one defined and form is valid
		// Else return the validation result
		if($this->is_form_valid($validation_results) && isset($current_form_handler)){
			if(is_callable($current_form_handler, false)){
				call_user_func($current_form_handler);
			}else{
				throw new Exception('Form handler method "' . $current_form_handler[1] . '" is not callable.');
			}
		}else{
			return $validation_results;
		} 
		
	}
	
	/**
	 * Call validation methods for the coresponding post data fields
	 *
	 * @param array $data
	 * @param array $validators
	 * @return array
	 */
	private function validate_fields($data, $validators){
		$results = array();
		foreach($data as $key => $value){
			if(isset($validators[$key]) && count($validators[$key]) > 0){
				$results[$key] = "";
				foreach($validators[$key] as $validator){
					$validator_name = "validator_" . strtolower($validator);
					if(method_exists($this, $validator_name)){
						$valid = $this->$validator_name($value);
						if(!$valid){
							$results[$key] = " form-invalid";
							break;
						}
					}
				}
			}
		}
		return $results;
	}
	
	/**
	 * Check based on the result array of validateFields if
	 * a form is valid or not
	 *
	 * @param array $validation_results
	 * @return bool
	 */
	private function is_form_valid($validation_results){
		foreach($validation_results as $key => $value){
			if($value != ""){
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Get the form handler for this form based on the form_handler array
	 * 
	 * @return array
	 */
	private function get_current_form_handler(){
		foreach($_POST as $key => $value){
			if(isset($this->form_handler[$key])) return $this->form_handler[$key];
		}
	}
	
	/**
	 * Load a js script files
	 * 
	 * @param string $handle Name of the script. Lowercase string
	 * @param string $src Url to the script. Example $this->plugin_url . '/js/file.js'
	 * @param array $deps Array of files where this script depends on. For example array('jquery')
	 * @param string $ver Specify script version. This solves caching problems when you changes things in your code
	 * @param bool $in_footer Normally scripts are placed in the head, if this is set to true it will be placed at the bottom of the <body>.
	 * @return void
	 */
	protected function enqueue_script($handle, $src=null, $deps=null, $ver='1.0', $in_footer=true ){
		wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
	}
	
	/**
	 * Load a css stylesheet
	 * 
	 * @param string $handle Name of the stylesheet
	 * @param string $src Url to the stylesheet. Example $this->plugin_url . '/css/file.css'
	 * @param array $deps Array of files where this style depends on. For example array(handle_name)
	 * @param string $ver Specify stylesheet version. This solves caching problems when you changes things in your code
	 * @param string $media Media type for your stylesheet
	 * @return void
	 */
	protected function enqueue_style($handle, $src=null, $deps=null, $ver='1.0', $media=self::MEDIA_ALL){
		wp_enqueue_style($handle, $src, $deps, $ver, $media);
	}
	
	/**
	 * Init action 
	 * Load the text domain
	 * enables translations for plugin
	 * 
	 * @return void
	 */
	public function add_textdomain(){
		load_plugin_textdomain( $this->plugin_name, false, $this->plugin_name . '/languages/' );
	}
	
	public function shortcode($atts){
		$class = str_replace("_main", "", get_class($this));
		$args['widget_id'] = WB_WidgetHelper::generate_widget_id($class);
		$args['widget_name'] = str_replace("_", " ", $class);
		$args['before_widget'] = '<div id="' . $args['widget_id'] . '" class="shortcode_' . strtolower($class) . ' wb-container">';
		$args['after_widget'] = '</div>';
		$args['before_title'] = '<h3>';
		$args['after_title'] = '</h3>';
		
		// Convert Yes checkbox value
		foreach($atts as $key => $value){
			if(!strcmp($value, 'true') || $value == 'yes'){
				$atts[$key] = 'Yes';
			} 
		}
		
		// Buffer output and return
		ob_start();
		$this->widget($args, $atts);
		$content = ob_get_contents();
		ob_end_clean();
		
		return $content;
	}
	
	/** 
	 * Overwritable functions
	 * The reason we create empty method for these actions/filters
	 * is code completitions
	 */
	public function action_muplugins_loaded(){}
	public function action_plugins_loaded(){}
	public function action_sanitize_commmnt_cookies(){}
	public function action_setup_theme(){}
	public function action_load_textdomain(){}
	public function action_after_setup_theme(){}
	public function action_auth_cookie_valid(){}
	public function action_set_current_user(){}
	public function action_init(){}
	public function action_widgets_init(){}
	public function action_register_sidebar(){}
	public function action_wp_register_sidebar_widget(){}
	public function action_wp_loaded(){}
	public function action_auth_redirect(){}
	public function action_wp_default_scripts($params){}
	public function action_admin_menu(){}
	public function action_admin_init(){}
	public function action_parse_request($params){}
	public function action_send_headers($wp_obj){}
	public function action_parse_query($params){}
	public function action_pre_get_posts($query_obj){}
	public function action_posts_selection(){}
	public function action_wp($wp_obj){}
	public function action_admin_xml_ns(){}
	public function action_wp_default_styles($params){}
	public function action_admin_enqueue_scripts(){}
	public function action_admin_print_styles(){}
	public function action_admin_print_scripts(){}
	public function action_wp_print_styles(){}
	public function action_wp_print_scripts(){}
	public function action_admin_head(){}
	public function action_in_admin_header(){}
	public function action_adminmenu(){}
	public function action_admin_notices(){}
	public function action_restrict_manage_posts(){}
	public function action_the_post($params){}
	public function action_in_admin_footer(){}
	public function action_admin_footer(){}
	public function action_admin_print_footer_scripts(){}
	public function action_wp_print_footer_scripts(){}
	public function action_shutdown(){}
	public function action_add_attachment($attachment_id){}
	public function action_clean_post_cache($post_id){}
	public function action_create_category($category_id){}
	public function action_delete_attachment($attachement_id){}
	public function action_delete_category($category_id){}
	public function action_delete_post($postpage_id){}
	public function action_deleted_post($postpage_id){}
	public function action_edit_attachment($attachment_id){}
	public function action_edit_category($category_id){}
	public function action_edit_post($postpage_id){}
	public function action_pre_post_update($post_id){}
	public function action_private_to_publish($post_obj){}
	public function action_publish_page($page_id){}
	public function action_publish_phone($post_id){}
	public function action_publish_post($post_id){}
	public function action_save_post($postpage_id){}
	public function action_xmlrpc_publish_post($post_id){}
	public function action_comment_closed($post_id){}
	public function action_comment_id_not_found($post_id){}
	public function action_comment_flood_trigger($time_prev_comment, $time_curr_comment){}
	public function action_comment_on_draft($post_id){}
	public function action_comment_post($comment_id, $status){}
	public function action_edit_comment($comment_id){}
	public function action_delete_comment($comment_id){}
	public function action_pingback_post($comment_id){}
	public function action_pre_ping(array $links, $pung){}
	public function action_trackback_post($comment_id){}
	public function action_wp_insert_post($post_id, $post=null){}
	
	/**
	 * Runs to check whether a comment should be blacklisted. 
	 * wp_die to reject the comment.
	 */
	public function action_wp_blacklist_check($author, $email, $url, $text, $ip, $user_agent){}
	public function action_wp_set_comment_status($status){}
	public function action_add_link($line_id){}
	public function action_delete_link($link_id){}
	public function action_edit_link($link_id){}
	public function action_atom_entry(){}
	public function action_atom_head(){}
	public function action_atom_ns(){}
	public function action_commentrss2_item(){}
	public function action_do_feed_rss2(){}
	public function action_do_feed_atom(){}
	public function action_do_feed_rdf(){}
	public function action_rdf_header(){}
	public function action_rdf_item(){}
	public function action_rdf_ns(){}
	public function action_rss_head(){}
	public function action_rss_item(){}
	public function action_rss2_head(){}
	public function action_rss2_item(){}
	public function action_rss2_ns(){}
	public function action_comment_form($post_id){}
	public function action_do_robots(){}
	public function action_do_robotstxt(){}
	public function action_get_footer(){}
	public function action_get_header(){}
	public function action_switch_theme($theme_name){}
	public function action_template_redirect(){}
	public function action_wp_footer(){}
	public function action_wp_head(){}
	public function action_wp_meta(){}
	public function action_activity_box_end(){}
	public function action_add_category_form_pre(){}
	public function action_check_passwords (){}
	public function action_dbx_page_advanced (){}
	public function action_dbx_page_sidebar (){}
	public function action_dbx_post_advanced (){}
	public function action_dbx_post_sidebar (){}
	public function action_delete_user($user_id){}
	public function action_edit_category_form (){}
	public function action_edit_category_form_pre(){}
	public function action_edit_tag_form(){}
	public function action_edit_tag_form_pre(){}
	public function action_edit_form_advanced(){}
	public function action_edit_page_form(){}
	public function action_edit_user_profile(){}
	public function action_login_form(){}
	public function action_login_head(){}
	public function action_lost_password(){}
	public function action_lostpassword_form(){}
	public function action_lostpassword_post(){}
	public function action_manage_link_custom_column(){}
	public function action_manage_posts_custom_column(){}
	public function action_manage_pages_custom_column(){}
	public function action_password_reset(){}
	public function action_personal_options_update(){}
	public function action_profile_personal_options (){}
	public function action_profile_update($user_id){}
	public function action_register_form(){}
	public function action_register_post(){}
	public function action_retrieve_password(){}
	public function action_show_user_profile(){}
	public function action_simple_edit_form (){}
	public function action_user_register(){}
	public function action_wp_authenticate(array $data){}
	public function action_wp_login(){}
	public function action_wp_logout(){}
	public function action_right_now_content_table_end(){}
	public function action_right_now_table_end(){}
	public function action_right_now_discussion_table_end(){}
	public function action_right_now_end(){}
	public function action_blog_privacy_selector(){}
	public function action_check_admin_referer(){}
	public function action_check_ajax_referer(){}
	public function action_generate_rewrite_rules($wp_rewrite){}
	public function action_loop_end(){}
	public function action_loop_start(){}
	public function action_sanitize_comment_cookies(){}
	
	
	public function filter_plugin_action_links($links){}
	public function filter_attachment_fields_to_edit(array $form_fields, $post_obj){}
	public function filter_attachment_icon($img_tag, $attachment_id){}
	public function filter_attachment_innerHTML($inner_html, $attachment_id){}
	public function filter_content_edit_pre(){}
	public function filter_excerpt_edit_pre(){}
	public function filter_get_attached_file($file_information, $attachment_id){}
	public function filter_get_enclosed(){}
	public function filter_get_pages($pages){}
	public function filter_get_pung(){}
	public function filter_get_the_excerpt(){}
	public function filter_get_the_guid(){}
	public function filter_get_to_ping(){}
	public function filter_icon_dir(){}
	public function filter_icon_dir_uri(){}
	public function filter_prepend_attachment(){}
	public function filter_sanitize_title(){}
	public function filter_single_post_title(){}
	public function filter_the_content(){}
	public function filter_the_content_rss(){}
	public function filter_the_editor_content(){}
	public function filter_the_excerpt(){}
	public function filter_the_excerpt_rss(){}
	public function filter_the_tags(){}
	public function filter_the_title($title){}
	public function filter_the_title_rss(){}
	public function filter_title_edit_pre(){}
	public function filter_wp_dropdown_pages(){}
	public function filter_wp_list_pages(){}
	public function filter_wp_list_pages_excludes(){}
	public function filter_wp_get_attachment_metadata(){}
	public function filter_wp_get_attachment_thumb_file($thumb_file, $attachment_id){}
	public function filter_wp_get_attachment_thumb_url($thumb_file, $attchment_id){}
	public function filter_wp_get_attachment_url($url, $attachment_id){}
	public function filter_wp_mime_type_icon($icon_uri, $mime, $post_id){}
	public function filter_wp_title(){}
	public function filter_add_ping(){}
	public function filter_attachment_fields_to_save($post_attributes, $attachment_fields){}
	public function filter_attachment_max_dims(){}
	public function filter_category_save_pre(){}
	public function filter_comment_status_pre(){}
	public function filter_content_filtered_save_pre(){}
	public function filter_content_save_pre(){}
	public function filter_excerpt_save_pre(){}
	public function filter_name_save_pre (){}
	public function filter_phone_content(){}
	public function filter_ping_status_pre(){}
	public function filter_post_mime_type_pre(){}
	public function filter_status_save_pre(){}
	public function filter_thumbnail_filename(){}
	public function filter_wp_thumbnail_creation_size_limit($max_file_size, $attachment_id, $attachment_file_name){}
	public function filter_wp_thumbnail_max_side_length($image_side_max_size, $attachment_id, $attachment_file_name){}
	public function filter_title_save_pre(){}
	public function filter_update_attached_file($attachment_info, $attachment_id){}
	public function filter_wp_delete_file(){}
	public function filter_wp_generate_attachment_metadata(){}
	public function filter_wp_update_attachment_metadata($meta_data, $attachment_id){}
	public function filter_comment_excerpt(){}
	public function filter_comment_post_redirect($location, $comment_info){}
	public function filter_comment_text(){}
	public function filter_comment_text_rss(){}
	public function filter_comments_array($comment_info, $post_id){}
	public function filter_comments_number(){}
	public function filter_get_comment_excerpt(){}
	public function filter_get_comment_ID(){}
	public function filter_get_comment_text(){}
	public function filter_get_comment_type(){}
	public function filter_get_comments_number(){}
	public function filter_post_comments_feed_link(){}
	public function filter_comment_save_pre($comment_data, $author, $email, $url, $content, $type, $user_id){}
	public function filter_pre_comment_approved(){}
	public function filter_pre_comment_content(){}
	public function filter_wp_insert_post_data($data, $post){}
	public function filter_category_description($description, $category_id, $description, array $category){}
	public function filter_category_feed_link(){}
	public function filter_category_link($link_url, $category_id){}
	public function filter_get_categories($category_list){}
	public function filter_get_category(){}
	public function filter_list_cats(){}
	public function filter_list_cats_exclusions(){}
	public function filter_single_cat_title(){}
	public function filter_the_category($list, $separator=''){}
	public function filter_the_category_rss(){}
	public function filter_wp_dropdown_cats(){}
	public function filter_wp_list_categories(){}
	public function filter_pre_category_description(){}
	public function filter_pre_category_name(){}
	public function filter_pre_category_nicename(){}
	public function filter_attachment_link($link_url, $attachment_id){}
	public function filter_author_feed_link(){}
	public function filter_author_link($url, $author_name, $author_id){}
	public function filter_comment_reply_link($url, $custom_options, $comment_obj, $post_obj){}
	public function filter_day_link($url, $year, $month, $day){}
	public function filter_feed_link($url, $type){}
	public function filter_get_comment_author_link($username){}
	public function filter_get_comment_author_url_link(){}
	public function filter_month_link($url, $year, $month){}
	public function filter_page_link($url, $page_id){}
	public function filter_post_link($url, $post_data){}
	public function filter_the_permalink(){}
	public function filter_year_link($url, $year){}
	public function filter_tag_link($url, $tag_id){}
	public function filter_get_comment_date(){}
	public function filter_get_comment_time(){}
	public function filter_get_the_modified_date(){}
	public function filter_get_the_modified_time(){}
	public function filter_get_the_time(){}
	public function filter_the_date(){}
	public function filter_the_modified_date(){}
	public function filter_the_modified_time(){}
	public function filter_the_time(){}
	public function filter_the_weekday(){}
	public function filter_the_weekday_date($weekday_text, $before_text, $after_text){}
	public function filter_login_redirect(){}
	public function filter_author_email(){}
	public function filter_comment_author(){}
	public function filter_comment_author_rss(){}
	public function filter_comment_email(){}
	public function filter_comment_url(){}
	public function filter_get_comment_author(){}
	public function filter_get_comment_author_email(){}
	public function filter_get_comment_author_IP(){}
	public function filter_get_comment_author_url(){}
	public function filter_login_errors(){}
	public function filter_login_headertitle(){}
	public function filter_login_headerurl(){}
	public function filter_login_message(){}
	public function filter_role_has_cap(){}
	public function filter_sanitize_user($username, $raw_username, $strict){}
	public function filter_the_author(){}
	public function filter_the_author_email(){}
	public function filter_pre_comment_author_email(){}
	public function filter_pre_comment_author_name(){}
	public function filter_pre_comment_author_url(){}
	public function filter_pre_comment_user_agent(){}
	public function filter_pre_comment_user_ip(){}
	public function filter_pre_user_id(){}
	public function filter_pre_user_description(){}
	public function filter_pre_user_display_name(){}
	public function filter_pre_user_email(){}
	public function filter_pre_user_first_name(){}
	public function filter_pre_user_last_name(){}
	public function filter_pre_user_login(){}
	public function filter_pre_user_nicename(){}
	public function filter_pre_user_nickname(){}
	public function filter_pre_user_url(){}
	public function filter_registration_errors(){}
	public function filter_user_registration_email(){}
	public function filter_validate_username($valid, $username){}
	public function filter_get_bookmarks($query_result, $arguments){}
	public function filter_link_category(){}
	public function filter_link_description(){}
	public function filter_link_rating(){}
	public function filter_link_title(){}
	public function filter_pre_link_description(){}
	public function filter_pre_link_image(){}
	public function filter_pre_link_name(){}
	public function filter_pre_link_notes(){}
	public function filter_pre_link_rel(){}
	public function filter_pre_link_rss(){}
	public function filter_pre_link_target(){}
	public function filter_pre_link_url(){}
	public function filter_all_options(){}
	public function filter_bloginfo($info, $show){}
	public function filter_bloginfo_rss($info, $show){}
	public function filter_bloginfo_url(){}
	public function filter_loginout(){}
	public function filter_register(){}
	public function filter_upload_dir($dir, $url, $error=false){}
	public function filter_upload_mimes($mimes){}
	public function filter_attribute_escape(){}
	public function filter_js_escape(){}
	public function filter_autosave_interval(){}
	public function filter_cat_rows(){}
	public function filter_comment_edit_pre(){}
	public function filter_comment_edit_redirect($location, $commend_id){}
	public function filter_comment_moderation_subject($mail_subject, $comment_id){}
	public function filter_comment_moderation_text($mail_body_text, $comment_id){}
	public function filter_comment_notification_headers($mail_header_text, $comment_id){}
	public function filter_comment_notification_subject($mail_subject, $comment_id){}
	public function filter_comment_notification_text($mail_body_text, $comment_id){}
	public function filter_cron_schedules(){}
	public function filter_custom_menu_order(){}
	public function filter_default_content(){}
	public function filter_default_excerpt(){}
	public function filter_default_title(){}
	public function filter_editable_slug(){}
	public function filter_format_to_edit(){}
	public function filter_format_to_post(){}
	public function filter_manage_posts_columns(){}
	public function filter_manage_pages_columns(){}
	public function filter_menu_order(){}
	public function filter_postmeta_form_limit(){}
	public function filter_pre_upload_error(){}
	public function filter_preview_page_link(){}
	public function filter_preview_post_link(){}
	public function filter_richedit_pre(){}
	public function filter_show_password_fields(){}
	public function filter_the_editor($value){}
	public function filter_user_can_richedit($bool){}
	public function filter_user_has_cap(){}
	public function filter_wp_handle_upload($info, $url, $type){}
	public function filter_wp_upload_tabs(){}
	public function filter_mce_spellchecker_languages(){}
	public function filter_mce_css(){}
	public function filter_mce_external_plugins(){}
	public function filter_mce_external_languages(){}
	public function filter_tiny_mce_before_init(){}
	public function filter_locale_stylesheet_uri($uri, $style_dir_uri){}
	public function filter_stylesheet(){}
	public function filter_stylesheet_directory($dir, $stylesheet){}
	public function filter_stylesheet_directory_uri($style_dir_uri, $stylesheet){}
	public function filter_stylesheet_uri($style_uri, $stylesheet){}
	public function filter_template(){}
	public function filter_template_directory($template_dir, $template){}
	public function filter_template_directory_uri($template_dir_uri, $template){}
	public function filter_theme_root(){}
	public function filter_theme_root_uri(){}
	public function filter_404_template(){}
	public function filter_archive_template(){}
	public function filter_attachment_template(){}
	public function filter_author_template(){}
	public function filter_category_template(){}
	public function filter_comments_popup_template(){}
	public function filter_comments_template(){}
	public function filter_date_template(){}
	public function filter_home_template(){}
	public function filter_page_template(){}
	public function filter_paged_template(){}
	public function filter_search_template(){}
	public function filter_single_template(){}
	public function filter_template_include(){}
	public function filter_allowed_redirect_hosts(){}
	public function filter_author_rewrite_rules(){}
	public function filter_category_rewrite_rules(){}
	public function filter_comments_rewrite_rules(){}
	public function filter_create_user_query(){}
	public function filter_date_rewrite_rules(){}
	public function filter_found_posts(){}
	public function filter_found_posts_query(){}
	public function filter_get_editable_authors(){}
	public function filter_gettext($translated_text, $untranslated_text){}
	public function filter_override_load_textdomain(){}
	public function filter_get_next_post_join(){}
	public function filter_get_next_post_sort(){}
	public function filter_get_next_post_where(){}
	public function filter_get_others_drafts(){}
	public function filter_get_previous_post_join(){}
	public function filter_get_previous_post_sort(){}
	public function filter_get_previous_post_where(){}
	public function filter_get_users_drafts(){}
	public function filter_locale(){}
	public function filter_mod_rewrite_rules(){}
	public function filter_post_limits(){}
	public function filter_posts_distinct(){}
	public function filter_posts_fields(){}
	public function filter_posts_groupby(){}
	public function filter_posts_join_paged(){}
	public function filter_posts_orderby(){}
	public function filter_posts_request(){}
	public function filter_post_rewrite_rules(){}
	public function filter_root_rewrite_rules(){}
	public function filter_page_rewrite_rules(){}
	public function filter_posts_where_paged(){}
	public function filter_posts_join(){}
	public function filter_posts_where(){}
	public function filter_query(){}
	public function filter_query_string(){}
	public function filter_query_vars(){}
	public function filter_request(){}
	public function filter_rewrite_rules_array(){}
	public function filter_search_rewrite_rules(){}
	public function filter_the_posts(){}
	public function filter_excerpt_length(){}
	public function filter_excerpt_more(){}
	public function filter_post_edit_form_tag(){}
	public function filter_update_user_query(){}
	public function filter_wp_redirect($url, $http_code){}
	public function filter_xmlrpc_methods(){}
	public function filter_wp_mail_from(){}
	public function filter_wp_mail_from_name(){}
	public function filter_pre_update_option_active_plugins(){}
	public function activate(){}
	public function deactivate(){}
}
}