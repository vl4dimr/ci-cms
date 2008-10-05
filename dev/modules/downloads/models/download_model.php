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
		
	var $doc_fields = array(
			'id'  => '',
			'cat'  => '',
			'file_id'  => '',
			'file_link'  => '',
			'weight'  => '',
			'lang'   => '',
			'pic'   => '',
			'title'   => '',
			'desc'   => '',
			'username'   => '',
			'keywords'   => '',
			'date'  => '',
			'hit'  => '',
			'acces'  => '',
			'icon'   => '',
			'status' => '1'
		);
	var $_cats;
	function Download_model()
	{
		parent::Model();
		
		$this->table = 'pages';
	}
	
	
	function get_doc($data)
	{

		if (is_array($data))
		{
			$this->db->where($data);
		}
		else
		{
			$this->db->where('download_doc.id', $data);
			
		}

		$select1 = 'download_doc.' . join(', download_doc.' , array_keys($this->doc_fields));
		$select2 = 'download_files.name, download_files.file, download_files.size';

		$this->db->select($select1 . ', '. $select2);
		$this->db->order_by('weight');
		$this->db->from('download_doc');
		$this->db->join('download_files', 'download_doc.file_id = download_files.id');
		
		
		$query = $this->db->get('download_doc');

		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return false;
		}
	}
	
	function get_docs($cat = 0, $start = null, $per_page = null)
	{
		$select1 = 'download_doc.' . join(', download_doc.' , array_keys($this->doc_fields));
		$select2 = 'download_files.name, download_files.file, download_files.size';
		$this->db->select($select1 . ', '. $select2);
		$this->db->order_by('weight');
		$this->db->where('cat', $cat);
		$this->db->where('lang', $this->user->lang);
		$this->db->from('download_doc');
		$this->db->join('download_files', 'download_doc.file_id = download_files.id');
		$this->db->limit($per_page, $start);
		
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}

	function get_totaldocs($cat = 0)
	{

		$this->db->where('cat', $cat);
		$this->db->where('lang', $this->user->lang);
		return $this->db->count_all_results('download_doc');
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
	
	function delete_doc($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('download_doc');
	}
	
	function get_totalcat($parent = null)
	{
		$this->db->where('lang', $this->user->lang);
		if (!is_null($parent))
		{
			$this->db->where('pid', $parent);
		}
		return $this->db->count_all_results('download_cat');
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


	function move_doc($direction, $id, $cat = 0)
	{

		
		$move = ($direction == 'up') ? -1 : 1;
		$this->db->where(array('id' => $id));
		
		$this->db->set('weight', 'weight+'.$move, FALSE);
		$this->db->update('download_doc');
		
		$this->db->where(array('id' => $id));
		$query = $this->db->get('download_doc');
		$row = $query->row();
		$new_ordering = $row->weight;
		
		
		if ( $move > 0 )
		{
			$this->db->set('weight', 'weight-1', FALSE);
			$this->db->where(array('weight <=' => $new_ordering, 'id <>' => $id, 'cat' => $cat, 'lang' => $this->user->lang));
			$this->db->update('download_doc');
		}
		else
		{
			$this->db->set('weight', 'weight+1', FALSE);
			$where = array('weight >=' => $new_ordering, 'id <>' => $id, 'cat' => $cat, 'lang' => $this->user->lang);
			
			$this->db->where($where);
			$this->db->update('download_doc');
		}
		//reordinate
		$i = 0;
		$this->db->order_by('weight');
		$this->db->where(array('cat' => $cat, 'lang' => $this->user->lang));
		
		$query = $this->db->get('download_doc');
		
		if ($rows = $query->result())
		{
			foreach ($rows as $row)
			{
				$this->db->set('weight', $i);
				$this->db->where('id', $row->id);
				$this->db->update('download_doc');
				$i++;
			}
		}
		//clear cache
		
	}
	
	function save_doc($id)
	{
		foreach ($this->doc_fields as $key=>$val)
		{
			if ($this->input->post($key))
			{
				$data[$key] = $this->input->post($key);
			}
		}
		
		if ($id)
		{
			$this->db->where('id', $data['id']);
			$this->db->update('download_doc', $data);
		}
		else
		{
			$data['date'] = mktime();
			$data['username'] = $this->user->username;
			
			$this->db->insert('download_doc', $data);
		}
	
	}
	
	function save_file($data)
	{
		$this->db->insert('download_files', $data);
		$id = $this->db->insert_id();
		return $id;
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

		$query = $this->db->get('download_files');
		if ($query->num_rows() > 0)
		{
		return $query->row_array();
		}
		else
		{
		return false;
		}
	}
	
	function delete_file($row)
	{
		@unlink('./media/files/' . $row['file']);
		$this->db->where('id', $row['id']);
		$this->db->delete('download_files');	
	}
	
	function get_files($start = null, $limit = null , $order = "file")
	{
		$this->db->order_by($order);
		$query = $this->db->get('download_files', $limit, $start);
		
		return $this->template['rows'] = $query->result_array();
	
	}
	
	function get_totalfiles()
	{
		return $this->db->count_all('download_files');
	}
	
	function update_doc($data, $where)
	{
		if (!is_array($where))
		{
			$where = array('id' => $where);
		}
		
		$this->db->update('download_doc', $data, $where);
	}
}