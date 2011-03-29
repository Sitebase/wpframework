<?php

/**
 * Check if input is a number
 * 
 * @author 		Sitebase (Wim Mostmans)
 * @copyright  	Copyright (c) 2011, Sitebase (http://www.sitebase.be)
 * @license    	http://www.opensource.org/licenses/bsd-license.php    BSD License
 */
class WpFramework_Validators_Integer extends WpFramework_Validators_Abstract
{
			
	private $_min;
	private $_max;
	
	/**
	 * Constructor
	 *
	 * @param string $message
	 * @param int $min
	 * @param int $max
	 */
	public function __construct($message, $min=null, $max=null){
		$this->failMessage 		= $message;
		$this->_min 			= $min;
		$this->_max 			= $max;
	}
	
	/**
	 * Validate this element
	 *
	 * @access public
	 * @param int $var
	 * @return bool
	 */
	public function validate($var){
		
		$valid = TRUE;
		
		if(is_numeric($this->_min) && $this->_min > $var){
			$valid = FALSE;
		}
		
		if(is_numeric($this->_max) && $this->_max < $var){
			$valid = FALSE;
		}
		
		if(!is_numeric($var)){
			$valid = FALSE;
		}
		
		return $valid;
	}
	
}