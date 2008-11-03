<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Member extends Controller {
	

	function Member()
	{
		parent::Controller();
		$this->template['module']	= 'member';
		$this->_init();
	}
	
	function _init() 
	{
		/*
		* default values
		*/
		if (!isset($this->system->login_signup_enabled)) 
		{
			$this->system->login_signup_enabled = 1;
		}
		
	}
	
	function logout()
	{
		$this->user->logout();
		redirect('member/login');
	}
	
	function login()
	{

		if ( $this->user->logged_in )
		{
			if ($redirect = $this->input->post('redirect'))
			{
				redirect($redirect, 'refresh');
			}
			redirect('member/profile');
		}
		else
		{
			if ( !$this->input->post('submit') )
			{
				$this->layout->load($this->template, 'login');

			}
			else
			{
				if(!$username = $this->input->post('username'))
				{
					$this->session->set_flashdata('notification', __("Please enter your username", $this->template['module']));
					redirect('member/login', 'refresh');
				}
				
				if(!$password = $this->input->post('password'))
				{
					$this->session->set_flashdata('notification', __("Please enter your password", $this->template['module']));
					redirect('member/login', 'refresh');
				}
			
				
				if ($this->user->login($username, $password))
				{
					redirect('member/profile');
				}
				else
				{	
					redirect('member/login', 'refresh');
				}
			}
		}
	}

	function unauthorized($module, $level)
	{
		$this->template['data']  = array('module' => $module, 'level' => $level);
		$this->layout->load($this->template, 'unauthorized');
	}
	
	
	function profile($username = null) 
	{
		if ( !$this->user->logged_in ) 
		{
			redirect('member/login');
			return;
		}
		if ( is_null($username) )
		{
			$this->load->library('validation');
			$rules['password'] = "trim|matches[passconf]";
			$rules['passconf'] = "trim";
			$rules['email'] = "trim|required|valid_email|callback__verify_mail";	

			$this->template['member'] = $this->db->get_where('users', array('username' => $this->user->username));
			$this->validation->set_rules($rules);	

			$fields['password'] = __("password", $this->template['module']);	
			$fields['passconf'] = __("password confirmation", $this->template['module']);	

			$this->validation->set_fields($fields);	
			$this->validation->set_error_delimiters('<p style="color:#900">', '</p>');

			$this->validation->set_message('required', __('The %s field is required'));
			$this->validation->set_message('matches', __('The %s field does not match the %s field'));
							
						
			if ($this->validation->run() == FALSE)
			{
				$this->layout->load($this->template, 'myprofile');
			}
			else
			{
				$data['status'] = $this->input->post('status');

				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}

				$this->user->update($username, $data);
				$this->session->set_flashdata('notification', __("Your profile was saved.", $this->template['module']));
				redirect(site_url());
			
			}				
		
		}
		else
		{
			echo $username;
		}
	}
}