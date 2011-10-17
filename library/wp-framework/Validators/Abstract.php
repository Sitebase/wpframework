<?php

/**
 * Base validator class
 */
abstract class WpFramework_Validators_Abstract
{
	
	/**
	 * Fail message
	 * @var string
	 */
	protected $failMessage;
	
	/**
	 * Get fail message
	 *
	 * @access public
	 * @return string
	 */
	public function getMessage(){
		return $this->failMessage;
	}
	
}