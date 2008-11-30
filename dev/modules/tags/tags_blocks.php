<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


$this->set('tag_cloud', 'tag_cloud');

function tag_cloud()
{
	$return = array();
	$obj =& get_instance();
	$obj->load->model('tags_model', 'tags');
	
	$tags = $obj->tags->get_cloud();
	return $tags;

}