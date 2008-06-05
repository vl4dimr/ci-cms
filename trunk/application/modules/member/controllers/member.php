<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Member extends Controller {
	

	function Member()
	{
		parent::Controller();
		$this->template['module']	= 'member';
	}
	

	function index()
	{
		return;
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
	function profile($username) {
		echo $username;
		
	}
}