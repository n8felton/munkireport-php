<?php 

/**
 * Ard_controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Ard_controller extends Module_controller
{
	function __construct()
	{
		$this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 *
	 * @author AvB
	 **/
	function index()
	{
		echo "You've loaded the disk report module!";
	}

} // END class Ard_controller