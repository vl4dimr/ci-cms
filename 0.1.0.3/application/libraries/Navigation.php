<?php

	class Navigation {
		
		var $nav;
		var $nav_opened;
		function Navigation()
		{
			$this->obj =& get_instance();
			$this->lang = $this->obj->session->userdata('lang');
		}
		/*
		function get()
		{
			$nav = array();
			
			$this->obj->db->select('id, uri, title');
			$this->obj->db->where('active', true);
			$this->obj->db->orderby('weight');
			
			$query = $this->obj->db->get('navigation');
			
			if ( $query->num_rows() > 0 )
			{
				$nav = $query->result_array();
			}
			
			return $nav;
		}
		*/
		function get()
		{
			if (!$data = $this->obj->cache->get('navigationarray'.$this->lang, 'navigation'))
			{
				$data = $this->_get();
				$this->obj->cache->save('navigationarray'.$this->lang, $data, 'navigation',0 );
			}
			return $data;			
		}
		
		function _get($parent = 0, $level = 0) {
			// retrieve all children of $parent
			$this->obj->db->where(array('parent_id' => $parent, 'lang' => $this->lang, 'active' => 1));
			$this->obj->db->orderby('parent_id, weight');
			$query = $this->obj->db->get('navigation');
			

			// display each child
			foreach ($query->result_array() as $row) {
			// indent and display the title of this child
			$this->nav[] = array(
				'level' =>$level, 
				'title' => $row['title'],
				'parent_id' => $row['parent_id'],
				'id' => $row['id'],
				'uri' => $row['uri']
			);
			// call this function again to display this
			// child's children
				$this->_get($row['id'], $level+1);
			}
			return $this->nav;
		} 	
		
		function print_menu()
		{
			
			if (!$data = $this->obj->cache->get('navigation'.$this->lang, 'navigation'))
			{
				$data = $this->_print_menu();
				$this->obj->cache->save('navigation'.$this->lang, $data, 'navigation',0 );
			}
			return $data;
		}
		function _print_menu ($parent = 0)
		{

			$this->obj->db->where(array('parent_id' => $parent, 'lang' => $this->lang, 'active' => 1));
			$this->obj->db->orderby('parent_id, weight');
			$query = $this->obj->db->get('navigation');		
		 	if ($query->num_rows() == 0)
			{
				return false;
			}
			$html = '<ul ' . (($this->nav_opened == false) ? 'class="nav"' : '') . '>';
			$this->nav_opened = true;
			foreach ($query->result_array() as $item)
			{
				$html .= '<li ' . ($item['uri'] == substr($this->obj->uri->uri_string(), 1) ? 'class="current" ' : '') . '>';
				$html .= '<a href="'. $item['uri'] .'">'.$item['title'].'</a>';
				//colling same function where we are (recursive) to check if menu item has submenu
				$html .= $this->_print_menu($item['id']);
				$html .= '</li>';
			}
		 
			$html .= '</ul>';
			return $html;
		}		
		
		function get_menu_array()
		{
			if (!$data = $this->obj->cache->get('navigationarray'.$this->lang, 'navigation'))
			{
				$data = $this->_get_menu_array();
				$this->obj->cache->save('navigationarray'.$this->lang, $data, 'navigation',0 );
			}
			return $data;		
		}
		

	}


?>