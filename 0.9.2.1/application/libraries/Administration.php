<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @version $Id$
 * @package solaitra
 * @copyright Copyright (C) 2005 - 2008 Tsiky dia Ampy. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 */

	class Administration {
		
		function Administration()
		{
			$this->obj =& get_instance();
			$this->obj->load->helper('dashboard');	
			$this->obj->load->helper('text');	
			
			if ( !$this->obj->user->logged_in && $this->obj->uri->segment(2) != 'login' )
			{
				$this->obj->session->set_flashdata('redirect', substr($this->obj->uri->uri_string(), 1));
				redirect('admin/login');
				exit;
			}
			//check if at least admin of one module
			if ($this->obj->user->logged_in && count($this->obj->user->level) == 0 )
			{
				$this->obj->session->set_flashdata('notification', __("That username is not an admin.", "admin"));
				$this->obj->user->logout();
				redirect('admin/login');
				exit;
			}			
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