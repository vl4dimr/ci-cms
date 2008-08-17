<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Download_model extends Model {
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
		
	var $file_fields = array(
			'id'  => '',
			'cat'  => '',
			'weight'  => '',
			'link'  => '',
			'linktype'  => '',
			'lang'   => '',
			'pic'   => '',
			'title'   => '',
			'desc'   => '',
			'username'   => '',
			'keywords'   => '',
			'submitter'  => '',
			'date'  => '',
			'hit'  => '',
			'valid'   => '',
			'acces'  => '',
			'icon'   => ''
		);
	var $_cats;
	function Download_model()
	{
		parent::Model();
		
		$this->table = 'pages';
	}
	
	
	function get_file($data)
	{
		if (is_array($data))
		{
			$this->db->where($data);
		}
		else
		{
			$this->db->where('id', $data);
		}

		$query = $this->db->get('download_file');
		if ($query->num_rows() > 0)
		{
		return $query->row_array();
		}
		else
		{
		return false;
		}
	}
	
	function get_files($cat = 0)
	{

		$this->db->where('cat', $cat);
		$this->db->where('lang', $this->user->lang);

		$query = $this->db->get('download_file');
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}

	function get_totalfiles($cat = 0)
	{

		$this->db->where('cat', $cat);
		$this->db->where('lang', $this->user->lang);
		return $this->db->count_all_results('download_file');
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
			$this->db->update('download_cat', $data);
		}
		else
		{
			$data['date'] = mktime();
			$data['username'] = $this->user->username;
			
			$this->db->insert('download_cat', $data);
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

		$query = $this->db->get('download_cat');
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
		$query = $this->db->get('download_cat');
		

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
		$query = $this->db->get('download_cat', $limit, $start);
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
		$this->db->delete('download_cat');
	}
	
	function get_totalcat()
	{
		return $this->db->count_all('download_cat');
	}

	function move_cat($direction, $id)
	{

		$query = $this->db->get_where('download_cat', array('id' => $id));
		
		
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
		$this->db->update('download_cat');
		
		$this->db->where(array('id' => $id));
		$query = $this->db->get('download_cat');
		$row = $query->row();
		$new_ordering = $row->weight;
		
		
		if ( $move > 0 )
		{
			$this->db->set('weight', 'weight-1', FALSE);
			$this->db->where(array('weight <=' => $new_ordering, 'id <>' => $id, 'pid' => $parent_id, 'lang' => $this->user->lang));
			$this->db->update('download_cat');
		}
		else
		{
			$this->db->set('weight', 'weight+1', FALSE);
			$where = array('weight >=' => $new_ordering, 'id <>' => $id, 'pid' => $parent_id, 'lang' => $this->user->lang);
			
			$this->db->where($where);
			$this->db->update('download_cat');
		}
		//reordinate
		$i = 0;
		$this->db->order_by('weight');
		$this->db->where(array('pid' => $parent_id, 'lang' => $this->user->lang));
		
		$query = $this->db->get('download_cat');
		
		if ($rows = $query->result())
		{
			foreach ($rows as $row)
			{
				$this->db->set('weight', $i);
				$this->db->where('id', $row->id);
				$this->db->update('download_cat');
				$i++;
			}
		}
		//clear cache
		
	}
	
	
	
}