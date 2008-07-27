<?php

$this->set('page_latest_pages', 'page_latest_pages');
		
function page_latest_pages($limit = 5)
{
	$obj =& get_instance();
	
	$obj->load->model('page_model');
	$data['pages'] = $obj->page_model->new_pages($limit);
	return $obj->load->view('blocks/newpages', $data, true);
}

?>