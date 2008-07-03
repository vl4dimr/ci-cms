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
					0 => __("No access"),
					1 => __("Can view"),
					2 => __("Can add"),
					3 => __("Can edit"),
					4 => __("Can delete")
					);
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
			$this->layout->load($this->template, 'admins/create');
		}
		
		function edit($id)
		{
			$this->db->where('id', $id);
			$query = $this->db->get('admins');
			$this->template['admin'] = $query->row_array();
			$this->layout->load($this->template, 'admins/edit');
		}
		
		function save($id = null)
		{
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
					$this->session->set_flashdata('notification', __("Administrator list updated"));
		
				}
				else
				{
					$this->db->insert('admins', $data);	
					$this->session->set_flashdata('notification', __("Administrator added in list"));
				}
			}
			else
			{
				$this->session->set_flashdata('notification', __("Nothing to save"));
			}
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