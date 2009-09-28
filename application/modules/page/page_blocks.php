<?php

$this->set('page_latest_pages', 'page_latest_pages');
		
function page_latest_pages($limit = 5)
{
	$obj =& get_instance();
	
	$obj->load->model('page_model');
	$data['pages'] = $obj->page_model->new_pages($limit);
	return $obj->load->view('blocks/newpages', $data, true);
}

$this->set('page_subpages', 'page_subpages');
		
function page_subpages($where, $limit = 5)
{

	$obj =& get_instance();
	
	$obj->load->model('page_model');
	if (is_array($where))
	{
		$page = $obj->page_model->get_page($where);
		return  $obj->page_model->get_subpages($page['id']);
	}
	
	return  $obj->page_model->get_subpages($where);
}

$this->set('page_item', 'page_item');

function page_item($where = array())
{
	$obj =& get_instance();
	
	$obj->load->model('page_model');
	return  $obj->page_model->get_page($where);
}

function page_images($page_id)
{
	$obj =& get_instance();
	$obj->load->model('page_model');
	return $obj->page_model->get_images($page_id);
}
?>