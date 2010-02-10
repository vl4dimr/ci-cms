<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category extends Controller {

	function Category()
	{
		parent::Controller();
	
		$this->load->library('administration');

		$this->template['module']	= 'downloads';
		$this->template['admin']		= true;
		$this->load->model('download_model', 'downloads');

	}


	function index($parent = 0, $start = null)
	{
		//rehefa tsisy nin nin dia lisitry ny files ao @ root
		redirect('admin/downloads/index/' . $parent);
	
	}

	function create($cat = 0, $id = null)
	{

		if ($cat == 0)
		{
			$this->template['cat'] = array('pid' => 0, 'id' => 0, 'title' => __("Root", 'downloads'));
		}
		else
		{
			$this->template['cat'] = $this->downloads->get_cat($cat);
		}
	
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
		
		$this->session->set_flashdata('notification', __("Category saved", 'downloads'));
		redirect('admin/downloads/index/'. $this->input->post('pid'));
		
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
	
	function delete($pid = 0, $id, $js = 0)
	{
		$this->user->check_level($this->template['module'], LEVEL_DEL);
		//cannot delete if contains files or categories
		
		if ($this->downloads->get_cat(array('pid'=> $id)))
		{
			$this->session->set_flashdata('notification', __('The category contains other categories. It cannot be removed.'));

			redirect('admin/downloads/index/' . $pid);
		}

		if ($this->downloads->get_doc(array('cat' => $id)))
		{
			$this->session->set_flashdata('notification', __('The category contains files. It cannot be removed.'));

			redirect('admin/downloads/index/' . $pid);
		}
		
		if ( $js > 0 )
		{
			$this->downloads->delete_cat($id);
			
			$this->session->set_flashdata('notification', __('The category  has been deleted.'));

			redirect('admin/downloads/index/' . $pid);
		}
		else
		{
			$this->template['id'] = $id;
			$this->template['cat'] = $pid;
			
			$this->layout->load($this->template, 'admin/category_delete');
		}
	}
}	
