<?php
/*
 * $Id: annuaire_model.php 1083 2008-11-23 09:31:10Z hery $
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tags_model extends Model {
	var $tables = array(
		'tag_items' => array (
			'fields' => array (
				'id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				  ),
				'tag' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '100',
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
				'srcid' => array(
					 'type' =>'INT',
					 'default' => '0',
					  ),

				'module' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '100',
					  ),
				'summary' => array(
					'type' => 'TEXT',
					'null' => TRUE,
				),
				'hit' => array(
					'type' => 'INT',
					'constraint' => 5,
					'default' => '0',
				)
			),
			'keys' => array (
				'id' => TRUE,
				'tag' => FALSE
			)
		)
	);
	var $_cats;
	function Tags_model()
	{
		parent::Model();
	}
	
	function get_cloud()
	{
		$this->db->select("COUNT(tag) as ctag, tag");
		$this->db->from("tag_items");
		$this->db->order_by("ctag DESC");
		$this->db->group_by("tag");
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			$rows = $query->result_array();
			foreach ($rows as $row)
			{
				$tagcount[$row['tag']] = $row['ctag'];
			}
			
			$min_size = 100; //%
			$max_size = 200; //%
			$max = max($tagcount);
			$min = min($tagcount);
			$spread = $max - $min;
			if (0 == $spread) { // we don't want to divide by zero
			    $spread = 1;
			}
			// determine the font-size increment
			// this is the increase per tag quantity (times used)
			$step = ($max_size - $min_size)/($spread);
			foreach ($rows as $row)
			{
				$row['max'] = $max;
				$row['min'] = $min;
				$size = $min_size + (($row['ctag'] - $min) * $step);
				$row['size'] = $size;
				$tags[] = $row;
			}
			
			return $tags;
		}
		else
		{
			return false;
		}
		
	}
	
	
	function get_tag($where, $params = array())
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
	
		$query = $this->db->get('tag_items');

		if ($query->num_rows() == 0 )
		{
			return false;
		}
		else 
		{
			return $query->row_array();
		}
	}
	
	function get_taglist($where, $params = array())
	{
		$default_params = array
		(
			'order_by' => 'title',
			'limit' => 5,
			'start' => null,
			'limit' => null
		);
		
		$this->db->select("tag, COUNT(tag) as ctag");
		$this->db->group_by("tag");
		
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
			$this->db->where('tag', $where);
			
		}
		$this->db->order_by($params['order_by']);
		$this->db->limit($params['limit'], $params['start']);
	
		$query = $this->db->get('tag_items');

		if ($query->num_rows() == 0 )
		{
			return false;
		}
		else
		{
			return $query->result_array();
		}
	
	}
	function get_tags($where, $params = array())
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
			$this->db->where('tag', $where);
			
		}

		$this->db->order_by($params['order_by']);
		$this->db->limit($params['limit'], $params['start']);
	
		$query = $this->db->get('tag_items');

		if ($query->num_rows() == 0 )
		{
			return false;
		}
		else
		{
			return $query->result_array();
		}
	}

	function get_totaltags()
	{
		$this->db->select("COUNT(tag)");
		$this->db->group_by("tag");
		$this->db->where('lang', $this->user->lang);
		return $this->db->count_all_results('tag_items');
	}
	
	
	function save_tag($data)
	{

		$this->db->insert('tag_items', $data);
		
	}
	
	function delete($where)
	{
		if (!is_array($where))
		{
			$where = array('id' => $where);
		}
		$this->db->where($where);
		$this->db->delete('tag_items');
	}
	
}