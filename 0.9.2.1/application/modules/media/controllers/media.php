<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Media extends Controller {

	function Media()
	{
		parent::Controller();
	

	}
	
	
	function index()
	{
		
		$fn = substr($this->uri->uri_string(), 1);
		if(file_exists($fn))
		{
		
		    if (function_exists('apache_request_headers'))
			{
				$headers = apache_request_headers();
			}

		    // Checking if the client is validating his cache and if it is current.
		    if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == filemtime($fn))) {
		        // Client's cache IS current, so we just respond '304 Not Modified'.
		        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 304);
		    } else {
		        // Image not cached or cache outdated, we respond '200 OK' and output the image.
		        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 200);
		        header('Content-Length: '.filesize($fn));
				$this->load->helper('file');
				$mime = get_mime_by_extension($fn);
		        header('Content-Type: $mime');
		        print file_get_contents($fn);
		    }
		
		}
		else
		{
			$this->output->set_header("HTTP/1.0 404 Not Found");
			echo "Not found";
		}
	}
}	
