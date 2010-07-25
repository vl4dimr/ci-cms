<?php
/*
 * $Id: category.php 1071 2008-11-19 05:54:11Z hery $
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category extends Controller {

	function Category()
	{
		parent::Controller();
	
		$this->load->library('administration');

		$this->template['module']	= 'links';
		$this->template['admin']		= true;
		$this->load->model('links_model', 'links');

	}


	function index($parent = 0, $start = null)
	{
		//rehefa tsisy nin nin dia lisitry ny files ao @ root
		redirect('admin/links/index/' . $parent);
	
	}

	function create($cat = 0, $id = null)
	{

		if ($cat == 0)
		{
			$this->template['cat'] = array('pid' => 0, 'id' => 0, 'title' => __("Root", 'links'));
		}
		else
		{
			$this->template['cat'] = $this->links->get_cat($cat);
		}
	
		$this->template['parents'] = $this->links->get_catlist();
	
		if ($row = $this->links->get_cat($id))
		{
			$this->template['row'] = $row;
		}
		else
		{
			$cat_fields = array() ;
			foreach ($this->links->tables['links_cat']['fields'] as $key => $val) :
				$cat_fields[$key] = isset($val['default']) ? $val['default'] : '';
			endforeach;
			$this->template['row'] = $cat_fields;
		}
		
		$this->layout->load($this->template, 'admin/category_create');
	
	}
	
	function save()
	{
		$id = $this->input->post('id');
		$this->links->save_cat($id);
		
		$this->session->set_flashdata('notification', __("Category saved", 'links'));
		redirect('admin/links/index/'. $this->input->post('pid'));
		
	}
	
	function move($direction, $id)
	{
		$this->user->check_level($this->template['module'], LEVEL_EDIT);

		if (!isset($direction) || !isset($id))
		{
			redirect('admin/links');
		}
		
		$this->links->move_cat($direction, $id);
		
		redirect('admin/links');					
		
				
	}
	
	function delete($pid = 0, $id, $js = 0)
	{
		$this->user->check_level($this->template['module'], LEVEL_DEL);
		//cannot delete if contains files or categories
		
		if ($this->links->get_cat(array('pid'=> $id)))
		{
			$this->session->set_flashdata('notification', __('The category contains other categories. It cannot be removed.'));

			redirect('admin/links/index/' . $pid);
		}

		if ($this->links->get_link(array('cat' => $id)))
		{
			$this->session->set_flashdata('notification', __('The category contains links. It cannot be removed.'));

			redirect('admin/links/index/' . $pid);
		}
		
		if ( $js > 0 )
		{
			$this->links->delete_cat($id);
			
			$this->session->set_flashdata('notification', __('The category  has been deleted.'));

			redirect('admin/links/index/' . $pid);
		}
		else
		{
			$this->template['id'] = $id;
			$this->template['cat'] = $pid;
			
			$this->layout->load($this->template, 'admin/category_delete');
		}
	}
}	
