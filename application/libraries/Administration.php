<?php

	class Administration {
		
		function Administration()
		{
			$this->obj =& get_instance();
			$this->obj->load->helper('dashboard');	
			$this->find_modules();
			$this->check_latest_version();
			
			
			if ( !$this->obj->user->logged_in && $this->obj->uri->segment(2) != 'login' )
			{
				redirect('admin/login');
				exit;
			}
		}
		
		function find_modules()
		{
			$modules = array();
			
			$handle = opendir(APPPATH.'modules');

			if ($handle)
			{
				while ( false !== ($module = readdir($handle)) )
				{
					// make sure we don't map silly dirs like .svn, or . or ..

					if ( (substr($module, 0, 1) != ".") && ($module != 'admin') )
					{
						$this->modules[] = $module;
						
						if (file_exists(APPPATH.'modules/'.$module.'/controllers/admin.php'))
						{
							$this->modules_with_admin[] = $module;
						}
					}
				}
			}
		}
		
		function check_latest_version()
		{
			/*
			ini_set('default_socket_timeout', 1);
			$this->latest_version = @file_get_contents("http://blaze.haughin.com/cms/version.php");
			
			if (!$this->latest_version)
			{
				//$this->latest_version = BLAZE_VERSION;
			}
			*/
		}
		
		function no_active_users()
		{
			$this->obj->db->where('status', 'active');
			$query = $this->obj->db->get('users');
			
			return $query->num_rows();
		}
		
		function db_size()
		{
			
			$sql = 'SHOW TABLE STATUS';
			
			$query = $this->obj->db->query($sql);
			$result = $query->result_array();
			
			foreach ($result as $row)
			{
				$db_size = $row['Data_length'] + $row['Index_length'];
			}
			
			return $db_size;
			
		}
	}


?>