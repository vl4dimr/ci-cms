<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Classes extends Controller {

	function Classes()
	{
		parent::Controller();
	
		$this->load->library('administration');

		$this->template['module']	= 'institute';
		$this->template['admin']		= true;
		$this->load->model('institute_model', 'institute');

	}


	function index($start = 0)
	{
		$per_page = 20;
		
		
		$this->user->check_level($this->template['module'], LEVEL_VIEW);
		
		
		$this->template['rows'] = $this->institute->get_class_list(intval($start), $per_page);
		
		
		$this->load->library('pagination');
		
		
		$config['uri_segment'] = 5;
		$config['first_link'] = __('First', 'institute');
		$config['last_link'] = __('Last', 'institute');
		$config['base_url'] = base_url() . 'admin/institute/class/index/';
		$config['total_rows'] = $this->institute->get_total_classes();
		$config['per_page'] = $per_page; 

		$this->pagination->initialize($config); 

		$this->template['pager'] = $this->pagination->create_links();		
		
		
		$this->layout->load($this->template, 'admin/classes/list');
	
	}

	function create($id = null)
	{

	
		$this->template['parents'] = $this->institute->get_class_list();
	
		if ($row = $this->institute->get_cat($id))
		{
			$this->template['row'] = $row;
		}
		else
		{
			$this->template['row'] = $this->institute->fields['institute_classes'];
		}
		
		$this->layout->load($this->template, 'admin/classes/create');
	
	}
	
	function save()
	{
		$id = $this->input->post('id');
		$this->institute->save_cat($id);
		
		$this->session->set_flashdata('notification', __("Category saved", 'institute'));
		redirect('admin/institute/index/'. $this->input->post('pid'));
		
	}
	
	function move($direction, $id)
	{
		$this->user->check_level($this->template['module'], LEVEL_EDIT);

		if (!isset($direction) || !isset($id))
		{
			redirect('admin/institute');
		}
		
		$this->institute->move_cat($direction, $id);
		
		redirect('admin/institute');					
		
				
	}
	
	function delete($pid = 0, $id, $js = 0)
	{
		$this->user->check_level($this->template['module'], LEVEL_DEL);
		//cannot delete if contains files or categories
		
		if ($this->institute->get_cat(array('pid'=> $id)))
		{
			$this->session->set_flashdata('notification', __('The category contains other categories. It cannot be removed.'));

			redirect('admin/institute/index/' . $pid);
		}

		if ($this->institute->get_doc(array('cat' => $id)))
		{
			$this->session->set_flashdata('notification', __('The category contains files. It cannot be removed.'));

			redirect('admin/institute/index/' . $pid);
		}
		
		if ( $js > 0 )
		{
			$this->institute->delete_cat($id);
			
			$this->session->set_flashdata('notification', __('The category  has been deleted.'));

			redirect('admin/institute/index/' . $pid);
		}
		else
		{
			$this->template['id'] = $id;
			$this->template['cat'] = $pid;
			
			$this->layout->load($this->template, 'admin/category_delete');
		}
	}
}	
