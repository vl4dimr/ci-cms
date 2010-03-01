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
	
	$hash = md5(serialize($where));
	$obj->cache->remove_group('page_list');
	if(!$result = $obj->cache->get('page_item_' . $hash, 'page_list'))
	{
		$obj->load->model('page_model');
		$result = $obj->page_model->get_page($where);
		$obj->cache->save('page_item_' . $hash, $result, 'page_list', 0);
	}
	return $result; 
}

function page_images($page_id)
{
	$obj =& get_instance();
	$obj->load->model('page_model');
	return $obj->page_model->get_images(array('where' => array('src_id' => $page_id)));
}

$this->set('page_list', 'page_list');

function page_list($params = array())
{
	$obj =& get_instance();
	$obj->load->model('page_model');
	return $obj->page_model->get_page_list($params);
}
?>