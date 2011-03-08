<?php

/**
 * Check if input is an IP address
 * 
 * @author 		Sitebase (Wim Mostmans)
 * @copyright  	Copyright (c) 2011, Sitebase (http://www.sitebase.be)
 * @license    	http://www.opensource.org/licenses/bsd-license.php    BSD License
 */
class WpFramework_Validators_Ip extends WpFramework_Validators_Abstract
{
	
	/**
	 * Constructor
	 *
	 * @param string $message
	 */
	public function __construct($message){
		$this->failMessage = $message;
	}
	
	/**
	 * Validate this element
	 *
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public function validate($string){
		return (bool)preg_match("/^(1?\d{1,2}|2([0-4]\d|5[0-5]))(\.(1?\d{1,2}|2([0-4]\d|5[0-5]))){3}$/", $string);
	}
	
}