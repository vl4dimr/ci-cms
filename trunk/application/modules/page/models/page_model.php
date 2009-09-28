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
		
		function move($direction, $id)
		{

			$query = $this->db->get_where('pages', array('id' => $id));
			
			
			if ($row = $query->row())
			{
				$parent_id = $row->parent_id;
				
			}
			else
			{
				$parent_id = 0;
			}
			
			
			$move = ($direction == 'up') ? -1 : 1;
			$this->db->where(array('id' => $id));
			
			$this->db->set('weight', 'weight+'.$move, FALSE);
			$this->db->update('pages');
			
			$this->db->where(array('id' => $id));
			$query = $this->db->get('pages');
			$row = $query->row();
			$new_ordering = $row->weight;
			
			
			if ( $move > 0 )
			{
				$this->db->set('weight', 'weight-1', FALSE);
				$this->db->where(array('weight <=' => $new_ordering, 'id <>' => $id, 'parent_id' => $parent_id, 'lang' => $this->user->lang));
				$this->db->update('pages');
			}
			else
			{
				$this->db->set('weight', 'weight+1', FALSE);
				$where = array('weight >=' => $new_ordering, 'id <>' => $id, 'parent_id' => $parent_id, 'lang' => $this->user->lang);
				
				$this->db->where($where);
				$this->db->update('pages');
			}
			//reordinate
			$i = 0;
			$this->db->order_by('weight');
			$this->db->where(array('parent_id' => $parent_id, 'lang' => $this->user->lang));
			
			$query = $this->db->get('pages');
			
			if ($rows = $query->result())
			{
				foreach ($rows as $row)
				{
					$this->db->set('weight', $i);
					$this->db->where('id', $row->id);
					$this->db->update('pages');
					$i++;
				}
			}
			//clear cache
			$this->cache->remove('pagelist'.$this->user->lang, 'page');				
			
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
				$row = $query->row_array();
				$row['options'] = unserialize($row['options']);
				return $row;
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
				$row['level'] = $level;
				
				$this->tmppages[] = $row;
			// call this function again to display this
			// child's children
				$this->list_pages($row['id'], $level+1);
			}
			return $this->tmppages;
		}
		
		function get_subpages($id, $limit = null)
		{
			$this->db->order_by('weight');
			$this->db->order_by('id', 'DESC');
			$this->db->where('parent_id', $id);
			$this->db->where('lang', $this->user->lang);
			$query = $this->db->get('pages', $limit);
			return $query->result_array();
		}

		function get_nextpage(&$page)
		{
			
			$this->db->where('active', 1);
			$this->db->where('parent_id', $page['parent_id']);
			$this->db->where('parent_id <> ', '0');
			$this->db->where('lang', $this->user->lang);
			$this->db->order_by('weight');
			
			$query = $this->db->get('pages');
			
			
			if($familypages = $query->result_array())
			{
				foreach($familypages as $key=>$val) {
					if($val['id'] == $page['id']) {
						$id = $key;
					}
				}
				if(($id - 1) >= 0) {
					// Ny mialoha
					$page['previous_page'] = $familypages[$id - 1];
				}
				if ( ( $id + 1 ) < count( $familypages ) ) {
					// Ny manaraka
					$page['next_page'] = $familypages[$id + 1];
				}
			}		
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
		
		function update($id, $data)
		{
			if(!is_array($data))
			{
				return false;
			}
			$this->db->where('id', $id);
			$this->db->update('pages', $data);
		}
		
		function get_comments($params = array())
		{
			$default_params = array
			(
				'order_by' => 'id DESC',
				'limit' => null,
				'start' => null,
				'where' => array()
			);
			
			foreach ($default_params as $key => $value)
			{
				$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
			}
			if ($params['where'])
			{
				$this->db->where($params['where']);
			}
			$this->db->order_by($params['order_by']);
			
			$query = $this->db->get('page_comments', $params['limit'], $params['start']);
			
			if ( $query->num_rows() > 0 )
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
			
		}
		
		function get_images($page_id)
		{
			$this->db->where(array('src_id' => $page_id, 'module' => 'page'));
			$uqery = $this->db->get('images');
			if ( $query->num_rows() > 0 )
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
			
		}
		
	}


?>