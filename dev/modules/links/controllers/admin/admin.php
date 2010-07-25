<?php
/*
 * $Id: admin.php 1083 2008-11-23 09:31:10Z hery $
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Controller {

	function Admin()
	{
		parent::Controller();
	
		$this->load->library('administration');

		$this->template['module']	= 'links';
		$this->template['admin']		= true;
		$this->load->model('links_model', 'links');
	}
	
	function index($cat = 0, $start = null)
	{
		//rehefa tsisy nin nin dia lisitry ny cateogory
		$per_page = 20;
		
		
		$this->user->check_level($this->template['module'], LEVEL_VIEW);
		
		
		$this->template['rows'] = $this->links->get_catlist_by_pid($cat);
		
		
		$this->load->library('pagination');
		/*
		
		$config['uri_segment'] = 5;
		$config['first_link'] = __('First');
		$config['last_link'] = __('Last');
		$config['base_url'] = base_url() . 'admin/links/index/' . $cat;
		$config['total_rows'] = $this->links->get_totalcat($cat);
		$config['per_page'] = $per_page; 

		$this->pagination->initialize($config); 
		$this->template['pager'] = $this->pagination->create_links();
		
		*/
		
		$this->template['links'] = $this->links->get_links(array('cat' => $cat, 'lang' => $this->user->lang), $start, $per_page);
		
		if ($cat == 0)
		{
			$this->template['cat'] = array('pid' => 0, 'id' => 0, 'title' => __("Root", 'links'));
		}
		else
		{
			$this->template['cat'] = $this->links->get_cat($cat);
		}
		
		
		$config['uri_segment'] = 5;
		$config['first_link'] = __('First');
		$config['last_link'] = __('Last');
		$config['base_url'] = base_url() . 'admin/links/index/' . $cat;
		$config['total_rows'] = $this->links->get_totallinks($cat);
		$config['per_page'] = $per_page; 

		$this->pagination->initialize($config); 

		$this->template['pager'] = $this->pagination->create_links();		
		
		
		$this->layout->load($this->template, 'admin/admin');
	
	}
	
}	
	
