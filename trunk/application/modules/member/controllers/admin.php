<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Admin extends Controller {
		
		function Admin()
		{
			parent::Controller();
			//$this->output->enable_profiler(true);
			
			
			$this->load->library('administration');
			$this->load->model('member_model');
			
			$this->template['module']	= 'member';
			$this->template['admin']		= true;
			$this->_init();
		}
		
		function index()
		{
			redirect('admin/member/listall');
		}
		
		function _init() 
		{
			/*
			* default values
			*/
			if (!isset($this->system->login_signup_enabled)) 
			{
				 $this->system->login_signup_enabled = 1;
			}
			
		}
		
		function listall() 
		{
			$debut = $this->uri->segment(4);
			$limit = $this->uri->segment(5);
			$this->template['members'] = $this->member_model->get_list();
			$this->load->library('pagination');

			$config['base_url'] = base_url() . 'member/listall/';
			$config['total_rows'] = $this->member_model->member_total;
			$config['per_page'] = '20'; 

			$this->pagination->initialize($config); 

			$this->template['pager'] = $this->pagination->create_links();
			
			$this->layout->load($this->template, 'admin');
			return;
		}
		
		
		function settings()
		{
		
		}
		
		function add()
		{
		
		}
		
		function profile($id)
		{
			if ($this->member_model->exists($id))
			{
				$this->member_model->delete($id);
				$this->session->set_flashdata('notification', __("User deleted"));
				redirect('admin/member');
			}

		}
		
		function edit($id) 
		{
		}
		
		function delete($id)
		{
			/*
			should check if 
			- Not deleting self
			- Can delete
			*/
			
			if ($this->member_model->exists($id))
			{
				$this->member_model->delete($id);
				$this->session->set_flashdata('notification', __("User deleted"));
				redirect('admin/member');
			}
		}
		
	}
	
?>