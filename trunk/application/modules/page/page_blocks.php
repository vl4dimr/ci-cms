<?php

$this->set('page_latest_pages', 'page_latest_pages');
		
function page_latest_pages($limit = 5)
{
	$this->obj =& get_instance();
	
	$this->obj->load->model('page_model');
	$data['pages'] = $this->obj->page_model->new_pages($limit);
	return $this->obj->load->view('blocks/newpages', $data, true);
}

?>