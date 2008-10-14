<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Search_model extends Model {
		
	function Search_model()
	{
		parent::Model();
		
	}
	
	function get_result($id)
	{
		$this->db->where("id", $id);
		$this->db->limit(1);
		$query = $this->db->get("search_results");
		if ($query->num_rows() > 0)
		{
			$serialized = $query->row_array();
			return unserialize($serialized);
		}
		else
		{
			return false;
		}
	}

	function save_result($rows)
	{
		//cleaning
		$this->db->where('s_date <=', (mktime() - 86400));
		$this->db->delete('search_results');
		
		$serialized = serialize($rows);
		$this->db->set('s_rows', $serialized);
		$this->db->set('s_date', mktime());
		$this->db->insert('search_results');
		return $this->db->insert_id();
	}
}