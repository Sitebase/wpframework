<?php

/**
 * Check if input is an url
 * 
 * @author 		Sitebase (Wim Mostmans)
 * @copyright  	Copyright (c) 2011, Sitebase (http://www.sitebase.be)
 * @license    	http://www.opensource.org/licenses/bsd-license.php    BSD License
 */
class WpFramework_Validators_FileSize extends WpFramework_Validators_Abstract {
	
	private $_max_size = null;
	private $_min_size = null;
	
	/**
	 * Constructor
	 *
	 * @param string $message
	 */
	public function __construct($message, $max_bytes_size=null, $min_bytes_size=null){
		$this->failMessage = $message;
		$this->_max_size = $max_bytes_size;
		$this->_min_size = $min_bytes_size;
	}
	
	/**
	 * Validate this element
	 *
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public function validate($file){
		if(isset($this->_max_size) && $file['size'] > $this->_max_size) return false; 
		if(isset($this->_min_size) && $file['size'] < $this->_min_size) return false; 
		return true;
	}
	
}