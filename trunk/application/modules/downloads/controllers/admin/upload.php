<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends Controller {

	function Upload()
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
		
		
		$this->template['rows'] = $this->downloads->get_files($start, $per_page, "id DESC");
		
		$this->javascripts->add('ajaxfileupload.js');

		$this->load->library('pagination');
		
		$config['uri_segment'] = 5;
		$config['first_link'] = __('First');
		$config['last_link'] = __('Last');
		$config['base_url'] = base_url() . 'admin/downloads/upload/index';
		$config['total_rows'] = $this->downloads->get_totalfiles();
		$config['per_page'] = $per_page; 

		$this->pagination->initialize($config); 

		$this->template['pager'] = $this->pagination->create_links();
		$this->layout->load($this->template, 'admin/files');
	
	}
	

	function save()
	{

		$this->user->check_level($this->template['module'], LEVEL_ADD);
	
		if ($_FILES['file']['name'] != '')
		{

			$config['upload_path'] = './media/files/';
			$config['allowed_types'] = isset($this->settings->allowed_file_type) ? $this->settings->allowed_file_type : 'gif|jpg|png|bmp|doc|docx|xls|mp3|swf|exe|pdf|wav';
			$config['max_size']	= '2000';
			
			$this->load->library('upload', $config);
			
			if ( ! $this->upload->do_upload('file'))
			{
				$error = array('error' => $this->upload->display_errors());
				
			}	
			else
			{

				$file_data = $this->upload->data();
				
					
				$data = array('id' => $id, 'file' => $file_data['file_name'], 'date' => mktime(), 'size' => $file_data['file_size']);
				$this->downloads->save_file($data);
				
			}				
		}		

		$this->session->set_flashdata('notification', $error . __("File saved", 'downloads'));
		redirect('admin/downloads/upload');	
	}
	
	
	function delete($id)
	{
		$this->user->check_level($this->template['module'], LEVEL_DEL);
		//cannot delete if contains files or categories
		
		if ($row = $this->downloads->get_file($id))
		{
			
			$this->downloads->delete_file($row);
		
			echo __("The file was deleted", 'downloads');
		}
		else
		{
			echo __("File not found", 'downloads');
		}
	}
	
	function ajax_file_upload()
	{
		$file_data = array();
		$error = "";
		if(!empty($_FILES['file']['error']))
		{
			switch($_FILES['file']['error'])
			{

				case '1':
					$error = __('The uploaded file exceeds the upload_max_filesize directive in php.ini');
					break;
				case '2':
					$error = __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form');
					break;
				case '3':
					$error = __('The uploaded file was only partially uploaded');
					break;
				case '4':
					$error = __('No file was uploaded.');
					break;

				case '6':
					$error = __('Missing a temporary folder');
					break;
				case '7':
					$error = __('Failed to write file to disk');
					break;
				case '8':
					$error = __('File upload stopped by extension');
					break;
				case '999':
				default:
					$error = __('No error code avaiable');
			}
		}
		elseif(empty($_FILES['file']['tmp_name']) || $_FILES['file']['tmp_name'] == 'none')
		{
			$error = __('No file was uploaded..');
		}
		else 
		{
		
		
			$config['upload_path'] = $this->downloads->settings['upload_path'];
			$config['allowed_types'] = $this->downloads->settings['allowed_file_types'];
			$config['max_size']	= '2000';
			
			//var_dump($config['upload_path']);
			$this->load->library('upload', $config);	
			

		
			if (!$this->upload->do_upload('file'))
			{
				$error = $this->upload->display_errors();

			}	
			else
			{
				$file_data = $this->upload->data();
			

				$data = array('file' => $file_data['file_name'], 'date' => mktime(), 'name' => $file_data['orig_name'], 'size' => $file_data['file_size']);
				$id = $this->downloads->save_file($data);
			}

		}
		echo "{";
		echo "error: '" . $error . "'";
		//echo "error: 'error is " . $image_data['file_name'] . "'";
		echo ",\n filedate: '" . date('d/m/Y') . "'";
		echo ",\n file: '" . (isset($file_data['file_name']) ? $file_data['file_name'] : '') . "'";
		echo ",\n size: '" . (isset($file_data['file_size']) ? $file_data['file_size'] . ' kb' : '') . "'";
		echo ",\n id: " . (isset($id) ? $id : 0)  . "";
		echo "\n}";	
	}
	
	function ajax_file_delete()
	{
		
		
		if ($row = $this->downloads->get_file($this->input->post('id')))
		{
			
			$this->downloads->delete_file($row);
		
			echo __("The file was deleted", 'downloads');
		}
		else
		{
			echo __("File not found", 'downloads');
		}
	}
	
}	
