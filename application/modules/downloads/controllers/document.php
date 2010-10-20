<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Document extends Controller {

	function Document()
	{
		parent::Controller();
	

		$this->template['module']	= 'downloads';
		$this->load->model('download_model', 'downloads');

	}


	function index($id)
	{
		//rehefa tsisy nin nin dia lisitry ny cateogory
		redirect('downloads/document/show/' . $id);
	
	}
	
	function get($file)
	{
		if ($row = $this->downloads->get_doc(array('download_files.file' => $file)))
		{

			$fn = $this->downloads->settings['upload_path'] . $file;
			if(file_exists($fn))
			{
			
				//counter hit
				if ($this->session->userdata('download_file'.$row['id']) != $row['id'])
				{
					$this->session->set_userdata('download_file'.$row['id'], $row['id']);
					$this->downloads->update_doc(array('hit' => ($row['hit'] + 1)), $row['id']);
				}
			
				if (function_exists('apache_request_headers'))
				{
			    $headers = apache_request_headers();
				}

			    // Checking if the client is validating his cache and if it is current.
			    if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == filemtime($fn))) 
				{
			        // Client's cache IS current, so we just respond '304 Not Modified'.
			        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 304);
			    } 
				else 
				{
					$this->load->helper('file');
			        // Image not cached or cache outdated, we respond '200 OK' and output the image.
			        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 200);
			        header('Content-Length: '.filesize($fn));
					header('Content-Type: ' . get_mime_by_extension($file));
					header("Content-Disposition: attachment; filename=\"$file\""); 
					
			        print file_get_contents($fn);
			    }
				
			}
			else
			{
				$this->output->set_header("HTTP/1.0 404 Not Found");
				$this->layout->load($this->template, '404');
			}		
		}
		else
		{
			$this->output->set_header("HTTP/1.0 404 Not Found");
			$this->layout->load($this->template, '404');
		}
	}
	
	function show($id)
	{
		if ($row = $this->downloads->get_doc($id))
		{
			$this->template['row'] = $row;
			$this->template['cat'] = $this->downloads->get_cat($row['cat']);
			$this->layout->load($this->template, 'show');
		}
	}
}	
