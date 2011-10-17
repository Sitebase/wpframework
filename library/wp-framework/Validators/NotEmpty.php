<?php

/**
 * Input is required
 * 
 * @author 		Sitebase (Wim Mostmans)
 * @copyright  	Copyright (c) 2011, Sitebase (http://www.sitebase.be)
 * @license    	http://www.opensource.org/licenses/bsd-license.php    BSD License
 */
class WpFramework_Validators_NotEmpty extends WpFramework_Validators_Abstract
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
		$trim_value = trim($string);
        // Not use empty() because in that case 0 is also invalid
		return !($trim_value == "");
	}
		
}