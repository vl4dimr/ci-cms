<?php

$this->add_filter('search_result', 'news_search_result');
$this->add_filter('feed_content', 'news_feed_content');

function news_feed_content($data)
{
	$obj =& get_instance();
	
	$obj->load->model('news_model');
	
	$rows = $obj->news_model->get_list(array('limit' => 10, 'where' => array('news.lang <>' => '0')));
	
	$contents = array();
	foreach ($rows as $key => $row)
	{
		$contents[$key]['title'] = $row['title'];
		$contents[$key]['url'] = site_url( $row['lang'] . '/news/' . $row['uri']);
		$contents[$key]['body'] = $row['body'];
		$contents[$key]['date'] = (isset($row['date'])) ? $row['date'] : '';
	}
	return array_merge($data, $contents);
}
	
function news_search_result($rows)
{

	$obj =& get_instance();

	if ($tosearch = $obj->input->post('tosearch'))
	{
		$where[] = " (body LIKE '%". ereg_replace(" ", "%' AND body LIKE '%", $obj->db->escape_str($tosearch)). "%') ";
		$where[] = " (n.title LIKE '%". ereg_replace(" ", "%' AND n.title LIKE '%", $obj->db->escape_str($tosearch)). "%') ";
		

		$sql = "SELECT n.id result_order, c.title AS result_type, n.title AS result_title, CONCAT('" . site_url('news') . "/', uri) AS result_link, CONCAT('... ', SUBSTRING(body, LOCATE('" . $obj->db->escape_str($tosearch) . "', body) - 50 , 200), '... ') AS result_text FROM " . $obj->db->dbprefix('news') . " n " .
		" LEFT JOIN " . $obj->db->dbprefix('news_cat') . " c ON n.cat=c.id " .
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