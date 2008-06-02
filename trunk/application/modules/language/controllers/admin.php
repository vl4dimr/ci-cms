<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Admin extends Controller {
		
		function Admin()
		{
			parent::Controller();
			
			$this->load->library('administration');
			
			$this->template['module']	= 'language';
			$this->template['admin']		= true;
			$this->locale->load_textdomain(APPPATH . 'modules/'.$this->template['module'].'/locale/' . $this->session->userdata('lang') . '.mo' );
		}
		
		function index()
		{
			
			$this->template['langs'] = $this->locale->get_list();
			
			$this->layout->load($this->template, 'admin');
		}
		
		function active($active, $id)
		{
			
			if(isset($active) && isset($id)) {
				$data = array('active' => $active);
				$this->db->where('id', $id);
				$this->db->update('languages', $data);
				$this->session->set_flashdata('notification', __("Language updated"));
			}
			redirect('admin/language');
		}
		
		function setdefault($id) {
			if(isset($id)) {
				$this->db->update('languages', array('default' => 0));
				$data = array('default' => 1);
				$this->db->where('id', $id);
				$this->db->update('languages', $data);
				$this->session->set_flashdata('notification', __("Language updated"));
			}
			redirect('admin/language');		
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
		function add()
		{
			if ( $post = $this->input->post('submit') )
			{
				$data = array(
							'name'		=> $this->input->post('name'),
							'code'		=> $this->input->post('code'),
							'ordering'	=> $this->input->post('ordering').$this->input->post('uri'),
							'active'	=> $this->input->post('active')
						);
						
				$this->db->insert('languages', $data);
				$id = $this->db->insert_id();	
					
				$this->session->set_flashdata('notification', __("Language added"));	
				
				redirect('admin/language');
			}
			else
			{
				
				$this->layout->load($this->template, 'add');
			}
		}
		
		function edit()
		{
			if ( $post = $this->input->post('submit') )
			{
				$data = array(
							'title'				=> $this->input->post('title'),
							'menu_title'		=> $this->input->post('menu_title'),
							'uri'				=> $this->input->post('parent').$this->input->post('uri'),
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