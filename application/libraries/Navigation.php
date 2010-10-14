<?php

	class Navigation {
		
		var $nav;
		var $nav_opened;
		function Navigation()
		{
			$this->obj =& get_instance();
			
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
		function get($where = null)
		{

			$hash = $this->obj->user->lang;
			$hash .= serialize($this->obj->user->groups);
			
			if (!is_null($where))
			{
				if(is_array($where))
				{
					$where['lang'] = $this->obj->user->lang;
					$hash .= serialize($where);
				}
				else
				{
					$hash .= $where;
					$where = array('id' => $where);
				}
				
			}
			
			$hash = md5($hash);
			
			if (!$data = $this->obj->cache->get('navigationarray'.$hash, 'navigation'))
			{
				if (is_null($where))
				{
					$parent = 0;
				}
				else
				{
					$this->obj->db->where($where);
					$where = "lang = '" . $this->obj->user->lang . "' AND g_id IN ('" . join("', '" , $this->obj->user->groups) . "')";
					$this->obj->db->where($where);
					$query = $this->obj->db->get('navigation');
					
					if ($query->num_rows() > 0 )
					{
						$row = $query->row_array();
						$parent = $row['id'];
					}
					else
					{
						$parent = 0;
					}
				}
				
				$this->nav = array();
				$data = $this->_get($parent);
				$this->obj->cache->save('navigationarray'.$hash, $data, 'navigation',0 );
			}
			
			return $data;			
		}
		
		function _get($parent = 0, $level = 0) {
			// retrieve all children of $parent
			$this->obj->db->where(array('parent_id' => $parent, 'lang' => $this->obj->user->lang, 'active' => 1));
			$this->obj->db->where("lang = '" . $this->obj->user->lang . "' AND g_id IN ('" . join("', '" , $this->obj->user->groups) . "')");
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
				'uri' => ((substr($row['uri'], 0, 7) == "http://") ||
						  (substr($row['uri'], 0, 8) == "https://") ||
						  (substr($row['uri'], 0, 6) == "ftp://") ||
						  (substr($row['uri'], 0, 7) == "mailto:"))? $row['uri']: site_url($row['uri'])
			);
			// call this function again to display this
			// child's children
				$this->_get($row['id'], $level+1);
			}
			return $this->nav;
		} 	
		
		function print_menu()
		{
			
			if (!$data = $this->obj->cache->get('navigation'.$this->obj->user->lang, 'navigation'))
			{
				$data = $this->_print_menu();
				$this->obj->cache->save('navigation'.$this->obj->user->lang, $data, 'navigation',0 );
			}
			return $data;
		}
		function _print_menu ($parent = 0)
		{


			$select = "SELECT * FROM " . $this->obj->db->dbprefix('navigation')
			. " WHERE parent_id = '$parent' "
			. " AND lang = '" . $this->obj->user->lang . "' "
			. " AND `active` = 1 " 
			. " AND g_id IN ('".join("', '", $this->obj->user->groups)."') "
			. " ORDER BY `parent_id`, `weight`";
			$query = $this->obj->db->query($select);


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
			if (!$data = $this->obj->cache->get('navigationarray'.$this->obj->user->lang, 'navigation'))
			{
				$data = $this->_get_menu_array();
				$this->obj->cache->save('navigationarray'.$this->obj->user->lang, $data, 'navigation',0 );
			}
			return $data;		
		}
		

		/*******
		 *
		 * thankx to rmorgan
		 * http://code.google.com/p/ci-cms/issues/detail?id=48
		 * Use: echo $this->navigation->print_menu_by_title('Footer-Menu');
		 *
		 ******/
		 
	   function print_menu_by_title($title='')
	   {
			   //echo  "Parent Id: ".$parent;
					   
			   if (!$data = $this->obj->cache->get('navigation'.$title.$this->obj->user->lang, 'navigation'))
			   {
					   $data = $this->_print_menu_by_title($title);
					   $this->obj->cache->save('navigation'.$title.$this->obj->user->lang, $data, 'navigation',0 );
			   }
			   return $data;
	   }

	   function _print_menu_by_title($title='')
	   {
			   $select = "SELECT t1.* "
					   ."FROM " . $this->obj->db->dbprefix('navigation') . " t1 "
					   ."JOIN " . $this->obj->db->dbprefix('navigation') . " t2 ON t1.parent_id = t2.id "
					   ."WHERE t2.title = '$title' "
					   ."AND t1.active = 1 "
					   ."AND t2.lang = '".$this->obj->user->lang."' "
					   ."AND t1.lang = '".$this->obj->user->lang."' "
					   ."AND (t2.g_id IN ('".join("', '", $this->obj->user->groups)."')) "
					   ."AND (t1.g_id IN ('".join("', '", $this->obj->user->groups)."')) "
					   ."ORDER BY t1.parent_id, t1.weight";
					   
	   
			   $query = $this->obj->db->query($select);                
			   
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
					   // calling same function where we are (recursive) to check if menu item has submenu
					   $html .= $this->_print_menu($item['id']);
					   $html .= '</li>';
			   }
	   
			   $html .= '</ul>';
			   return $html;
	   }               		
	}


?>