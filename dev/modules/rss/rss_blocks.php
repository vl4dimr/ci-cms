<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/***
* This block should be called like this
* $rss = $this->block->get('rss_block', array('url' => 'http:---', 'show_title' => true, 'show_description' => true, 'item_numbers' => 5));
*/
$this->set('rss_block', 'rss_block');

function rss_block($params = array())
{
	$default_params = array
	(
		'url' => 'http://ci-cms.blogspot.com/feeds/posts/default/-/news',
		'limit' => 5
	);
	
	foreach ($default_params as $key => $value)
	{
		$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
	}
	$obj =& get_instance();
	$obj->load->library('simplepie');
	$obj->simplepie->set_feed_url($params['url']);
	$obj->simplepie->set_cache_location('./cache');
	$obj->simplepie->init();
	$obj->simplepie->handle_content_type();
	return $obj->simplepie->get_items(0, $params['limit']);

}