<?php
/*
 * $Id: link.php 1070 2008-11-18 06:26:42Z hery $
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Link extends Controller {

	function Link()
	{
		parent::Controller();
	
		$this->load->library('administration');

		$this->template['module']	= 'links';
		$this->template['admin']		= true;
		$this->load->model('links_model', 'links');

	}


	function index($id)
	{
		//rehefa tsisy nin nin dia lisitry ny cateogory
		redirect('links/link/show/' . $id);
	
	}

	function create($cat = 0, $id = null)
	{
				
		$this->template['parents'] = $this->links->get_catlist();

		$this->template['cat'] = $cat;
		
		
		if ($row = $this->links->get_link($id))
		{
			$this->template['row'] = $row;
		}
		else
		{
			$link_fields = array() ;
			foreach ($this->links->tables['links_links']['fields'] as $key => $val) :
				$cat_fields[$key] = isset($val['default']) ? $val['default'] : '';
			endforeach;
			$this->template['row'] = $cat_fields;
		}
		
		$this->layout->load($this->template, 'admin/link_create');
	
	}
	
	function save()
	{
		$error = 0;
		if (!$this->input->post('url'))
		{
			$this->session->set_flashdata('notification', __("You didn't specify a link", 'links'));
			$error = 1;
		}
		if (!$this->input->post('title'))
		{
			$this->session->set_flashdata('notification', __("You didn't specify the title", 'links'));
			$error = 1;
		}
		if (substr($this->input->post('url'), 0,7) != 'http://' && 
		substr($this->input->post('url'), 0,8) != 'https://' &&
		substr($this->input->post('url'), 0,6) != 'ftp://' )
		{
			$this->session->set_flashdata('notification', __("The format of the url is not valid. It should start with http://", 'links'));
			$error = 1;
		}
		
		if ($error == 1)
		{
		
			$this->template['row'] = $_POST;
			$this->template['parents'] = $this->links->get_catlist();

			$this->template['cat'] = $this->input->post('cat');

			$this->layout->load($this->template, 'admin/link_create');
			return;
		}
		
		$id = $this->input->post('id');
		$this->links->save_link($id);
		
		$this->session->set_flashdata('notification', __("link saved", 'links'));
		redirect('admin/links/index/' . $this->input->post('cat'));
		
	}
	
	function delete($cat, $id, $js = 0)
	{
		$this->user->check_level($this->template['module'], LEVEL_DEL);
		//cannot delete if contains files or categories
		
		if ( $js > 0 )
		{
			$this->links->delete_link($id);
			
			$this->session->set_flashdata('notification', __('The link  has been deleted.'));

			redirect('admin/links/index/' . $cat);
		}
		else
		{
			$this->template['id'] = $id;
			$this->template['cat'] = $cat;
			
			$this->layout->load($this->template, 'admin/link_delete');
		}
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
}	
