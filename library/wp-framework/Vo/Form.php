<?php 

/**
 * One of the reasons for creating a form object
 * is that when you get the submitted value from an input field
 * you will net get undefined variable notices.
 *
 * @author 		Sitebase (Wim Mostmans)
 * @copyright  	Copyright (c) 2011, Sitebase (http://www.sitebase.be)
 * @license    	http://www.opensource.org/licenses/bsd-license.php    BSD License
 */
class WpFramework_Vo_Form {
	
	/**
	 * Results after validation is triggered
	 * @var array
	 */
	private $_errors = array();
	
	/**
	 * The POST data
	 * @var array
	 */
	private $_data = array();
	
	/**
	 * Saved
	 * Set if the form is just saved
	 * @var bool
	 */
	private $_saved = false;
	
	/**
	 * Constructor
	 */
	public function __construct($data=null, $errors=null) {
		if(isset($data)) $this->_data = $data;
		if(isset($errors)) $this->_errors = $errors;
	}
	
	/**
	 * Set errors
	 * 
	 * @param array $errors
	 * @return void
	 */
	public function setErrors($errors) {
		$this->_errors = $errors;
	}
	
	/**
	 * Get errors
	 * 
	 * @param string $field	Optitional name of field to get the errors from
	 * @param int $limit How many errors to return
	 * @return array/string
	 */
	public function getErrors($field=null, $limit=0){
		
		// Get errors from field or all
		$errors = array();
		if(!isset($field)) {
			foreach($this->_errors as $field => $field_errors){
				$errors = array_merge($errors, $field_errors);
			}
		} else {
			if(!isset($this->_errors[$field])) return false;
			$errors = $this->_errors[$field];
		}
		
		// Limit
		if($limit === 0) return $errors;
		if($limit === 1) return $errors[0];
		return array_slice($errors, $limit);
	}
	
	/**
	 * Check if a field has errors
	 * 
	 * @param string $fieldname
	 * @return bool
	 */
	public function hasErrors($field=null) {
		if(isset($field)) {
			return isset($this->_errors[$field]) && is_array($this->_errors[$field]) ? true : false;
		} else {
			return count($this->_errors) > 0;
		}
	}
	
	/**
	 * Set form data
	 * 
	 * @param array $data
	 * @return void
	 */
	public function setData($data) {
		$this->_data = $data;
	}
	
	/**
	 * Get current field value
	 * This is very handy because this way you wouldn't get any undefined variable notices
	 * 
	 * @todo a sanitized prefix
	 * 
	 * @return void
	 */
	public function getField($name, $sanitize=true) {
		if(isset($this->_data[$name])) {
			return $sanitize ? esc_html($this->_data[$name]) : $this->_data[$name];
		}
		return;
	}
	
	/**
	 * Get all the fields
	 * 
	 * @param $sanitize
	 * @return array
	 */
	public function getFields($sanitize=false) {
		if($sanitize) {
			return array_map('esc_html', $this->_data);
		}
		return $this->_data;
	}
	
	/**
	 * Check if saved
	 * 
	 * @return bool
	 */
	public function isSaved() {
		return $this->_saved;
	}
	
	/**
	 * Set saved 
	 * 
	 * @param bool $bool
	 * @return void
	 */
	public function setSaved($bool) {
		$this->_saved = $bool;
	}
	
}
