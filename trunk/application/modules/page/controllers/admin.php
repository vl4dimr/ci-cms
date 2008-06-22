<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Admin extends Controller {
		
		function Admin()
		{
			parent::Controller();
			
			$this->load->library('administration');
			
			$this->template['module']	= 'page';
			$this->template['admin']		= true;
			
			$this->load->model('page_model', 'pages');
			
			$this->page_id = ( $this->uri->segment(4) ) ? $this->uri->segment(4) : NULL;
		}
		
		function index()
		{
			
			$this->template['pages'] = $this->pages->list_pages();
			
			$this->layout->load($this->template, 'admin');
		}
		/**
		 * Dealing with page module settings
		 **/
		function settings()
		{
			
			if ($post = $this->input->post('submit') )
			{
				$fields = array('page_home');
				
				foreach ($fields as $field)
				{
					if ( $this->input->post($field) !== false)
					{
						$this->system->set($field, $this->input->post($field));
					}
				}
				$this->session->set_flashdata('notification', __("Settings updated"));	
				redirect('admin/page/settings');
			}
			else
			{
				$this->layout->load($this->template, 'settings');
			}
		}
		function create()
		{
			if ( $post = $this->input->post('submit') )
			{
				$data = array(
							'title'				=> $this->input->post('title'),
							'parent_id'		=> $this->input->post('parent_id'),
							'uri'				=> $this->input->post('uri').$this->input->post('uri'),
							'meta_keywords'		=> $this->input->post('meta_keywords'),
							'meta_description'	=> $this->input->post('meta_description'),
							'body'				=> $this->input->post('body'),
							'active'			=> $this->input->post('status')
						);
						
				$this->db->insert('pages', $data);
				$id = $this->db->insert_id();	
					
				$this->session->set_flashdata('notification', 'Page "'.$this->input->post("title").'" has been created, continue editing here');	
				
				redirect('admin/page/edit/'.$id);
			}
			else
			{
				
				$this->layout->load($this->template, 'create');
			}
		}
		
		function edit()
		{
			if ( $post = $this->input->post('submit') )
			{
				$data = array(
							'title'				=> $this->input->post('title'),
							'parent_id'		=> $this->input->post('parent_id'),
							'uri'				=> $this->input->post('uri'),
							'meta_keywords'		=> $this->input->post('meta_keywords'),
							'meta_description'	=> $this->input->post('meta_description'),
							'body'				=> $this->input->post('body'),
							'active'			=> $this->input->post('status')
						);
					
				$this->db->where('id', $this->input->post('id'));
				$this->db->update('pages', $data);
				
				$this->session->set_flashdata('notification', 'Page "'.$this->input->post("title").'" has been saved ...');
				
				redirect('admin/page/edit/'.$this->input->post('id'));
			}
			
			$this->template['pages'] = $this->pages->list_pages();
			
			$this->template['page'] = $this->pages->get_page( array('id' => $this->page_id) );
			$this->layout->load($this->template, 'edit');
		}
		
		function delete()
		{
			if ( $post = $this->input->post('submit') )
			{
				$this->db->where('id', $this->input->post('id'));
				$query = $this->db->delete('pages');
				
				$this->session->set_flashdata('notification', 'Page has been deleted.');
				
				redirect('admin/page');
			}
			else
			{
				$this->template['page'] = $this->pages->get_page( array('id' => $this->page_id) );
				
				$this->layout->load($this->template, 'delete');
			}
		}
		
	}

?>