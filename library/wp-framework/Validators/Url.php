<?php

/**
 * Check if input is an url
 * 
 * @author 		Sitebase (Wim Mostmans)
 * @copyright  	Copyright (c) 2011, Sitebase (http://www.sitebase.be)
 * @license    	http://www.opensource.org/licenses/bsd-license.php    BSD License
 */
class WpFramework_Validators_Url extends WpFramework_Validators_Abstract
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
		return (bool)preg_match("/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i", $string); 
	}
	
}