<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Test extends Controller {
		var $settings = array();
		function Test()
		{
			parent::Controller();
			
		
		}
		
		function index()
		{
			var_dump($this);
		}
		
	}

?>