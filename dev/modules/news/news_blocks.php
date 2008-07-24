<?php

$this->set('latest_news', 'news_latest_news');
		
function news_latest_news($limit = 5)
{
	$return = array();
	
	$this->obj =& get_instance();
	
	$this->obj->load->helper('typography');
	$this->obj->load->helper('text');	
	
	$this->obj->load->model('news_model');
	if($rows = $this->obj->news_model->latest_news($limit))
	{
		foreach ($rows as $row)
		{
			$this->obj->db->order_by('id DESC');
			$this->obj->db->where(array('src_id' => $row['id'], 'module' => 'news'));
			$query = $this->obj->db->get('images');
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