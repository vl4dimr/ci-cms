<?php
/*
 * $Id: links_model.php 1083 2008-11-23 09:31:10Z hery $
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Links_model extends Model {
	var $tables = array(
		'links_links' => array (
			'fields' => array (
				'id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				  ),
				'title' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					  ),
				'url' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					  ),
				'lang' => array(
					 'type' => 'CHAR',
					 'constraint' => '5',
					  ),
				'cat' => array(
					 'type' =>'INT',
					 'default' => '0'
					  ),

				'description' => array(
					'type' => 'TEXT',
					'null' => TRUE,
				),
				'hit' => array(
					'type' => 'INT',
					'constraint' => 5,
					'default' => '0',
				),
				'username' => array(
					'type' =>'VARCHAR',
					'constraint' => '250',
					'default' => '',
				),
				'icon' => array(
					'type' =>'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'date' => array(
					'type' =>'INT',
					'default' => '0'
				)
			),
			'keys' => array (
				'id' => TRUE,
				'title' => FALSE
			)
		),
		'links_cat' => array(
			'fields' => array(
				'id' => array(
					 'type' => 'INT',
					 'constraint' => 5,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
				  ),
				'title' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					  ),
				'lang' => array(
					 'type' => 'CHAR',
					 'constraint' => '5',
					  ),
				'pid' => array(
					 'type' =>'INT',
					 'default' => '0',
					  ),

				'description' => array(
					'type' => 'TEXT',
					'null' => TRUE,
				),
				'weight' => array(
					'type' => 'INT',
					'default' => '0',
				),
				'icon' => array(
					'type' =>'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'username' => array(
					'type' =>'VARCHAR',
					'constraint' => '250',
					'default' => '',
				),
				'date' => array(
					'type' =>'INT',
					'default' => '0',
				)
			),
			'keys' => array (
				'id' => TRUE,
				'title' => FALSE
			)
		)
	);
	var $_cats;
	function Links_model()
	{
		parent::Model();
	}
	
	
	function get_link($where, $params = array())
	{

		$default_params = array
		(
			'order_by' => 'title',
			'limit' => 5,
			'start' => null,
			'limit' => null
		);
		
		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		
		if (is_array($where))
		{
			$this->db->where($where);
		}
		else
		{
			$this->db->where('id', $where);
			
		}

		$this->db->order_by($params['order_by']);
		$this->db->limit($params['limit'], $params['start']);
	
		$query = $this->db->get('links_links');

		if ($query->num_rows() == 0 )
		{
			return false;
		}
		else 
		{
			return $query->row_array();
		}
	}
	
	function get_links($where, $start = null, $per_page = null)
	{
		$default_params = array
		(
			'order_by' => 'title',
			'limit' => 5,
			'start' => null,
			'limit' => null
		);
		
		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		
		if (is_array($where))
		{
			$this->db->where($where);
		}
		else
		{
			$this->db->where('cat', $where);
			
		}

		$this->db->order_by($params['order_by']);
		$this->db->limit($params['limit'], $params['start']);
	
		$query = $this->db->get('links_links');

		if ($query->num_rows() == 0 )
		{
			return false;
		}
		else
		{
			return $query->result_array();
		}
	}

	function get_totallinks($cat = 0)
	{

		$this->db->where('cat', $cat);
		$this->db->where('lang', $this->user->lang);
		return $this->db->count_all_results('links_links');
	}
	
	
	function save_cat($id = false)
	{
		foreach ($this->tables['links_cat']['fields'] as $key=>$val)
		{
			if ($this->input->post($key))
			{
				$data[$key] = $this->input->post($key);
			}
		}
		
		if ($id)
		{
			$this->db->where('id', $id);
			$this->db->update('links_cat', $data);
		}
		else
		{
			$data['date'] = mktime();
			$data['username'] = $this->user->username;
			
			$this->db->insert('links_cat', $data);
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

		$query = $this->db->get('links_cat');
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
		$query = $this->db->get('links_cat');
		

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
		$query = $this->db->get('links_cat', $limit, $start);
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
		$this->db->delete('links_cat');
	}
	
	function delete_link($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('links_links');
	}
	
	function get_totalcat($parent = null)
	{
		$this->db->where('lang', $this->user->lang);
		if (!is_null($parent))
		{
			$this->db->where('pid', $parent);
		}
		return $this->db->count_all_results('links_cat');
	}

	function move_cat($direction, $id)
	{

		$query = $this->db->get_where('links_cat', array('id' => $id));
		
		
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
		$this->db->update('links_cat');
		
		$this->db->where(array('id' => $id));
		$query = $this->db->get('links_cat');
		$row = $query->row();
		$new_ordering = $row->weight;
		
		
		if ( $move > 0 )
		{
			$this->db->set('weight', 'weight-1', FALSE);
			$this->db->where(array('weight <=' => $new_ordering, 'id <>' => $id, 'pid' => $parent_id, 'lang' => $this->user->lang));
			$this->db->update('links_cat');
		}
		else
		{
			$this->db->set('weight', 'weight+1', FALSE);
			$where = array('weight >=' => $new_ordering, 'id <>' => $id, 'pid' => $parent_id, 'lang' => $this->user->lang);
			
			$this->db->where($where);
			$this->db->update('links_cat');
		}
		//reordinate
		$i = 0;
		$this->db->order_by('weight');
		$this->db->where(array('pid' => $parent_id, 'lang' => $this->user->lang));
		
		$query = $this->db->get('links_cat');
		
		if ($rows = $query->result())
		{
			foreach ($rows as $row)
			{
				$this->db->set('weight', $i);
				$this->db->where('id', $row->id);
				$this->db->update('links_cat');
				$i++;
			}
		}
		//clear cache
		
	}


	
	function save_link($id)
	{
		foreach ($this->tables['links_links']['fields'] as $key=>$val)
		{
			if ($this->input->post($key))
			{
				$data[$key] = $this->input->post($key);
			}
		}
		
		if ($id)
		{
			$this->db->where('id', $data['id']);
			$this->db->update('links_links', $data);
		}
		else
		{
			$data['date'] = mktime();
			$data['hit'] = 0;
			$data['username'] = $this->user->username;
			
			$this->db->insert('links_links', $data);
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
	
	function update_link( $where, $data)
	{
		if (!is_array($where))
		{
			$where = array('id' => $where);
		}

		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				$this->db->set($key, $val, FALSE);
			}
			$this->db->where($where);
			$this->db->update('links_links');

		}
	}
}