<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Controller {

	function Admin()
	{
		parent::Controller();
	
		$this->load->library('administration');

		$this->template['module']	= 'downloads';
		$this->template['admin']		= true;
		$this->load->model('download_model', 'downloads');
	}
	
	function index($cat = 0, $start = null)
	{
		//rehefa tsisy nin nin dia lisitry ny cateogory
		$per_page = 20;
		
		
		$this->user->check_level($this->template['module'], LEVEL_VIEW);
		
		
		$this->template['rows'] = $this->downloads->get_catlist_by_pid($cat);
		
		
		$this->load->library('pagination');
		/*
		
		$config['uri_segment'] = 5;
		$config['first_link'] = __('First');
		$config['last_link'] = __('Last');
		$config['base_url'] = base_url() . 'admin/downloads/index/' . $cat;
		$config['total_rows'] = $this->downloads->get_totalcat($cat);
		$config['per_page'] = $per_page; 

		$this->pagination->initialize($config); 
		$this->template['pager'] = $this->pagination->create_links();
		
		*/
		
		$this->template['files'] = $this->downloads->get_docs($cat, $start, $per_page);
		
		if ($cat == 0)
		{
			$this->template['cat'] = array('pid' => 0, 'id' => 0, 'title' => __("Root", 'downloads'));
		}
		else
		{
			$this->template['cat'] = $this->downloads->get_cat($cat);
		}
		
		
		$config['uri_segment'] = 5;
		$config['first_link'] = __('First');
		$config['last_link'] = __('Last');
		$config['base_url'] = base_url() . 'admin/downloads/index/' . $cat;
		$config['total_rows'] = $this->downloads->get_totalfiles($cat);
		$config['per_page'] = $per_page; 

		$this->pagination->initialize($config); 

		$this->template['pager'] = $this->pagination->create_links();		
		
		
		$this->layout->load($this->template, 'admin/admin');
	
	}
	
	function settings()
	{
		if ($post = $this->input->post('submit') )
		{
			
			foreach ($this->downloads->default_settings as $key => $val)
			{
			
				if ( $this->input->post($key) !== false)
				{
					$this->downloads->set($key, $this->input->post($key));
					
				}
				else
				{
					$this->downloads->set($key, $val);
				}
			}
			$this->session->set_flashdata('notification', __("Settings updated", $this->template['module']));	
			redirect('admin/downloads/settings');
		}
		else
		{
		
			$this->layout->load($this->template, 'admin/settings');
		
		}
		
	}
}	
	
