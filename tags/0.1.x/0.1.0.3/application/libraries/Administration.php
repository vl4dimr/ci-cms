<?php

	class Administration {
		
		function Administration()
		{
			$this->obj =& get_instance();
			$this->obj->load->helper('dashboard');	
			$this->find_modules();
			
			if ( !$this->obj->user->logged_in && $this->obj->uri->segment(2) != 'login' )
			{
				$this->obj->session->set_flashdata('redirect', $this->obj->uri->uri_string());
				redirect('admin/login');
				exit;
			}
		}
		
		function find_modules()
		{
			$this->obj->db->where('status', 1);
			$this->obj->db->order_by('ordering');
			$query = $this->obj->db->get('modules');
			
			$modules = array();
			$this->modules = $query->result_array();
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