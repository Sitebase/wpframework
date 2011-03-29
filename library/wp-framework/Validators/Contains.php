<?php

/**
 * Input must contain a specific string
 * 
 * @author 		Sitebase (Wim Mostmans)
 * @copyright  	Copyright (c) 2011, Sitebase (http://www.sitebase.be)
 * @license    	http://www.opensource.org/licenses/bsd-license.php    BSD License
 */
class WpFramework_Validators_Contains extends WpFramework_Validators_Abstract
{
			
	private $_contain;
	
	/**
	 * Constructor
	 *
	 * @param string $message
	 * @param string $contain
	 */
	public function __construct($message, $contain=""){
		$this->failMessage 	= $message;
		$this->_contain 	= $contain;
	}
	
	/**
	 * Validate this element
	 *
	 * @access public
	 * @param int $var
	 * @return bool
	 */
	public function validate($var){
		return strstr(strtolower($var), $this->_contain);
	}
		
}