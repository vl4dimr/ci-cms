<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Controller {

	function Search()
	{
		parent::Controller();
		$this->template['module'] = 'search';
		$this->load->model('search_model', 'search');

	}
	
	
	function index()
	{
		$this->layout->load($this->template, 'search_form');
	}
	
	function result($search_id = null)
	{
		if (is_null($search_id) || $search_id == 0)
		{		
			$rows = $this->plugin->apply_filters('search_result', $rows);
		}
		else
		{
			$rows = $this->search->get_result($search_id);
		}
		
		if (count($rows) == 0)
		{
			$this->layout->load($this->template, '404');
		}
		else
		{
			$search_id = $this->search->save_result($rows);
			$this->template['rows'] = $rows;
			
			$this->load->library('pagination');
			$config['base_url'] = site_url('search/result/' . $search_id . '/';
			$config['total_rows'] = count($rows);
			$config['per_page'] = 20;
			$config['uri_segment'] = 4;
			$config['num_links'] = 3;
			
			$this->pagination->initialize($config);
			
			$this->template['pager'] = $this->pagination->creat_links();
			$this->layout->load($this->template, 'result');
		}
	}
}	
