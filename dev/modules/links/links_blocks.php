<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/***
* This block should be called like this
* $cat = $this->block->get('links_catlist', $params = array());
*/
$this->set('links_catlist', 'links_catlist');

function links_catlist($params = array())
{
	$default_params = array
	(
		'pid' => '0',
		'limit' => 5
	);
	
	foreach ($default_params as $key => $value)
	{
		$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
	}
	$obj =& get_instance();
	$obj->load->model('links_model', 'links');
	$cat = $obj->links->get_catlist_by_pid($params['pid'] , 0, $params['limit']);
	return $cat;

}

$this->set('links_latest', 'links_latest');

function links_latest($params = array())
{
	$default_params = array
	(
		'limit' => 5
	);
	
	foreach ($default_params as $key => $value)
	{
		$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
	}
	$obj =& get_instance();
	$obj->load->model('links_model', 'links');
	$rows = $obj->links->get_links(array('lang' => $obj->user->lang) , array('order_by' => 'id DESC', 'limit' => 5));
	return $rows;

}