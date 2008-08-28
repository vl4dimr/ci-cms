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

		$this->template['module']	= 'news';
		$this->template['admin']		= true;
		$this->load->model('news_model', 'news');

	}


	function index($start = null)
	{
		//rehefa tsisy nin nin dia lisitry ny cateogory
		$per_page = 20;
		
		
		$this->user->check_level($this->template['module'], LEVEL_VIEW);
		
		
		$this->template['rows'] = $this->news->get_catlist($start, $per_page);
		
		
		$this->load->library('pagination');
		
		$config['uri_segment'] = 5;
		$config['first_link'] = __('First');
		$config['last_link'] = __('Last');
		$config['base_url'] = base_url() . 'admin/news/category/index';
		$config['total_rows'] = $this->news->get_totalcat();
		$config['per_page'] = $per_page; 

		$this->pagination->initialize($config); 

		$this->template['pager'] = $this->pagination->create_links();
		$this->layout->load($this->template, 'admin/category');
	
	}

	function create($id = null)
	{

		$this->template['parents'] = $this->news->get_catlist();
	
		if ($row = $this->news->get_cat($id))
		{
			$this->template['row'] = $row;
		}
		else
		{
			$this->template['row'] = $this->news->cat_fields;
		}
		
		$this->layout->load($this->template, 'admin/category_create');
	
	}
	
	function save()
	{
		$id = $this->input->post('id');
		$this->news->save_cat($id);
		
		$this->session->set_flashdata('notification', __("Category saved", $this->template['module']));
		redirect('admin/news/category');
		
	}
	
	function move($direction, $id)
	{
		$this->user->check_level($this->template['module'], LEVEL_EDIT);

		if (!isset($direction) || !isset($id))
		{
			redirect('admin/news/category');
		}
		
		$this->news->move_cat($direction, $id);
		
		redirect('admin/news/category');					
		
				
	}
	
	function delete($id, $js = 0)
	{
		$this->user->check_level($this->template['module'], LEVEL_DEL);
		//cannot delete if contains files or categories
		
		if ($this->news->get_cat(array('pid'=> $id)))
		{
			$this->session->set_flashdata('notification', __('The category contains other categories. It cannot be removed.'));

			redirect('admin/news/category');
		}

		if ($this->news->get_news(array('cat' => $id)))
		{
			$this->session->set_flashdata('notification', __('The category contains news. It cannot be removed.'));

			redirect('admin/news/category');
		}
		
		if ( $js > 0 )
		{
			$this->news->delete_cat($id);
			
			$this->session->set_flashdata('notification', __('The category  has been deleted.'));

			redirect('admin/news/category');
		}
		else
		{
			$this->template['id'] = $id;
			
			$this->layout->load($this->template, 'admin/category_delete');
		}
	}
}	
