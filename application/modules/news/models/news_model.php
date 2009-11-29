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
				'acces'  => '',
				'uri' => ''
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
		
		function get_total_published($params)
		{
			$this->db->where($params['where'] );
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
		
		function generate_cat_uri($title)
		{
			$raw_uri = format_title($title);
			$uri = format_title($title);
			
			$i = 1;
			while($this->get_cat(array('uri' => $uri)))
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
			$params = array('order_by' => 'cat, ordering, date DESC', 'where' => $data);
			return $this->get_list($params);
			
		}
		function get_news($data)
		{
			
			$this->db->select("news.*, news_cat.title as category, news_cat.uri as cat_uri");
			$this->db->from("news");
			$this->db->join("news_cat", "news.cat = news_cat.id", "left");
			
			if ( is_array($data) )
			{
				foreach ($data as $key => $value)
				{
					$this->db->where($key, $value);
				}
			}
			else
			{
				$this->db->where('news.uri', $data);
			}
			
			$this->db->order_by('news.cat');
			$this->db->order_by('news.ordering');
			$this->db->order_by('news.date', 'DESC');

			$query = $this->db->get();
			
			
			if ( $query->num_rows() > 0 )
			{
				$row = $query->row_array();
				$this->db->where('news_id', $row['id']);
				$this->db->order_by('tag');
				$query = $this->db->get('news_tags');
				if ($query->num_rows() > 0)
				{
					$row['tags'] = $query->result_array();
				}
				return $row;
			
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
		
		function get_list($params = array())
		{
			$default_params = array
			(
				'order_by' => 'id DESC',
				'limit' => 20,
				'start' => 0,
				'having' => array(),
				'where' => array()
			);
			
			foreach ($default_params as $key => $value)
			{
				$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
			}
			$hash = md5(serialize($params));
			if(!$result = $this->cache->get('get_news_list' . $hash, 'news_list'))
			{
				$this->db->select('news_cat.title as category, news_cat.uri as cat_uri, news.*');
				$this->db->where($params['where']);
				$this->db->having($params['having']);
				$this->db->order_by($params['order_by']);
				$this->db->limit($params['limit'], $params['start']);
				$this->db->from('news');
				$this->db->join('news_cat', 'news_cat.id = news.cat', 'left');
				$query = $this->db->get();
				if ( $query->num_rows() > 0 )
				{
					
					foreach ($query->result_array() as $row)
					{
						$this->db->order_by('id DESC');
						$this->db->where(array('src_id' => $row['id'], 'module' => 'news'));
						$query2 = $this->db->get('images');
						$row['image'] = $query2->row_array();
						
						if($page_break_pos = strpos($row['body'], "<!-- page break -->"))
						{
							$row['summary'] = substr($row['body'], 0, $page_break_pos);
						}
						else
						{
							$row['summary'] = character_limiter(strip_tags($row['body']), 200);
						}

						$return[] = $row;
					}
					$result = $return;
				}
				else
				{
					$result = false;
				}
				
				$this->cache->save('get_news_list' . $hash, $result, 'news_list', 0);
				
			}
			return $result;
		}
		
		
	function news_list($start =  null, $limit = null)
	{
		
		$params = array('where' => array('lang' => $this->user->lang), 'order_by' => 'cat, ordering, date DESC', 'limit' => $limit, 'start' => $start);
		return $this->get_list($params);
	}

	function delete($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->delete('news');
		
		$this->db->where('src_id', $id);
		$this->db->set('src_id', 0);
		$query = $this->db->update('images');
		$this->cache->remove_group('news_list');
	}
	
	function latest_news($limit = 10)
	{
		$params = array('where' => array('lang' => $this->user->lang), 'order_by' => 'id DESC', 'limit' => $limit);
		return $this->get_list($params);
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
			if(!$data['uri'])
			{
				$data['uri'] = $this->generate_cat_uri($data['title']);
			}
			
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
		$this->cache->remove_group('news_list');
		
	}
	
	function save()
	{
		$this->user->check_level($this->template['module'], LEVEL_ADD);


		

		
		$fields = array('id', 'cat', 'title', 'body', 'status', 'allow_comments', 'lang', 'notify');
		$data = array();
		
		foreach ($fields as $field)
		{
			$data[$field] = $this->input->post($field);
		}

		if($date = $this->input->post('date')) {
			$day = substr($date, 0,2);
			$month = substr($date, 3, 2);
			$year = substr($date, 6, 4);

			$data['date'] = mktime(date("H"), date("i"), date("s"), $month, $day, $year);
		}
		else
		{
			$data['date'] = mktime();
		}
		
		// if uri is provided
		if ($this->input->post('uri'))
		{
			$data['uri'] = $this->input->post('uri');
		}
		else
		{
			$data['uri'] = $this->news->generate_uri($this->input->post('title'));
		}
	

	
		if($id = $this->input->post('id'))
		{

			//fixing missing uri
			/*
			$news = $this->news->get_news(array('news.id' => $id));
			
			if (!$news['uri']) ($data['uri'] = $this->news->generate_uri($this->input->post('title')));
			*/
			$this->user->check_level($this->template['module'], LEVEL_EDIT);
		
			//update
			$this->db->where('id', $id);
			$this->db->update('news', $data);
		}
		else
		{
			$this->user->check_level($this->template['module'], LEVEL_ADD);
		
			$data['author'] = $this->user->username;
			$data['email'] = $this->user->email;
			$this->db->insert('news', $data);
			$id = $this->db->insert_id();
			//insert
		}	
		
		//tags
		if($this->input->post('id'))
		{
			$this->db->delete('news_tags', array('news_id' => $id));
		}
		if($tags = $this->input->post('tags'))
		{
			foreach($tags as $tag)
			{
				if($tag)
				{
				$datatag = array('news_id' => $id, 'tag' => $tag, 'uri' => format_title($tag));
				$this->db->insert('news_tags', $datatag);
				}
			}
		}
		$this->cache->remove_group('news_list');
		return $id;
	}

	function get_tags($params = array())
	{
		$default_params = array
		(
			'order_by' => 'tag',
			'limit' => null,
			'start' => null,
			'where' => array()
		);
		
		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		
		$this->db->where($params['where']);
		$this->db->limit($params['limit'], $params['start']);
		
		$this->db->select('count(tag) as cnt, news_tags.*');
		$this->db->group_by("tag");
		$this->db->order_by($params['order_by']);
		$query = $this->db->get("news_tags");
		if ($query->num_rows() == 0 )
		{
			return false;
		}
		else
		{
			return $query->result_array();
		}
		
	}
	
	function get_news_by_tag($params = array())
	{
		$default_params = array
		(
			'order_by' => 'news.ordering, news.id',
			'limit' => null,
			'start' => null,
			'where' => array()
		);
		
		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		
		$hash = md5(serialize($params));
		if(!$result = $this->cache->get('get_news_by_tag' . $hash, 'news_list'))
		{
			$this->db->where($params['where']);
			$this->db->limit($params['limit'], $params['start']);
			$this->db->order_by($params['order_by']);

			$this->db->select('news_tags.uri, news_tags.tag, news.*');
			$this->db->from('news_tags');
			$this->db->join('news', 'news_tags.news_id=news.id',  'left');
			
			
			$query = $this->db->get();


			if ($query->num_rows() > 0)
			{
				foreach ($query->result_array() as $row)
				{
					$this->db->order_by('id DESC');
					$this->db->where(array('src_id' => $row['id'], 'module' => 'news'));
					$query2 = $this->db->get('images');
					$row['image'] = $query2->row_array();
					
					if($page_break_pos = strpos($row['body'], "<!-- page break -->"))
					{
						$row['summary'] = substr($row['body'], 0, $page_break_pos);
					}
					else
					{
						$row['summary'] = character_limiter(strip_tags($row['body']), 200);
					}

					$return[] = $row;
				}
				$result = $return;
			}
			else
			{
				$result = false;
			}
			$this->cache->save('get_news_by_tag', $result, 'news_list', 0 );
		}
		return $result;
	}
	
	function get_total_news_by_tag($params = array())
	{
		$default_params = array
		(
			'order_by' => 'news.ordering, news.id',
			'start' => null,
			'where' => array()
		);
		
		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		$hash = md5(serialize($params));
		if(!$result = $this->cache->get('get_total_news_by_tag' . $hash, 'news_list'))
		{
			$this->db->where($params['where']);
			$this->db->order_by($params['order_by']);

			$this->db->select(' count(' . $this->db->dbprefix('news') . '.id) as cnt');
			$this->db->from('news');
			$this->db->join('news_tags', 'news_tags.news_id=news.id',  'left');
			$query = $this->db->get();
			
			$row = $query->row_array();
			$result = $row['cnt'];
			$this->cache->save('get_total_news_by_tag', $result, 'news_list', 0 );
		}
		return $result;
	}
}


