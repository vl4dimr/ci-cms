<?php  
/*
$Id$
*/


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contacts extends Controller {
	var $settings = array();
	function Contacts()
	{
		parent::Controller();
		$this->template['module'] = "contacts";
		
	}
	
	function index()
	{

	}
}

?>