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
		$rows = array();
		if (!is_null($search_id))
		{
			$result = $this->search->get_result($search_id);
			$rows = unserialize($result['s_rows']);
			$tosearch = $result['s_tosearch'];
		}
		else
		{	
			//refreshing first page or getting back to first page without searchid
			
			$rows = $this->plugin->apply_filters('search_result', $rows);
			$search_id = $this->search->save_result($rows);
			$tosearch = $this->input->post('tosearch');
			$this->session->set_flashdata('search_id', $search_id);	
		}
		
		if (count($rows) == 0)
		{
			
			$this->layout->load($this->template, '404');
		}
		else
		{
			$debut = intval($this->uri->segment(4));
			$total = count($rows);
			$z		= $debut + 1;
			$end 	= 20 + $z;
			if ( $end > $total ) {
				$end = $total + 1;
			}
			for( $i=$z; $i < $end; $i++ ) {
				$limitedrows[] = $rows[$i-1];
			}
		
			$this->template['rows'] = $limitedrows;
			
			$this->load->library('pagination');
			$config['base_url'] = site_url('search/result/' . $search_id . '/');
			$config['total_rows'] = count($rows);
			$config['per_page'] = 20;
			$config['uri_segment'] = 4;
			$config['num_links'] = 3;

			$this->template['tosearch'] = $tosearch;
			
			$this->pagination->initialize($config);
			$this->template['pager'] = $this->pagination->create_links();
			$this->layout->load($this->template, 'result');
		}
	}
}	
