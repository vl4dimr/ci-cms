<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Document extends Controller {

	function Document()
	{
		parent::Controller();
	
		$this->load->library('administration');

		$this->template['module']	= 'downloads';
		$this->template['admin']		= true;
		$this->load->model('download_model', 'downloads');

	}


	function index($id)
	{
		//rehefa tsisy nin nin dia lisitry ny cateogory
		redirect('downloads/document/show/' . $id);
	
	}

	function create($id = null)
	{

		$this->template['parents'] = $this->downloads->get_catlist();
	
		if ($row = $this->downloads->get_cat($id))
		{
			$this->template['row'] = $row;
		}
		else
		{
			$this->template['row'] = $this->downloads->cat_fields;
		}
		
		$this->layout->load($this->template, 'admin/category_create');
	
	}
	
	function save()
	{
		$id = $this->input->post('id');
		$this->downloads->save_cat($id);
		
		$this->session->set_flashdata('notification', __("Category saved"));
		redirect('admin/downloads');
		
	}
	
	function move($direction, $id)
	{
		$this->user->check_level($this->template['module'], LEVEL_EDIT);

		if (!isset($direction) || !isset($id))
		{
			redirect('admin/downloads');
		}
		
		$this->downloads->move_cat($direction, $id);
		
		redirect('admin/downloads');					
		
				
	}
	
	function delete($id, $js = 0)
	{
		$this->user->check_level($this->template['module'], LEVEL_DEL);
		//cannot delete if contains files or categories
		
		if ($this->downloads->get_cat(array('pid'=> $id)))
		{
			$this->session->set_flashdata('notification', __('The category contains other categories. It cannot be removed.'));

			redirect('admin/downloads');
		}

		if ($this->downloads->get_file(array('cat' => $id)))
		{
			$this->session->set_flashdata('notification', __('The category contains files. It cannot be removed.'));

			redirect('admin/downloads');
		}
		
		if ( $js > 0 )
		{
			$this->downloads->delete_cat($id);
			
			$this->session->set_flashdata('notification', __('The category  has been deleted.'));

			redirect('admin/downloads');
		}
		else
		{
			$this->template['id'] = $id;
			
			$this->layout->load($this->template, 'admin/category_delete');
		}
	}
}	
