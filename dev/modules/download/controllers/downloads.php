<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Downloads extends Controller {

	function Downloads()
	{
		parent::Controller();
	
		$this->template['module']	= 'downloads';
		$this->load->model('download_model', 'downloads');

	}
	
	function index($pid = 0, $start = null)
	{
		//rehefa tsisy nin nin dia lisitry ny cateogory
		$per_page = 20;
		
		
		$this->template['rows'] = $this->downloads->get_catlist_by_pid($pid, $start, $per_page);
		
		if ($pid != 0)
		{
			$this->template['parent'] = $this->downloads->get_cat($pid);
		}
		
		if (isset($this->template['parent']['title']))
		{
			$this->template['title'] = $this->template['parent']['title'];
			/*
			$this->template['breadcrumb'][] = array(
									'title' => ucwords($this->template['parent']['title']),
									'uri'	=> 'downloads/index/' . $pid
								);
			*/
		}
		else
		{
			$this->template['title'] = __("Downloads");
		}
		
		$this->load->library('pagination');
		
		$config['uri_segment'] = 4;
		$config['first_link'] = __('First');
		$config['last_link'] = __('Last');
		$config['base_url'] = base_url() . 'downloads/index/' . $pid;
		$config['total_rows'] = $this->downloads->get_totalcat();
		$config['per_page'] = $per_page; 

		$this->pagination->initialize($config); 

		$this->template['pager'] = $this->pagination->create_links();
		$this->layout->load($this->template, 'index');
	
	}
	
	function images($file)
	{
		if(file_exists(APPPATH . 'modules/downloads/images/' . $file))
		{
		
		$fn = APPPATH . 'modules/downloads/images/' . $file;
	    $headers = apache_request_headers();

	    // Checking if the client is validating his cache and if it is current.
	    if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == filemtime($fn))) {
	        // Client's cache IS current, so we just respond '304 Not Modified'.
	        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 304);
	    } else {
	        // Image not cached or cache outdated, we respond '200 OK' and output the image.
	        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 200);
	        header('Content-Length: '.filesize($fn));
	        header('Content-Type: image/gif');
	        print file_get_contents($fn);
	    }
		
		}
	}
}	
