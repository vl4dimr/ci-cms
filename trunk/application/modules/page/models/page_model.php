<?php

	class Page_Model extends Model {
		var $tmppages;
		function Page_model()
		{
			parent::Model();
			
			$this->table = 'pages';
		}
		
		function get_total()
		{
			$this->db->where('lang', $this->user->lang );
			$this->db->from('pages');
					
			return $this->db->count_all_results();
			
		}
		function get_tree ($parent = 0, $level = 0)
		{
			// retrieve all children of $parent
			$this->db->where(array('parent_id' => $parent, 'lang' => $this->user->lang, 'active' => 1));
			$this->db->orderby('parent_id, weight');
			$query = $this->db->get('pages');
			

			// display each child
			foreach ($query->result_array() as $row) {
			// indent and display the title of this child
			$this->pagetree[] = array(
				'level' =>$level, 
				'title' => $row['title'],
				'parent_id' => $row['parent_id'],
				'id' => $row['id'],
				'uri' => $row['uri']
			);
			// call this function again to display this
			// child's children
				$this->get_tree($row['id'], $level+1);
			}
			return $this->pagetree;
		}
		
		function get_page($data)
		{
			$this->db->select('*');
			
			if ( is_array($data) )
			{
				foreach ($data as $key => $value)
				{
					$this->db->where($key, $value);
				}
			}
			else
			{
				$this->db->where('uri', $data);
			}
			
			$query = $this->db->get($this->table, 1);
			
			if ( $query->num_rows() == 1 )
			{
				return $query->row_array();
			}
			else
			{
				return false;
			}
		}
		
		function list_pages($parent = 0, $level = 0)
		{
			
			$this->db->where(array('parent_id' => $parent, 'lang' => $this->user->lang));
			$this->db->orderby('parent_id, weight');
			$query = $this->db->get('pages');
			

			// display each child
			foreach ($query->result_array() as $row) {
			// indent and display the title of this child
			$this->tmppages[] = array(
				'level' =>$level, 
				'title' => $row['title'],
				'parent_id' => $row['parent_id'],
				'id' => $row['id'],
				'uri' => $row['uri'],
				'active' => $row['active']
			);
			// call this function again to display this
			// child's children
				$this->list_pages($row['id'], $level+1);
			}
			return $this->tmppages;
		}

		function new_pages($limit = 10)
		{
			$this->db->select('id, title, uri, active');
			$this->db->order_by('id', 'DESC');
			$this->db->limit($limit);
			$query = $this->db->get($this->table);
			
			$pages = array();
			
			if ( $query->num_rows() > 0 )
			{
				$pages = $query->result_array();
			}
			
			return $pages;
		}

		function attach($id, $image_data)
		{
			$data = array('src_id' => $id, 'module' => 'page', 'file' => $image_data['file_name']);
			$this->db->insert('images', $data);
			return $this->db->insert_id();
		}
	}


?>