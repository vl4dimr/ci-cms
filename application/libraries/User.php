<?php

	class User {
		
		var $id = 0;
		var $logged_in = false;
		var $username = '';
		var $table = 'users';
		var $level = array();
		var $groups = array();
		
		function User()
		{
			$this->obj =& get_instance();
			
			$this->_session_to_library();
			$this->obj->load->library('encrypt');
			
			$this->_get_levels();
			$this->_get_groups();
		}
			
		function _get_groups()
		{
			$this->groups[] = 0;

			if($this->logged_in){
				$this->groups[] = 1;
				$this->obj->db->select('g_id');
				$this->obj->db->where("g_user = '" .$this->username ."' AND (g_from <= '" . mktime() . "' OR g_from=0) AND (g_to >= '" . mktime() . "' OR g_to=0)");
				$query = $this->obj->db->get("group_members");
				
				if($rows = $query->result_array()){
					foreach ($rows as $row) {
						$this->groups[] = $row->g_id;
					}
				}
			}		
		}

		function _get_levels()
		{
			if ($this->logged_in)
			{
			
				$query = $this->obj->db->get('admins', 1);
				
				if ($query->num_rows() == 0)
				{
					$this->obj->db->insert('admins', array('username' => $this->username, 'module' => 'admin', 'level' => 4));
				}
			
				$this->obj->db->where('username', $this->username);
				$query = $this->obj->db->get('admins');
				$admin = array();
				if ($rows = $query->result_array())
				{
					foreach($rows as $val) {
						$admin[ $val['module'] ] = $val['level'];
					}
					$this->level = $admin;
				}
			}
			else
			{
				return false;
			}
		}
		
		function check_level($module, $level)
		{
		
			
			if ( !isset($this->obj->user->level[$module]) || $this->obj->user->level[$module] < $level)
			{
		
				redirect('admin/unauthorized/'. $module . '/' . $level);
				exit;
			}
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
			$this->lang 			= $this->obj->session->userdata('lang');
			$this->email			= $this->obj->session->userdata('email');
		}
		
		function _start_session($user)
		{
			// $user is an object sent from function login();
			// Let's build an array of data to put in the session.
			$data = array(
						'id' 			=> $user->id,
						'username' 		=> $user->username,
						'email'		=> $user->email, 
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
				$this->register($username, $password, '');
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
				
				$this->obj->session->set_flashdata('notification', 'Login successful...');
				
				return true;
			}
			else
			{
				// Login failed...
				
				// Couldn't find the user,
				// Let's destroy everything just to make sure.
				
				$this->_destroy_session();
				
				$this->obj->session->set_flashdata('notification', 'Login failed...');
				
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
