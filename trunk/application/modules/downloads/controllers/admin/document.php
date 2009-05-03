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
	
		$this->load->library('administration');

		$this->template['module']	= 'downloads';
		$this->template['admin']		= true;
		$this->load->model('download_model', 'downloads');

	}


	function index($id)
	{
		//rehefa tsisy nin nin dia lisitry ny cateogory
		redirect('downloads/document/show/' . $id);
	
	}

	function create($cat = 0, $id = null)
	{
				
		$this->template['parents'] = $this->downloads->get_catlist();
		$this->template['files'] = $this->downloads->get_files();

		$this->template['cat'] = $cat;
		
		
		if (!is_null($id) && $row = $this->downloads->get_doc($id))
		{
			$this->template['row'] = $row;
		}
		else
		{
			$this->template['row'] = $this->downloads->doc_fields;
		}
		
		$this->layout->load($this->template, 'admin/document_create');
	
	}
	
	function save()
	{
		if (!$this->input->post('file_id') && !$this->input->post('file_link'))
		{
			$this->session->set_flashdata('notification', __("Please specify a link or select a downloaded file.", 'downloads'));
			redirect('admin/downloads/document/create/' . $this->input->post('cat') . '/' . $this->input->post('id'));
		}
		$id = $this->input->post('id');
		$this->downloads->save_doc($id);
		
		$this->session->set_flashdata('notification', __("Document saved", 'downloads'));
		redirect('admin/downloads/index/' . $this->input->post('cat'));
		
	}
	
	function move($direction, $id, $cat)
	{
		
		$this->user->check_level($this->template['module'], LEVEL_EDIT);

		if (!isset($direction) || !isset($id))
		{
			redirect('admin/downloads');
		}
		
		$this->downloads->move_doc($direction, $id, $cat);
		
		redirect('admin/downloads/index/'. $cat);					
		
				
	}
	
	function delete($cat, $id, $js = 0)
	{
		$this->user->check_level($this->template['module'], LEVEL_DEL);
		//cannot delete if contains files or categories
		
		if ( $js > 0 )
		{
			$this->downloads->delete_doc($id);
			
			$this->session->set_flashdata('notification', __('The document  has been deleted.'));

			redirect('admin/downloads/index/' . $cat);
		}
		else
		{
			$this->template['id'] = $id;
			$this->template['cat'] = $cat;
			
			$this->layout->load($this->template, 'admin/document_delete');
		}
	}
}	
