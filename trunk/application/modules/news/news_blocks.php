<?php

$this->set('latest_news', 'news_latest_news');
$this->set('get_news_bycat', 'news_get_news');
$this->set('get_news_cat', 'news_get_cat');

function news_get_cat($pid = 0)
{
	$return = array();
	$obj =& get_instance();
	$obj->load->model('news_model');
	return $obj->news_model->get_catlist_by_pid($pid);
	
}

function news_get_news($cat = 0)
{
	$return = array();
	$obj =& get_instance();
	$obj->load->model('news_model');
	$cat = $obj->news_model->get_cat($cat);
	$return['title'] = $cat['title'];
	$return['cat'] = $cat['id'];
	$return['news'] = $obj->news_model->get_news_list(array('cat' => $cat));
	
	return $return;
	
}
		
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