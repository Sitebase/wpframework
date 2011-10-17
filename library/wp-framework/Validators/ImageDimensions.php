<?php

/**
 * Check if input is an url
 * 
 * @author 		Sitebase (Wim Mostmans)
 * @copyright  	Copyright (c) 2011, Sitebase (http://www.sitebase.be)
 * @license    	http://www.opensource.org/licenses/bsd-license.php    BSD License
 */
class WpFramework_Validators_ImageDimensions extends WpFramework_Validators_Abstract
{
	
	private $_min_width = null;
	private $_min_height = null;
	private $_max_width = null;
	private $_max_height = null;
	
	/**
	 * Constructor
	 *
	 * @param string $message
	 */
	public function __construct($message, $max_width=null, $max_height=null, $min_width=null, $min_height=null){
		$this->failMessage = $message;
		$this->_min_height = $min_height;
		$this->_min_width = $min_width;
		$this->_max_height = $max_height;
		$this->_max_width = $max_width;
	}
	
	/**
	 * Validate this element
	 *
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public function validate($image){
		list($width, $height, $type, $attr) = getimagesize($image['tmp_name']);
		if(isset($this->_max_width) && $width > $this->_max_width) return false;
		if(isset($this->_max_height) && $height > $this->_max_height) return false;
		if(isset($this->_min_width) && $width < $this->_min_width) return false;
		if(isset($this->_min_height) && $height < $this->_min_height) return false;
		return true;
	}
	
}