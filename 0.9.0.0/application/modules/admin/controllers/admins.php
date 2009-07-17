<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Admins extends Controller {
		var $levels;
		
		function Admins()
		{

			parent::Controller();
			//$this->output->enable_profiler(true);
			$this->load->library('administration');
			
			$this->template['module'] = "admin";
			$this->template['levels'] = array(
					0 => __("No access", $this->template['module']),
					1 => __("Can view", $this->template['module']),
					2 => __("Can add", $this->template['module']),
					3 => __("Can edit", $this->template['module']),
					4 => __("Can delete", $this->template['module'])
					);
			$this->user->check_level($this->template['module'], LEVEL_EDIT);
		}
		
		function index()
		{
		
			$this->db->order_by('module ASC, level DESC');
			$query = $this->db->get('admins');
			
			$this->template['admins'] = $query->result_array();
			$this->layout->load($this->template, 'admins/index');
		}
		
		function create()
		{
			$this->user->check_level($this->template['module'], LEVEL_ADD);
			$this->layout->load($this->template, 'admins/create');
		}
		
		function edit($id)
		{
			$this->user->check_level($this->template['module'], LEVEL_EDIT);
			$this->db->where('id', $id);
			$query = $this->db->get('admins');
			$this->template['admin'] = $query->row_array();
			$this->layout->load($this->template, 'admins/edit');
		}
		
		function save($id = null)
		{
			$this->user->check_level($this->template['module'], LEVEL_EDIT);
			if ($this->input->post('submit'))
			{
				$this->db->where(array(
					'username' => $this->input->post('username'),
					'module' => $this->input->post('module'))
					);
				$query = $this->db->get('admins');
				$data = array(
					'username' => $this->input->post('username'),
					'module' => $this->input->post('module'),
					'level' => $this->input->post('level')
					);
				if ($query->num_rows() > 0)
				{
					$this->db->where(array(
					'username' => $this->input->post('username'),
					'module' => $this->input->post('module'))
					);
					$this->db->update('admins', $data);
					$this->session->set_flashdata('notification', __("Administrator list updated", $this->template['module']));
		
				}
				else
				{
					$this->db->insert('admins', $data);	
					$this->session->set_flashdata('notification', __("Administrator added in list", $this->template['module']));
				}
			}
			else
			{
				$this->session->set_flashdata('notification', __("Nothing to save", $this->template['module']));
			}
			redirect('admin/admins');			
		}
		
		function delete($id)
		{
			$this->user->check_level($this->template['module'], LEVEL_DEL);
			$this->db->where('id', $id);
			$query = $this->db->get('admins');
			if ($query->num_rows == 1)
			{
				$row = $query->row_array();
				
				if ($this->user->username == $row['username']) 
				{
					$this->session->set_flashdata('notification', __("You cannot remove yourself from the list.", $this->template['module']));
				}
				else
				{
				
					$this->db->where('id', $id);
					$this->db->delete('admins');
					$this->session->set_flashdata('notification', __("User removed from administrator list", $this->template['module']));
				}
			}
			else
			{
					$this->session->set_flashdata('notification', __("User not found in the list", $this->template['module']));
			}
			redirect('admin/admins');
		}
	}

?>