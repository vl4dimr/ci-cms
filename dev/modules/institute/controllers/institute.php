<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Institute extends Controller {

	function Institute()
	{
		parent::Controller();
	

		$this->template['module']	= 'institute';
		$this->load->model('institute_model', 'institute');
		
	}


	function index()
	{
		$this->user->require_login();
		/**
		aseho ny
		- Edit profile na create profile
		- Aseho ny lisitry ny Classes misy hoe Enter Class - Modify - Delete
		- Aseho ny bouton Register a class
		*/
		
		$this->template['title'] = __("Your area", "institute");
		
		$this->template['classes'] = false;
		
		if($this->template['student'] = $this->institute->get_student())
		{
			// only needed for profiled student
			$this->template['classes'] = $this->institute->get_classes();
		}
		
		$this->layout->load($this->template, 'home');
	}
	
	function myclass($action = 'register', $class_id = null)
	{
		$this->user->require_login();
		switch ($action)
		{
			case 'modify':
			
			break;
			case 'delete':
			
			break;
			default:
			case 'register':
			
			break;
		}
	}
	
}