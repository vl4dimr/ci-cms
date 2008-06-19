<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Modules extends Controller {
		
		function Modules()
		{

			parent::Controller();
			
			$this->load->library('administration');
			
			$this->template['module'] = "admin";
		}
		
		function index()
		{
			

			
			$this->load->view('module_list');
		}
		

	}

?>