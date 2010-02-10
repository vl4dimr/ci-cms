<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Test extends Controller {
		
		
		function Test()
		{

			parent::Controller();
			
			$this->load->library('administration');
		}
		
		function index()
		{
			var_dump(format_title("Inona izao no mety Hasehon'ity?"));
		}


	}

?>