<?php
/*
 * $Id: document.php 1070 2008-11-18 06:26:42Z hery $
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Links extends Controller {

	function Links()
	{
		parent::Controller();
	

		$this->template['module']	= 'links';
		$this->load->model('links_model', 'links');
		$this->settings = isset($this->system->links_settings) ? unserialize($this->system->links_settings) : null;

	}


	function index($cat = 0, $start = null)
	{
		//rehefa tsisy nin nin dia lisitry ny cateogory
		$per_page = 20;
		
		
		$this->template['rows'] = $this->links->get_catlist_by_pid($cat);
		
		
		$this->load->library('pagination');
		
		$this->template['links'] = $this->links->get_links($cat, $start, $per_page);
		
		if ($cat == 0)
		{
			$this->template['cat'] = array('pid' => 0, 'id' => 0, 'title' => __("Root", 'links'));
		}
		else
		{
			$this->template['cat'] = $this->links->get_cat($cat);
		}
		
		
		$config['uri_segment'] = 4;
		$config['first_link'] = __('First');
		$config['last_link'] = __('Last');
		$config['base_url'] = base_url() . 'links/index/' . $cat;
		$config['total_rows'] = $this->links->get_totallinks($cat);
		$config['per_page'] = $per_page; 

		$this->pagination->initialize($config); 

		$this->template['pager'] = $this->pagination->create_links();		
		
		
		$this->layout->load($this->template, 'index');
	
	}
	
	
	function goto($id = null)
	{
		if (is_null($id))
		{
			$this->layout->load($this->template, '404');
			return;
		}
		
		if ($row = $this->links->get_link($id))
		{
			if ($this->session->userdata('links_link_hit_'.$row['id']) != $row['id'])
			{
				$this->session->set_userdata('links_link_hit_'.$row['id'], $row['id']);
				$this->links->update_link($row['id'], array('hit' => 'hit+1'));
			}
			header("location: " . $row['url']);
		}
	}
	
	
	function thumbnail($icon = null)
	{
		if (is_null($icon))
		{
			return;
		}
		$fn = './media/thumbnail/' . $icon;
		
		if (file_exists($fn))
		{
		//if downloaded get the thumbnail
			if (function_exists('apache_request_headers'))
			{
				$headers = apache_request_headers();
			}

			// Checking if the client is validating his cache and if it is current.
			if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == filemtime($fn))) {
				// Client's cache IS current, so we just respond '304 Not Modified'.
				header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 304);
				return;
			} else {
				// Image not cached or cache outdated, we respond '200 OK' and output the image.
				header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 200);
				header('Content-Length: '.filesize($fn));
				header('Content-Type: image/jpeg');
				print file_get_contents($fn);
				return;
			}
		}
		
		//else check if its job is ready 
		
		$this->load->helper('file');

		$job = substr($icon, 0, strpos($icon, '.'));
		$this->load->library('webthumb');
		$this->webthumb->setApi($this->settings['apikey']);
		
		
		if ($icon != "empty.gif" && $this->webthumb->requestStatus($job) == "Complete")
		{
			if($thumbnail = $this->webthumb->getThumbnail( $job )) 
			{
				if(trim($thumbnail) != 'Bad JobId') 
				{
					write_file($fn, $thumbnail);
					@unlink($jf);
					header('Content-Length: '.strlen($thumbnail));
					header('Content-Type: image/jpeg');
					print $thumbnail;
					return;
				}
				else
				{
					@unlink($jf);
				}
			}
		}

			
		//else just submit one site and show no-image 
		if(	$link = $this->links->get_link(array("icon" => ""), array('order_by' => 'id DESC')))
		{
			if($job = $this->webthumb->requestThumbnail($link['url'])) {
				
				$this->links->update_link(array('id' => $link['id']), array('icon' => "'$job" . ".jpg'"));
				
			}
			else
			{
				
				//$this->links->delete_link($link['id']);
			}
		}		
		
		$empty = APPPATH . "modules/links/images/empty.gif";
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($empty)).' GMT', true, 200);
		header('Content-Length: '.filesize($empty));
		header('Content-Type: image/gif');
		print file_get_contents($empty);
		return;		
	}
	
	function test()
	{
		$this->load->library('http_request');
		$response = $this->http_request->post('webthumb.bluga.net', '/api.php', '<test></test>');
		var_dump( $response );
	}
}	
