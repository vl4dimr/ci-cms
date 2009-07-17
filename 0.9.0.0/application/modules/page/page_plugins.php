<?php

$this->add_filter('search_result', 'page_search_result');
	
function page_search_result($rows)
{

	$obj =& get_instance();

	if ($tosearch = $obj->input->post('tosearch'))
	{
		$where[] = " (body LIKE '%". ereg_replace(" ", "%' AND body LIKE '%", $obj->db->escape_str($tosearch)). "%') ";
		$where[] = " (title LIKE '%". ereg_replace(" ", "%' AND title LIKE '%", $obj->db->escape_str($tosearch)). "%') ";
		

		$sql = "SELECT weight result_order, " . $obj->db->escape( __("Page", "page") ) . " AS result_type, title AS result_title, CONCAT('" . site_url() . "', uri) AS result_link, CONCAT('... ', SUBSTRING(body, LOCATE('" . $obj->db->escape_str($tosearch) . "', body) , 200), '... ') AS result_text FROM " . $obj->db->dbprefix('pages') . "  " .
		(count($where) > 0 ? " WHERE " . join(" OR ", $where) : "") .
		" ORDER BY result_order";
		
		
		

		$query = $obj->db->query($sql);
		
		if ($query->num_rows() > 0 )
		{
			$list = $query->result_array();
			$rows = array_merge_recursive($rows, $list);
			return $rows;
		}
		else
		{
			return $rows;
		}
		
	}
}

?>