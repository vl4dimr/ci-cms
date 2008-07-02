<?php

	class User {
		
		var $id = 0;
		var $logged_in = false;
		var $username = '';
		var $table = 'users';
		
		function User()
		{
			$this->obj =& get_instance();
			
			$this->_session_to_library();
			$this->obj->load->library('encrypt');
		}
		
		function _prep_password($password)
		{
			// Salt up the hash pipe
			// Encryption key as suffix.
			
			return $this->obj->encrypt->sha1($password.$this->obj->config->item('encryption_key'));
		}
		
		function _session_to_library()
		{
			// Pulls session data into the library.
			
			$this->id 				= $this->obj->session->userdata('id');
			$this->username			= $this->obj->session->userdata('username');
			$this->logged_in 		= $this->obj->session->userdata('logged_in');
		}
		
		function _start_session($user)
		{
			// $user is an object sent from function login();
			// Let's build an array of data to put in the session.
			
			$data = array(
						'id' 			=> $user->id,
						'username' 		=> $user->username,
						'logged_in'		=> true
					);
					
			$this->obj->session->set_userdata($data);
			
		}
		
		function _destroy_session()
		{
			$data = array(
						'id' 			=> 0,
						'username' 		=> '',
						'logged_in'		=> false
					);
					
			$this->obj->session->set_userdata($data);
			
			foreach ($data as $key => $value)
			{
				$this->$key = $value;
			}
		}
		
		function login($username, $password)
		{
			$query = $this->obj->db->get($this->table, 1);
			
			if ($query->num_rows() == 0)
			{
				$this->register($username, $password, 'admin');
			}
		
			// First up, let's query the DB.
			// Prep the password to make sure we get a match.
			// And only allow active members.
			
			$this->obj->db->where('username', $username);
			$this->obj->db->where('password', $this->_prep_password($password));
			$this->obj->db->where('status', 'active');
			
			$query = $this->obj->db->get($this->table, 1);
			
			if ( $query->num_rows() == 1 )
			{
				// We found a user!
				// Let's save some data in their session/cookie/pocket whatever.
				
				$user = $query->row();
				
				$this->_start_session($user);
				
				$this->obj->session->set_flashdata('user', 'Login successful...');
				
				return true;
			}
			else
			{
				// Login failed...
				
				// Couldn't find the user,
				// Let's destroy everything just to make sure.
				
				$this->_destroy_session();
				
				$this->obj->session->set_flashdata('user', 'Login failed...');
				
				return false;
			}
		}
		
		function logout()
		{
			$this->_destroy_session();
			$this->obj->session->set_flashdata('user', 'You are now logged out');
		}
		
		function update($username, $data)
		{
			//encrypt password
			if (isset($data['password']))
			{
				$data['password'] = $this->_prep_password($data['password']);
			}
			$this->obj->db->where('username', $username);
			$this->obj->db->update($this->table, $data);
			
		}
		
		function register($username, $password, $email)
		{
			// $user is an array...
			
			$data	= 	array(
							'username'	=> $username,
							'password'	=> $this->_prep_password($password),
							'email'		=> $email,
							'status'	=> 'active',
							'registered'=> mktime()
						);
			
			$query = $this->obj->db->insert($this->table, $data);
			
			return $this->obj->db->insert_id();
		}
	}
?>