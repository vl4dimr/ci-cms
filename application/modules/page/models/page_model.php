<?php

class Page_Model extends Model {
	var $tmppages;
	var $fields;
	function Page_model()
	{
		parent::Model();
		
		$this->table = 'pages';
		
		$this->fields = array(
			'pages' => array(
					'id' 				=> '',
					'uri'				=> '',
					'title'				=> '',
					'parent_id'			=> 0,
					'meta_keywords'		=> '',
					'meta_description'	=> '',
					'body'				=> '',
					'active'			=> 1,
					'lang'				=> $this->user->lang,
					'options'			=> '',
					'email'				=> $this->user->email,
					'g_id'				=> '0'
					
				)
		);
		
	}
	
	function get_total($params = array())
	{
		$default_params = array
		(
			'where' => array('lang' => $this->user->lang)
		);
		
		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		if ($params['where'])
		{
			$this->db->where($params['where']);
		}
		$this->db->select('count(id)');
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
		$this->cache->remove_group('page_list');
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
			if($page_break_pos = strpos($row['body'], "<!-- page break -->"))
			{
				$row['summary'] = substr($row['body'], 0, $page_break_pos);
			}
			else
			{
				$row['summary'] = character_limiter(strip_tags($row['body']), 200);
			}
			
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
		$this->cache->remove_group('image_list');
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
		$this->cache->remove_group('page_list');
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
		$default_params = array
		(
			'order_by' => 'file',
			'limit' => 20,
			'start' => 0,
			'where' => null,
			'like' => null
		);
		
		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		
		$hash = md5(serialize($params));
		if(!$result = $this->cache->get('get_images'. $hash, 'image_list'))
		{
		
			if (!is_null($params['like']))
			{
				$this->db->like($params['like']);
			}
			if (!is_null($params['where']))
			{
				$this->db->where($params['where']);
			}
			
			$this->db->limit($params['limit'], $params['start']);

			$this->db->order_by($params['order_by']);

			$this->db->from('images');

			$query = $this->db->get();

			if ($query->num_rows() == 0 )
			{
				$result = false;
			}
			else
			{
				$result = $query->result_array();
			}
			$this->cache->save('get_images'. $hash, $result, 'image_list', 0);
		}
		return $result;
	}

	function update_image($id, $data)
	{
		$this->db->set($data);
		$this->db->where('id', $id);
		$this->db->update('images');
		$this->cache->remove_group('image_list');
	}
	
	function save($data)
	{
		$this->db->insert('pages', $data);
		$this->cache->remove_group('page_list');
		return $this->db->insert_id();
		
	}
	
	function delete($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->delete('pages');
		$this->cache->remove_group('page_list');
	}
	
	function delete_image($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->delete('images');
		$this->cache->remove_group('image_list');
	}

	/*
	* updating many images
	*/
	
	function update_images($where = array(), $data = array())
	{
		
		$this->db->where($where);
		$this->db->set($data);
		$this->db->update('images');
		$this->cache->remove_group('image_list');
	}

	function save_image($data)
	{
		$this->db->insert('images', $data);
		$this->cache->remove_group('image_list');
		return $this->db->insert_id();
		
	}
	
	function get_page_list($params)
	{
		
		$default_params = array
		(
			'select' => '*',
			'order_by' => 'id DESC',
			'limit' => null,
			'start' => null,
			'where' => null,
			'like' => null,
			'or_where' => null,
		);
		
		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		$hash = md5(serialize($params));
		if(!$result = $this->cache->get('get_page_list' . $hash, 'page_list'))
		{
			if (!is_null($params['like']))
			{
				$this->db->like($params['like']);
			}
			if (!is_null($params['where']))
			{
				$this->db->where($params['where']);
			}
			if (!is_null($params['or_where']))
			{
				$this->db->where($params['or_where']);
			}
			$this->db->order_by($params['order_by']);
			$this->db->limit($params['limit'], $params['start']);

			$this->db->select($params['select']);
			$this->db->from('pages');
			$query = $this->db->get();

			if ($query->num_rows() == 0 )
			{
				$result =  false;
			}
			else
			{
				$results = $query->result_array();
				foreach ($results as $aresult)
				{
					$aresult['children'] = 0;
					if($page_break_pos = strpos($aresult['body'], "<!-- page break -->"))
					{
						$aresult['summary'] = substr($aresult['body'], 0, $page_break_pos);
					}
					else
					{
						$aresult['summary'] = character_limiter(strip_tags($aresult['body']), 200);
					}
					$query = $this->db->query("SELECT count('id') cnt FROM " . $this->db->dbprefix('pages') . " WHERE parent_id = '" . $aresult['id'] . "'");
					
					if($query->num_rows() > 0)
					{
						$row =  $query->row_array();
						$aresult['children'] = $row['cnt'];
					}
					$result[] = $aresult;
				}
			}
			
			$this->cache->save('get_page_list' . $hash, $result, 'page_list', 0);
		}
		
		return $result;
		
	}		
	
	function get_params($id)
	{
		if($params = $this->cache->get($id, 'page_search_cache'))
		{
			return $params;
		}
		else
		{
			return false;
		}
	}

	function save_params($params)
	{
		$id = md5($params);
		if($this->cache->get($id, 'page_search_cache'))
		{
			return $id;
		}
		else
		{
		
			$this->cache->save($id, $params, 'page_search_cache', 0);
			return $id;
		}
	}
	
	function _get_parent($id)
	{
		$page = $this->get_page(array('id' => $id));
		return $this->get_page(array('id' => $page['parent_id']));
	}
	
	function get_parent_recursive($id)
	{
		$bc = false;
		// itself first
		
		if($parent = $this->_get_parent($id))
		{
			$bc[] = array(
			'title'	=> (strlen($parent['title']) > 20 )? substr($parent['title'], 0, 20) . '...': $parent['title'],
			'id'	=> $parent['id']
			);
			
		
			while($parent = $this->_get_parent($parent['parent_id']))
			{
				$bc[] = array(
				'title'	=> (strlen($parent['title']) > 20 )? substr($parent['title'], 0, 20) . '...': $parent['title'],
				'id'	=> $parent['id']
				);
			}
		}
		return $bc;
	}
	
	
	/*
	
	not ready
	//give access of page id to group
	
	function set_group($id = 0, $groups = array())
	{
		//group = array('g_id', 'level')
		$this->db->where('page_id', $id);
		$this->db->delete('page_access');
		if(is_array($group) && count($group) > 0)
		{
			foreach($groups as $group)
			{
				$this->db->insert('page_access', $group);
			}
		}
		
	}
	*/
	
}
