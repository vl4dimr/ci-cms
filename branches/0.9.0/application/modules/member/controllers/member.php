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
				$username = $this->input->post('username');
				$password = $this->input->post('password');
				
				if ($this->user->login($username, $password))
				{
					redirect('member/profile');
				}
				else
				{
					$this->layout->load($this->template, 'login');
				}
			}
		}
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
			echo "Izaho";
		}
		else
		{
			echo $username;
		}
	}
}