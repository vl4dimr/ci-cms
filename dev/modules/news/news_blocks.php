<?php

$this->set('latest_news', 'news_latest_news');
		
function news_latest_news($limit = 5)
{
	$this->obj =& get_instance();
	
	$this->obj->load->model('news_model');
	$data['rows'] = $this->obj->news_model->latest_news($limit);
	return $this->obj->load->view('blocks/latest', $data, true);
}

?>