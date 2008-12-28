<?php

	class News_Model extends Model {
		var $tmppages;
		var $_cats;
		var $cat_fields = array(
				'id' => '',
				'pid' => '',
				'title' => '', 
				'icon' => '', 
				'desc' => '', 
				'date'  => '',
				'username'  => '',
				'lang'  => '',
				'weight'  => '',
				'status'  => '',
				'acces'  => ''
			);
		
		function News_Model()
		{
			parent::Model();
			
			$this->table = 'news';
		}
		
		function get_total()
		{
			$this->db->where('lang', $this->user->lang );
			$this->db->from('news');
					
			return $this->db->count_all_results();
			
		}
		
		function get_total_published()
		{
			$this->db->where('lang', $this->user->lang );
			$this->db->where('status', 1 );
			$this->db->from('news');
					
			return $this->db->count_all_results();
		
		}

		function generate_uri($title)
		{
			$raw_uri = format_title($title);
			$uri = format_title($title);
			
			$i = 1;
			while($this->get_news($uri))
			{
				$uri = $raw_uri . '-' . $i;
				$i++;
			}
			
			
			return $uri;
		}
		
		function get_news_list($data = null)
		{
			if (is_null($data))
			{
				$data = array('lang' => $this->user->lang);
			}
			else
			{
				$data['lang'] = $this->user->lang;
			}
			$this->db->order_by('cat, ordering, date DESC');
			$this->db->where($data);
			$query = $this->db->get($this->table);
			if ( $query->num_rows() > 0 )
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
			
		}
		function get_news($data)
		{
			
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
			
			$this->db->order_by('cat, ordering, date DESC');
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
		
		function get_comments($news_id, $limit = null, $start = null, $activeonly = 1)
		{
			if ($activeonly == 1)
			{
				$this->db->where('status', 1);
			}
			$this->db->where('news_id', $news_id);
			$this->db->order_by('id');
			
			$query = $this->db->get('news_comments', $limit, $start);
			if ( $query->num_rows() > 0 )
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
			
		}
		
		function count_comments($news_id, $activeonly = 1)
		{
					if ($activeonly == 1)
			{
				$this->db->where('status', 1);
			}
			$this->db->where('news_id', $news_id);
			$this->db->order_by('id');
			
			$query = $this->db->get('news_comments');
			
			return $query->num_rows();

		}
		
		function news_list($start = null, $limit = null)
		{
			
			$this->db->where(array('lang' => $this->user->lang));
			$this->db->order_by('cat, ordering, date DESC');
			
			$query = $this->db->get($this->table, $limit, $start);
			
			if ( $query->num_rows() > 0 )
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
		}

		function latest_news($limit = 10)
		{
			$this->db->where('lang', $this->user->lang);
			$this->db->order_by('cat, ordering, date DESC');
			$this->db->limit($limit);
			$query = $this->db->get($this->table);
			
			if ( $query->num_rows() > 0 )
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
		}

		function attach($id, $image_data)
		{
			$data = array('src_id' => $id, 'module' => 'news', 'file' => $image_data['file_name']);
			$this->db->insert('images', $data);
			return $this->db->insert_id();
		}
		
		
	function save_cat($id = false)
	{
		foreach ($this->cat_fields as $key=>$val)
		{
			$data[$key] = $this->input->post($key);
		}
		
		
		
		if ($id)
		{
			$this->db->where('id', $id);
			$this->db->update('news_cat', $data);
		}
		else
		{
			$data['date'] = mktime();
			$data['username'] = $this->user->username;
			
			$this->db->insert('news_cat', $data);
		}
	}
	
	function get_cat($data)
	{
		if (is_array($data))
		{
			$this->db->where($data);
		}
		else
		{
			$this->db->where('id', $data);
		}

		$query = $this->db->get('news_cat');
		if ($query->num_rows() > 0)
		{
		return $query->row_array();
		}
		else
		{
		return false;
		}
	}
	function get_cattree($parent = 0, $level = 0)
	{

		$this->db->where(array('pid' => $parent, 'lang' => $this->user->lang));
		$this->db->orderby('pid, weight');
		$query = $this->db->get('news_cat');
		

		// display each child
		if ($query->num_rows() > 0 )
		{
			foreach ($query->result_array() as $row) {
			// indent and display the title of this child
				$row['level'] = $level;
				$this->_cats[] = $row;
				$this->get_cattree($row['id'], $level+1);
			}
		}
		return $this->_cats;
	}
	
	function get_catlist_by_pid($pid = 0, $start = null, $limit = null)
	{

		$this->db->where(array('pid' => $pid, 'lang' => $this->user->lang));
		$this->db->orderby('pid, weight');
		$query = $this->db->get('news_cat', $limit, $start);
		if ($query->num_rows() > 0 )
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
		
	}
	
	function get_catlist($start = null, $limit = null)
	{
		
		$cat_tree = $this->get_cattree();
		
		
		if (is_array($cat_tree))
		{
			if (is_null($start)) $start = 0;
			
			if (!is_null($limit)) 
			{
				return array_slice($cat_tree, $start, $limit);
			}
			else
			{
				return  array_slice($cat_tree, $start);
			}
		}
		else
		{
			return false;
		}

	}
	
	function delete_cat($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('news_cat');
	}
	
	function get_totalcat()
	{
		return $this->db->count_all('news_cat');
	}

	function move_cat($direction, $id)
	{

		$query = $this->db->get_where('news_cat', array('id' => $id));
		
		
		if ($row = $query->row())
		{
			$parent_id = $row->pid;
			
		}
		else
		{
			$parent_id = 0;
		}
		
		
		$move = ($direction == 'up') ? -1 : 1;
		$this->db->where(array('id' => $id));
		
		$this->db->set('weight', 'weight+'.$move, FALSE);
		$this->db->update('news_cat');
		
		$this->db->where(array('id' => $id));
		$query = $this->db->get('news_cat');
		$row = $query->row();
		$new_ordering = $row->weight;
		
		
		if ( $move > 0 )
		{
			$this->db->set('weight', 'weight-1', FALSE);
			$this->db->where(array('weight <=' => $new_ordering, 'id <>' => $id, 'pid' => $parent_id, 'lang' => $this->user->lang));
			$this->db->update('news_cat');
		}
		else
		{
			$this->db->set('weight', 'weight+1', FALSE);
			$where = array('weight >=' => $new_ordering, 'id <>' => $id, 'pid' => $parent_id, 'lang' => $this->user->lang);
			
			$this->db->where($where);
			$this->db->update('news_cat');
		}
		//reordinate
		$i = 0;
		$this->db->order_by('weight');
		$this->db->where(array('pid' => $parent_id, 'lang' => $this->user->lang));
		
		$query = $this->db->get('news_cat');
		
		if ($rows = $query->result())
		{
			foreach ($rows as $row)
			{
				$this->db->set('weight', $i);
				$this->db->where('id', $row->id);
				$this->db->update('news_cat');
				$i++;
			}
		}
		//clear cache
		
	}
			
	function move($direction, $id)
	{

		$query = $this->db->get_where('news', array('id' => $id));
		
		
		if ($row = $query->row())
		{
			$cat = $row->cat;
			
		}
		else
		{
			$cat = 0;
		}
		
		
		$move = ($direction == 'up') ? -1 : 1;
		$this->db->where(array('id' => $id));
		
		$this->db->set('ordering', 'ordering+'.$move, FALSE);
		$this->db->update('news');
		
		$this->db->where(array('id' => $id));
		$query = $this->db->get('news');
		$row = $query->row();
	
		$new_ordering = $row->ordering;
		
		
		if ( $move > 0 )
		{
			$this->db->set('ordering', 'ordering-1', FALSE);
			$this->db->where(array('ordering <=' => $new_ordering, 'id <>' => $id, 'cat' => $cat, 'lang' => $this->user->lang));
			$this->db->update('news');
		}
		else
		{
			$this->db->set('ordering', 'ordering+1', FALSE);
			$where = array('ordering >=' => $new_ordering, 'id <>' => $id, 'cat' => $cat, 'lang' => $this->user->lang);
			
			$this->db->where($where);
			$this->db->update('news');
		}
		//reordinate
		$i = 0;
		$this->db->order_by('ordering');
		$this->db->where(array('cat' => $cat, 'lang' => $this->user->lang));
		
		$query = $this->db->get('news');
		
		if ($rows = $query->result())
		{
			foreach ($rows as $row)
			{
				$this->db->set('ordering', $i);
				$this->db->where('id', $row->id);
				$this->db->update('news');
				$i++;
			}
		}
		//clear cache
		$this->cache->remove('news'.$this->user->lang, 'news');
	}
		
}


?>