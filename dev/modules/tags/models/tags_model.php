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
	
	function get_tags($where, $start = null, $per_page = null)
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

		$this->db->where('lang', $this->user->lang);
		return $this->db->count_all_results('tag_items');
	}
	
	
	function save_tag($data)
	{

		
		//if tags is alredy saved then just update the title and description
		$where = array('url' => $data['url'], 'tag' => $data['tag'], 'lang' => $data['lang']);
		if ($tag = $this->get_tag($where))
		{
			$this->db->where($where);
			$this->db->update('tag_items', $data);
		}
		else
		{
			$this->db->insert('tag_items', $data);
		}
	}
	
	function delete_tag($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('tag_items');
	}
	
}