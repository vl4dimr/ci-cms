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
		$this->load->helper('file');
	}
	
	function get_result($id)
	{
		$this->db->where("id", $id);
		$this->db->limit(1);
		$query = $this->db->get("search_results");
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			$row['s_rows'] = read_file('./cache/search_result_'. $row['id']);
			return $row;
		}
		else
		{
			return false;
		}
	}

	function save_result($rows)
	{
		//cleaning
		
		$query = $this->db->get_where('search_results', array('s_date <=' => (mktime() - 900)));
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				@unlink('./cache/search_result_' . $row['id']);
			}
		}
		$this->db->where('s_date <=', (mktime() - 900));
		$this->db->delete('search_results');
		
		$serialized = serialize($rows);
		$this->db->set('s_date', mktime());
		$this->db->set('s_tosearch', $this->input->post('tosearch'));
		$this->db->insert('search_results');
		$id = $this->db->insert_id();
		write_file('./cache/search_result_' . $id, $serialized);
		return $id;
	}
}