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
	
	function index($start = null)
	{
		//rehefa tsisy nin nin dia lisitry ny cateogory
		$per_page = 20;
		
		
		$this->user->check_level($this->template['module'], LEVEL_VIEW);
		
		
		$this->template['rows'] = $this->downloads->get_catlist($start, $per_page);
		
		
		$this->load->library('pagination');
		
		$config['uri_segment'] = 4;
		$config['first_link'] = __('First');
		$config['last_link'] = __('Last');
		$config['base_url'] = base_url() . 'admin/downloads/index';
		$config['total_rows'] = $this->downloads->get_totalcat();
		$config['per_page'] = $per_page; 

		$this->pagination->initialize($config); 

		$this->template['pager'] = $this->pagination->create_links();
		$this->layout->load($this->template, 'admin/admin');
	
	}
	
}	
	
