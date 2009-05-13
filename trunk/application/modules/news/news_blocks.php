<?php

$this->set('latest_news', 'news_latest_news');
$this->set('get_news_bycat', 'news_get_news_by_cat');
$this->set('get_news_cat', 'news_get_cat');
$this->set('get_news_list', 'news_get_news');

function news_get_cat($pid = 0)
{
	$obj =& get_instance();
	$obj->load->model('news_model');
	return $obj->news_model->get_catlist_by_pid($pid);
	
}

function news_get_news_by_cat($cat = 0)
{
	$params = array();
	$obj =& get_instance();
	$obj->load->model('news_model');
	$params['where'] = array('cat' => $cat);
	return $obj->news_model->get_list($params);
	
}


function news_get_news($params)
{
	$rows = array();
	$obj =& get_instance();
	$obj->load->model('news_model');

	$rows = $obj->news_model->get_list($params);
	
	return $rows;
	
}
		
function news_latest_news($limit = 5)
{

	$obj =& get_instance();

	$rows = array();
	$params = array(
	'limit' => $limit,
	'order_by' => 'news.id DESC',
	'where' => array('news.lang' => $obj->user->lang)
	);
	$obj->load->model('news_model');

	$rows = $obj->news_model->get_list($params);
	
	return $rows;

}


?>