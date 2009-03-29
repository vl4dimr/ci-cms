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
		
		$this->session->set_flashdata('notification',__("You are now logged out.", $this->template['module']));
		$last_uri = $this->session->userdata('last_uri');
		redirect($last_uri);
	}
	
	function login()
	{

		if ( $this->user->logged_in )
		{
			$this->session->set_flashdata('notification', __("You are now logged in", $this->template['module']));
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
					$this->session->set_flashdata('notification', __("Login error. Please verify your username and your password.", $this->template['module']));
					redirect('member/login', 'refresh');
				}
			}
		}
	}
	
	function signup()
	{
		if ($this->user->logged_in)
		{
			redirect('member/profile');
		}
		$this->load->library('validation');	
		
		$rules['username'] = "trim|required|min_length[4]|max_length[12]|xss_clean|callback__verify_username";
		$rules['remail'] = "trim";
		$rules['email'] = "trim|required|matches[remail]|valid_email|callback__verify_mail";	

		
		$this->validation->set_rules($rules);	

		$fields['email'] = __("email", $this->template['module']);	
		$fields['username'] = __("username", $this->template['module']);	
		$fields['remail'] = __("password confirmation", $this->template['module']);	

		$this->validation->set_fields($fields);	
		$this->validation->set_error_delimiters('<p style="color:#900">', '</p>');

		//$this->validation->set_message('min_length', __('The %s field is required'));
		$this->validation->set_message('required', __('The %s field is required', $this->template['module']));
		$this->validation->set_message('matches', __('The %s field does not match the %s field', $this->template['module']));
		$this->validation->set_message('valid_email', __('The email address you entered is not valid.', $this->template['module']));			
						

					
		if ($this->validation->run() == FALSE)
		{
			$this->layout->load($this->template, 'signup');
		}
		else
		{
			//generate key
			$password = $this->_keygen();
			
			$id = $this->user->register(
				$this->input->post('username'),
				$password,
				$this->input->post('email')
			);
			
			$this->load->library('email');
			//send password
			$this->email->from($this->system->admin_email, $this->system->site_name);
			$this->email->to($this->input->post('email'));
			$this->email->subject(sprintf(__("Your password for %s", $this->template['module']), $this->system->site_name));
			$this->email->message(sprintf(__("Hello %s,\n\nThank you for registering to %s.\nNow you can enter in the site with these information.\n\nUsername: %s\nPassword: %s\n\nThank you.\nThe administrator", $this->template['module']), $this->input->post('username'), $this->system->site_name, $this->input->post('username'), $password));

			$this->email->send();
			//notify admin
			
			$this->email->from($this->system->admin_email, $this->system->site_name);
			$this->email->to($this->system->admin_email);
			$this->email->subject(sprintf(__("New member for %s", $this->template['module']), $this->system->site_name));
			$this->email->message(sprintf(__("Hello admin,\n\nA new member has just registere into your site. These are the submitted information.\n\nUsername: %s\nEmail: %s\nIP: %s\nThank you.\nThe administrator", $this->template['module']), $this->input->post('username'), $this->input->post('email'), $this->input->ip_address()));

			$this->email->send();
			
			
			$this->session->set_flashdata('notification', nl2br(sprintf(__("Thank you for registering with this site.\n\nPlease check your email %s and get there your password, then turn back to log in.", $this->template['module']), "<b>" . $this->input->post('email') . "</b>")));
			redirect('member/login');
		}

	}
	
	function _verify_username($data)
	{

		$username = $this->input->post('username');
		
		//check if email belongs to someone else
		if ($this->member_model->exists(array('username' => $username)))
		{
			$this->validation->set_message('verify_username', __("The username is already in use", $this->template['module']));
			return FALSE;
		}
		
		if ( !ereg("^[[:alnum:]]+$", $username))
		{
			$this->validation->set_message('verify_username', __("The username format is not valid, please use alphanumeric characters.", $this->template['module']));
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
	function _verify_mail($data)
	{

		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$username = $this->input->post('username');
		
		//check if email belongs to someone else
		if ($this->member_model->exists(array('email' => $email, 'username !=' => $username)))
		{
			$this->validation->set_message('_verify_mail', __("The email is already in use", $this->template['module']));
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
			$username = $this->user->username;
			$this->load->library('validation');
			$rules['password'] = "trim|matches[passconf]";
			$rules['passconf'] = "trim";
			
			$this->template['member'] = $this->user->get_user(array('username' => $this->user->username));
			$this->validation->set_rules($rules);	

			$fields['password'] = __("password", $this->template['module']);	
			$fields['passconf'] = __("password confirmation", $this->template['module']);	

			$this->validation->set_fields($fields);	
			$this->validation->set_error_delimiters('<p style="color:#900">', '</p>');

			$this->validation->set_message('required', __('The %s field is required', $this->template['module']));
			$this->validation->set_message('matches', __('The %s field does not match the %s field', $this->template['module']));
							
						
			if ($this->validation->run() == FALSE)
			{
				$this->layout->load($this->template, 'myprofile');
			}
			else
			{
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}
				$this->user->update($username, $data);
				
				$this->session->set_flashdata('notification', __("Your profile was saved.", $this->template['module']));
				if ($redirect = $this->session->userdata("login_redirect"))
				{
					$this->session->set_userdata(array("login_redirect" => ""));
					redirect($redirect);
				}
				else
				{
					redirect('member/profile');
				}
			
			}				
		
		}
		else
		{
			echo $username;
		}
	}
	
	function _keygen()
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
	
	

	
	function adino($code = null)
	{
		if (is_null($code))
		{
			if ($this->user->logged_in)
			{
				redirect('member/profile');
			}
			
			
			$this->load->library('validation');
			$rules['email'] = "trim|required|valid_email|callback__email_not_found";	
			
			$this->validation->set_rules($rules);	
			$this->validation->set_error_delimiters('<p style="color:#900">', '</p>');
			$fields['email'] = __("email", $this->template['module']);	

			$this->validation->set_fields($fields);	

			$this->validation->set_message('required', __('The %s field is required', $this->template['module']));
			$this->validation->set_message('valid_email', __('The address %s is not a valid email', $this->template['module']));
			
			if ($this->validation->run() == FALSE)
			{
				$this->layout->load($this->template, 'adino');
				
			}
			else
			{

				$user = $this->user->get_user(array('email' => $this->input->post('email')));
				
				$key = $this->_keygen();
				$this->load->library('email');
				//send password
				$this->email->from($this->system->admin_email, $this->system->site_name);
				$this->email->to($user['email']);
				$this->email->subject(sprintf(__("Create a new password: %s", $this->template['module']), $this->system->site_name));
				$this->email->message(sprintf(__("Hello %s,\n\nYou said you forgot your password for %s. Since we do not keep passwords in clear, you have to create one. Click the link below to create a new password.\n\n%s\n\nThank you.\nThe administrator", $this->template['module']), $user['username'], $this->system->site_name, site_url($this->user->lang . '/member/adino/' . $key)));

				$this->email->send();
				
				$this->user->update($user['username'], array('activation' => $key));
				$this->template['message'] = sprintf(__("We have sent to %s the instruction on how to create a new password. Please check your email.", $this->template['module']), $user['email']);
				$this->layout->load($this->template, 'adino_result');
			}
		}
		else
		{
		//verify code
			if ($user = $this->user->get_user(array('activation' => $code)))
			{
				if ($this->input->post('newpass') && ($this->input->post('newpass') == $this->input->post('rnewpass')))
				{
					$this->user->update($user['username'], array('activation' => '', 'password' => $this->input->post('newpass')));
					$this->session->set_flashdata('notification', __("Your password is now changed. You can login with your username and the new password.", $this->template['module']));
					redirect('member/login');
				}
				else
				{

					$this->template['row'] = $user;
					$this->layout->load($this->template, 'adino_activate');
				}
			}
			else
			{
				$this->template['message'] = __("The activation link is not valid. Please check again your email and verify the link. If you are sure the link was right then contact the administrator.", $this->template['module']);
				$this->layout->load($this->template, 'adino_result');
			
			}
		}
	}

	function _email_not_found($email)
	{

		//check if email belongs to someone else
		if (!$this->member_model->exists(array('email' => $email)))
		{
			$this->validation->set_message('email_not_found', __('The address %s is not found in our database. Try another address.', $this->template['module']));
			
			return FALSE;
		}
	
	}	
	
	function change_mail()
	{
		
		$this->user->require_login();
		
		$this->template['title'] = __("Change your email", "member") ;
		$this->load->library('validation');	
		
		$rules['remail'] = "trim";
		$rules['email'] = "trim|required|matches[remail]|valid_email|callback__verify_mail";	

		
		$this->validation->set_rules($rules);	

		$fields['email'] = __("Email", $this->template['module']);	
		$fields['remail'] = __("Email confirmation", $this->template['module']);	


		$this->validation->set_fields($fields);	
		$this->validation->set_error_delimiters('<p style="color:#900">', '</p>');

		//$this->validation->set_message('min_length', __('The %s field is required'));
		$this->validation->set_message('required', __('The %s field is required', $this->template['module']));
		$this->validation->set_message('matches', __('The %s field does not match the %s field', $this->template['module']));
		$this->validation->set_message('valid_email', __('The email address you entered is not valid.', $this->template['module']));			
						

					
		if ($this->validation->run() == FALSE)
		{
			
			$this->layout->load($this->template, 'change_mail');
		}
		else
		{	
			$hash = $this->_keygen();
			$this->load->helper('file');
			write_file('cache/' . $hash, $this->input->post('email'));

			$this->load->library('email');
			//send password
			$this->email->from($this->system->admin_email, $this->system->site_name);
			$this->email->to($this->input->post('email'));
			$this->email->subject(sprintf(__("Email change confirmation", $this->template['module']), $this->system->site_name));
			$this->email->message(sprintf(__("Hello %s,\n\nYou asked to change your email for %s.\n Please click the link below to confirm that this email belongs to you. \n\n%s\n\nThank you.\nThe administrator", $this->template['module']), $this->user->username, $this->system->site_name, site_url('member/verify/' . $hash)));

			$this->email->send();
			

			$this->session->set_flashdata('notification', sprintf(__("Please, check your new email %s, We sent a link to verify that it belongs to you.", $this->template['module']), $this->input->post('email')));
			redirect('member/profile');
		}
	}
	
	function verify($hash = "null")
	{
		$this->user->require_login();
		$this->load->helper('file');
		
		if (is_file('cache/' . $hash))
		{
			$email = read_file('cache/' . $hash);
			$this->user->update($this->user->username, array('email' => $email));
			@unlink('cache/' . $hash);
			$this->session->set_flashdata('notification', __("Your email is now changed. ", $this->template['module']));
			redirect('member/profile');
		}
		else
		{
			$this->session->set_flashdata('notification', __("The link is not valid. Please check your email to verify. ", $this->template['module']));
			
		}	
	}
	
	
}