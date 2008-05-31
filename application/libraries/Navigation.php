<?php

	class Navigation {
		
		function Navigation()
		{
			$this->obj =& get_instance();
		}
		
		function get()
		{
			$nav = array();
			
			$this->obj->db->select('uri, title');
			$this->obj->db->where('active', true);
			$this->obj->db->orderby('weight');
			
			$query = $this->obj->db->get('navigation');
			
			if ( $query->num_rows() > 0 )
			{
				$nav = $query->result_array();
			}
			
			return $nav;
		}
		
		
	}


?>