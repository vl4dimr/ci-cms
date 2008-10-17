<?php

$this->add_filter('search_result', 'news_search_result');
	
function news_search_result($rows)
{

	$obj =& get_instance();

	if ($tosearch = $obj->input->post('tosearch'))
	{
		$where[] = " (body LIKE '%". ereg_replace(" ", "%' AND body LIKE '%", $obj->db->escape_str($tosearch)). "%') ";
		$where[] = " (n.title LIKE '%". ereg_replace(" ", "%' AND n.title LIKE '%", $obj->db->escape_str($tosearch)). "%') ";
		

		$sql = "SELECT n.id result_order, c.title AS result_type, n.title AS result_title, CONCAT('" . site_url('news') . "/', uri) AS result_link, CONCAT('... ', SUBSTRING(body, LOCATE('" . $obj->db->escape_str($tosearch) . "', body) - 50 , 200), '... ') AS result_text FROM " . $obj->db->dbprefix('news') . " n " .
		" LEFT JOIN " . $obj->db->dbprefix('news_cat') . " c ON n.cat=c.id " .
		(count($where) > 0 ? " WHERE " . join(" AND ", $where) : "") .
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