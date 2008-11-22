<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Member extends Controller {
	

	function Member()
	{
		parent::Controller();
		$this->template['module']	= 'member';
		$this->load->model('member_model');
		
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
	
	function signup()
	{
		$this->load->library('validation');	
		
		$rules['username'] = "trim|required|min_length[4]|max_length[12]|xss_clean|callback_verify_username";
		$rules['remail'] = "trim";
		$rules['email'] = "trim|required|matches[remail]|valid_email|callback_verify_mail";	

		
		$this->validation->set_rules($rules);	

		$fields['email'] = __("email", $this->template['module']);	
		$fields['username'] = __("username", $this->template['module']);	
		$fields['remail'] = __("password confirmation", $this->template['module']);	

		$this->validation->set_fields($fields);	
		$this->validation->set_error_delimiters('<p style="color:#900">', '</p>');

		//$this->validation->set_message('min_length', __('The %s field is required'));
		$this->validation->set_message('required', __('The %s field is required'));
		$this->validation->set_message('matches', __('The %s field does not match the %s field'));
		$this->validation->set_message('valid_email', 'The email address you entered is not valid.');			
						

					
		if ($this->validation->run() == FALSE)
		{
			$this->layout->load($this->template, 'signup');
		}
		else
		{
			//generate key
			$password = $this->keygen();
			
			$id = $this->user->register(
				$this->input->post('username'),
				$password,
				$this->input->post('email')
			);
			
			$this->load->library('email');
			//send password
			$this->email->from($this->system->admin_email, $this->system->site_name);
			$this->email->to($this->input->post('email'));
			$this->email->subject(sprintf(__("Your password for %s", "member"), $this->system->site_name));
			$this->email->message(sprintf(__("Hello %s,\n\nThank you for registering to %s.\nNow you can enter in the site with these information.\n\nUsername: %s\nPassword: %s\n\nThank you.\nThe administrator"), $this->input->post('username'), $this->system->site_name, $this->input->post('username'), $password), "member");

			$this->email->send();
			//notify admin
			
			$this->email->from($this->system->admin_email, $this->system->site_name);
			$this->email->to($this->system->admin_email);
			$this->email->subject(sprintf(__("New member for %s", "member"), $this->system->site_name));
			$this->email->message(sprintf(__("Hello admin,\n\nA new member has just registere into your site. These are the submitted information.\n\nUsername: %s\nEmail: %s\nIP: %s\nThank you.\nThe administrator"), $this->input->post('username'), $this->input->post('email'), $this->input->ip_address()), "member");

			$this->email->send();
			
			
			$this->session->set_flashdata('notification', nl2br(__("Thank you for registering with this site.\n\nPlease check your email %s and get there your password, then turn back to log in.", $this->template['module'])));
			redirect('member/login');
		}

	}
	
	function verify_username($data)
	{

		$username = $this->input->post('username');
		
		//check if email belongs to someone else
		if ($this->member_model->exists(array('username' => $username)))
		{
			$this->validation->set_message('verify_username', __("The username is already in use", $this->template['module']));
			return FALSE;
		}

	}

	/**
	 * Registration validation callback
	 *
	 * @access	private
	 * @param	string
	 * @return	bool
	 */
	function verify_mail($data)
	{

		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$username = $this->input->post('username');
		
		//check if email belongs to someone else
		if ($this->member_model->exists(array('email' => $email, 'username !=' => $username)))
		{
			$this->validation->set_message('verify_mail', __("The email is already in use", $this->template['module']));
			return FALSE;
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
			$rules['email'] = "trim|required|valid_email|callback_verify_mail";	

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
	
	function keygen()
	{
		$size = 3;
		$key = "";
		$consonne = "bcdfghjklmnpqrstvwxz";
		$voyelle = "aeiouy";

		srand((double)microtime()*date("YmdGis"));

		for($cnt = 0; $cnt < $size; $cnt++)
		{
		$key .= $consonne[rand(0, 19)].$voyelle[rand(0, 5)];
		}

		return $key;
	}		
	
	
}