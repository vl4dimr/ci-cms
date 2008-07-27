<?php

$this->set('latest_news', 'news_latest_news');
		
function news_latest_news($limit = 5)
{
	$return = array();
	
	$obj =& get_instance();
	
	$obj->load->helper('typography');
	$obj->load->helper('text');	
	
	$obj->load->model('news_model');
	if($rows = $obj->news_model->latest_news($limit))
	{
		foreach ($rows as $row)
		{
			$obj->db->order_by('id DESC');
			$obj->db->where(array('src_id' => $row['id'], 'module' => 'news'));
			$query = $obj->db->get('images');
			$row['image'] = $query->row_array();
			
			if($page_break_pos = strpos($row['body'], "<!-- page break -->"))
			{
				$row['summary'] = character_limiter(strip_tags(substr($row['body'], 0, $page_break_pos), 200));
			}
			else
			{
				$row['summary'] = character_limiter(strip_tags($row['body']), 200);
			}

			$return[] = $row;
		}
	}
	
	
	return $return;

}


?>