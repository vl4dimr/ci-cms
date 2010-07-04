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
			$this->obj->load->library('encrypt');
			$this->obj->plugin->add_filter('user_auth', array(&$this, '_user_auth'), 30, 2);
			
			$this->_session_to_library();
			$this->_get_levels();
			$this->_get_groups();
			$this->_update_fields();
		}
		
		
		function _update_fields()
		{	
			if ($this->logged_in)
			{
				$this->update($this->username, array('activation' => '', 'lastvisit' => mktime(), 'online' => 1));
			}
		}

		function _get_groups()
		{
			$this->groups[] = '0';

			if($this->logged_in){
				$this->groups[] = '1';
				$this->obj->db->select('g_id');
				$this->obj->db->where('g_user', $this->username);
				$this->obj->db->where("(g_from <= '" . mktime() . "' OR g_from=0)");
				$this->obj->db->where("(g_to >= '" . mktime() . "' OR g_to=0)");
				$query = $this->obj->db->get("group_members");
				
				if($rows = $query->result_array()){
					foreach ($rows as $row) {
						$this->groups[] = $row['g_id'];
					}
				}
			}		
		}

		function _get_levels()
		{
			$admin = array();
			if ($this->logged_in)
			{
				
			
				$this->obj->db->where('username', $this->username);
				$query = $this->obj->db->get('admins');
				if ($rows = $query->result_array())
				{
					foreach($rows as $val) {
						$admin[ $val['module'] ] = $val['level'];
					}
				}
			}

			if (is_array($this->obj->system->modules))
			{
				foreach($this->obj->system->modules as $module)
				{	
					if (!isset($admin[ $module['name'] ]))
					{
						$admin[ $module['name'] ] = 0;
					}
				}
			}


			$this->level = $admin;
		}
		
		function check_level($module, $level)
		{

			if ( !isset($this->obj->user->level[$module]) || $this->obj->user->level[$module] < $level)
			{
				if ($this->obj->uri->segment(1) == "admin" || $this->obj->uri->segment(2) == "admin")
				{
			
					redirect('admin/unauthorized/'. $module . '/' . $level);
					exit;
				}
				else
				{
					redirect('member/unauthorized/'. $module . '/' . $level);
					exit;
				}
			}
		}

		function _prep_password($password)
		{
			// Salt up the hash pipe
			// Encryption key as suffix.
			
			return $this->obj->encrypt->sha1($password.$this->obj->config->item('encryption_key'));
		}
		
		function prep_password($password)
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
		
		function _start_session()
		{
			// $user is an object sent from function login();
			// Let's build an array of data to put in the session.
			
			$data = array(
						'id' 			=> $this->id,
						'username' 		=> $this->username,
						'email'		=> $this->email, 
						'logged_in'		=> $this->logged_in,
						'lang'	=> $this->lang
					);
					
			$this->obj->session->set_userdata($data);
			
		}
		
		function _destroy_session()
		{
			$data = array(
						'id' 			=> 0,
						'username' 		=> '',
						'email' 		=> '',
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
			
			//destroy previous sesson
			$this->_destroy_session();
			//First check from the table
			
			$result['username'] = $username;
			$result['password'] = $password;
			
			$result = $this->obj->plugin->apply_filters('user_auth', $result);
		

			if(isset($result['logged_in']) && $result['logged_in'] !== false)
			{
				// We found a user!
				// Let's save some data in their session/cookie/pocket whatever.
				
				$this->id 				= $result['id'];
				$this->username			= $result['username'];
				$this->logged_in 		= true;
				$this->lang 			= $this->obj->session->userdata('lang');
				$this->email			= $result['email'];
				$this->_start_session();
				$this->obj->session->set_flashdata('notification', 'Login successful...');
				return true;
			}
			else
			{
				$this->_destroy_session();
				
				if (isset($result['error_message']))
				{
					$this->obj->session->set_flashdata('notification', $result['error_message']);
				}
				else
				{
					$this->obj->session->set_flashdata('notification', 'Login failed...');
				}
				
				return false;
			
			}
		}
		
	
		
		function logout()
		{
			//keep last_uri
			$this->update($this->username, array('online' => 0));
			$last_uri = $this->obj->session->userdata("last_uri");
			$this->_destroy_session();
			$this->obj->session->set_userdata(array('last_uri' => $last_uri));
			$this->obj->session->set_flashdata('notification', 'You are now logged out');
		}
		
		function update($username, $data)
		{
			
			//encrypt password
			
			if (isset($data['password']))
			{
				$data['password'] = $this->_prep_password($data['password']);
			}
			
			$this->obj->db->where('username', $username);
			$this->obj->db->set($data);
			$this->obj->db->update($this->table);
			
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
			$this->obj->plugin->do_action('user_registered', $data);
			return $this->obj->db->insert_id();
		}

		function require_login()
		{
			if (!$this->logged_in)
			{
				//save _POST and uri
				$data = array(
				"last_post" => $_POST,
				"redirect" => substr($this->obj->uri->uri_string(), 1)
				);
				$this->obj->session->set_userdata($data);
				
				
				redirect("member/login");
			}
		}
		
		function get_user($where)
		{
			if (!is_array($where))
			{
				$where = array('id', $where);
			}
		
			$query = $this->obj->db->get_where('users', $where);
			if ($query->num_rows() > 0 )
			{
				return $query->row_array();
			}
			else
			{
				return false;
			}
		}
		
		function get_users($where)
		{
			if (!is_array($where))
			{
				$where = array('id', $where);
			}
		
			$query = $this->obj->db->get_where('users', $where);
			if ($query->num_rows() > 0 )
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
		}

		function get_user_number($where = array(), $params = array())
		{
			
			$this->obj->db->select("COUNT(id) total");
			$this->obj->db->where($where);
			$this->obj->db->from("users");
			
			$query = $this->obj->db->get();
			if ($query->num_rows() > 0)
			{
				$row = $query->row_array();
				return $row['total'];
			}
			else
			{
				return 0;
			}
		}
		
		function delete_user($where, $limit = 1)
		{
			if (!is_array($where))
			{
				$where = array('id', $where);
			}
			$this->obj->db->where($where);
			$this->obj->db->limit($limit);
			$this->obj->db->delete("users");
		}
		
		function get_user_list($params = array())
		{
			$default_params = array
			(
				'order_by' => 'id',
				'limit' => null,
				'start' => null,
				'where' => null,
				'like' => null,
			);
			foreach ($default_params as $key => $value)
			{
				$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
			}
			if (!is_null($params['like']))
			{
				$this->obj->db->like($params['like']);
			}
			$this->obj->db->order_by($params['order_by']);
			if (!is_null($params['where']))
			{
				$this->obj->db->where($params['where']);
			}
			$this->obj->db->limit($params['limit'], $params['start']);
		
			$query = $this->obj->db->get('users');
			if ($query->num_rows() > 0 )
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
		}

		function get_group_list($params = array())
		{
			$default_params = array
			(
				'order_by' => 'id',
				'limit' => null,
				'start' => null,
				'where' => array('g_owner' => $this->username),
				'like' => null,
			);
			
			foreach ($default_params as $key => $value)
			{
				$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
			}
			if (!is_null($params['like']))
			{
				$this->obj->db->like($params['like']);
			}
			if (!is_null($params['where']))
			{
				$this->obj->db->where($params['where']);
			}
			$this->obj->db->or_where(array('g_id' => '0'));
			$this->obj->db->or_where(array('g_id' => '1'));
			$this->obj->db->order_by($params['order_by']);
			$this->obj->db->limit($params['limit'], $params['start']);
			
			$query = $this->obj->db->get('groups');

			if($query->num_rows() > 0)
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
			
		}
		
		function is_online($username)
		{
			$this->obj->db->where(array('username' => $username, 'lastvisit >' => mktime() - 600, 'online' => 1 ));
			$this->obj->db->order_by('lastvisit DESC');
			$query = $this->obj->db->get('users');
			if($query->num_rows() > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		function get_online()
		{
			$this->obj->db->where(array('lastvisit >' => mktime() - 600, 'online' => 1 ));
			$this->obj->db->order_by('lastvisit DESC');
			$query = $this->obj->db->get('users');
			if($query->num_rows() > 0)
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
			
		}

		function _user_auth($result)
		{
			// this is the authentication from database
			//used only if no plugin were used before
			if(isset($result['logged_in'])) return $result;
			
			$result['logged_in'] = false;

			$this->obj->db->where('username', $result['username']);
			$this->obj->db->where('password', $this->_prep_password($result['password']));
			$this->obj->db->where('status', 'active');
			
			$query = $this->obj->db->get('users', 1);
			
			if ( $query->num_rows() == 1 )
			{
				
				$userdata = $query->row_array();
				
				$result['logged_in'] = true;
				$result['email'] = $userdata['email'];
				$result['id'] = $userdata['id'];
				
				return $result;
			}
			else
			{
				$result['logged_in'] = false;
				return $result;
			}
			
		
		}
		
		
	}	
