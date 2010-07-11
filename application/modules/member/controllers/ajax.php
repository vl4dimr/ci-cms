<?php
/*
 * $Id: document.php 1070 2008-11-18 06:26:42Z hery $
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends Controller {

	function Ajax()
	{
		parent::Controller();
	

		$this->template['module']	= 'membre';
	}

	function login()
	{
		if(!$username = $this->input->post('username'))
		{
			$data['message'] = __("Username is required", $this->template['module']);
			$data['status'] = 0;
			$this->output->set_header("Content-type: text/xml");
			$this->load->view('ajax', array('data' => $data));
			return;
		}
		
		if(!$password = $this->input->post('password'))
		{
			$data['message'] = __("Please enter your password", $this->template['module']);
			$data['status'] = 0;
			$this->output->set_header("Content-type: text/xml");
			$this->load->view('ajax', array('data' => $data));
			return;
		}


		//destroying session
		$data = array(
					'id' 			=> 0,
					'username' 		=> '',
					'email' 		=> '',
					'logged_in'		=> false
				);
				
		$this->session->set_userdata($data);
		
		foreach ($data as $key => $value)
		{
			$this->user->$key = $value;
		}
		//apply login
		
		$result['username'] = $username;
		$result['password'] = $password;
		
		$result = $this->plugin->apply_filters('user_auth', $result);
	

		if(isset($result['logged_in']) && $result['logged_in'] !== false)
		{
			// We found a user!
			// Let's save some data in their session/cookie/pocket whatever.
			
			$this->user->id 				= $result['id'];
			$this->user->username			= $result['username'];
			$this->user->logged_in 		= true;
			$this->user->lang 			= $this->session->userdata('lang');
			$this->user->email			= $result['email'];

			$data = array(
						'id' 			=> $this->user->id,
						'username' 		=> $this->user->username,
						'email'		=> $this->user->email, 
						'logged_in'		=> $this->user->logged_in,
						'lang'	=> $this->user->lang
					);
					
			$this->session->set_userdata($data);

			
			$data['message'] = __("Logged in:", $this->template['module']) . " " . $username;
			$data['message'] .= "<br /><a href='" . site_url('member/logout') . "'>" . __("Sign out", $this->template['module']) . "</a>"; 
		
			$data['status'] = 1;
			$data['username'] = $username;
			
			$data = $this->plugin->apply_filters('logged_in_message', $data);
			$this->output->set_header("Content-type: text/xml");
			$this->load->view('ajax', array('data' => $data));

			return;
		}
		else
		{
			
			
			if (isset($result['error_message']))
			{
				$data['message'] = $result['error_message'];
			}
			else
			{
				$data['message'] = __("Login error. Please verify your username " . $this->input->post('username') . " and your password.", $this->template['module']);
			}
			
			$data['status'] = 0;
			$this->output->set_header("Content-type: text/xml");
			$this->load->view('ajax', array('data' => $data));
			return;
		
		}







		
		

	}
	
	function exists($field)
	{
		if (is_null($field))
		{
			echo "false";
		}
		switch($field)
		{
			case 'username':
				if(! $username = $this->input->post('username'))
				{
					echo "false";
					return;
				}
			
				$this->load->model('member_model', 'member');
				if ($this->member->exists(array('username' => $username)))
				{
					echo "false";
					return;
				}
				else
				{
					echo "true";
				}
			
			break;
			case 'email':
				if(! $email = $this->input->post('email'))
				{
					echo "false";
					return;
				}
			
				$this->load->model('member_model', 'member');
				if ($this->member->exists(array('email' => $email)))
				{
					echo "false";
					return;
				}
				else
				{
					echo "true";
				}
			
			break;
		}
	}

}	
