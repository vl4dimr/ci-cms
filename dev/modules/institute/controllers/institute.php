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
		- Aseho ny lisitry ny Classes misy hoe Enter Class - edit - Delete
		- Aseho ny bouton Register a class
		*/
		
		$this->template['title'] = __("Your area", "institute");
		
		$this->template['classes'] = false;
		
		if($this->template['student'] = $this->institute->get_profile())
		{
			// only needed for profiled student
			$this->template['classes'] = $this->institute->get_classes();
		}
		
		$this->layout->load($this->template, 'home');
	}
	
	function myclass($action = 'register', $class_id = null)
	{
		$this->user->require_login();
		if(!$this->institute->get_profile())
		{
			$this->session->set_flashdata('notification', __("Please fill your profile before registering a class", "institute"));
			redirect('institute/profile/create');
			return;
		}
		switch ($action)
		{
			case 'edit':
			
			break;
			case 'delete':
			
			break;
			default:
			case 'register':
				
			break;
		}
	}
	
	function profile($action = 'edit', $class_id = null)
	{
		$this->user->require_login();
		switch ($action)
		{
			case 'edit':
				if($this->template['profile'] = $this->institute->get_profile())
				{
					$this->template['title'] = __("Edit your profile", "institute");
					$this->layout->load($this->template, 'profile/edit');
				}
				else
				{
					redirect('institute/profile/create');
				}
			break;
			case 'create':
				$this->template['title'] = __("Create your profile", "institute");
				$this->template['profile'] = $this->institute->fields['institute_profiles'];
				$this->layout->load($this->template, 'profile/edit');
				
			break;
			case 'save':
				if($this->input->post('submit'))
				{
					$data = array();
					foreach($this->institute->fields['institute_profiles'] as $key => $val)
					{
						if($this->input->post($key) !== false)
						{
							$data[$key] = $this->input->post($key);
						}
						else
						{
							$data[$key] = $val;
						}
					}
					$this->institute->save_profile($data);
					$this->session->set_flashdata('notification', __("Your profile was saved", "institute"));
					redirect('institute');
				}
				else
				{
					redirect('institute/profile/create');
				}
			break;
		}
	}
	
	
}