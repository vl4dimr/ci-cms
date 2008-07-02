<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Admins extends Controller {
		
		function Admins()
		{

			parent::Controller();
			//$this->output->enable_profiler(true);
			$this->load->library('administration');
			
			$this->template['module'] = "admin";
		}
		
		function index()
		{
			
			$this->db->order_by('module, level DESC');
			$query = $this->db->get('admins');
			
			$this->template['admins'] = $query->result_array();
			$this->layout->load($this->template, 'admins/index');
		}
		
		function create()
		{
			$this->layout->load($this->template, 'admins/edit');
		}
		
		function edit($id)
		{
			$this->db->where('id', $id);
			$query = $this->db->get('admins');
			$this->template['admin'] = $query->row_array();
			$this->layout->load($this->template, 'admins/edit');
		}
		
		function save()
		{
			$data = array(
				'username' => $this->input->post('username'),
				'module' => $this->input->post('module'),
				'level' => $this->input->post('level')
				);
			$this->data->insert('admins');
			$this->session->set_flashdata('notification', __("Administrator list saved"));
			redirect('admin/admins');
		}
		function delete($id)
		{
			$this->db->where('id', $id);
			$this->db->delete('admins');
			$this->session->set_flashdata('notification', __("User removed from administrator list"));
			redirect('admin/admins');
		}
	}

?>