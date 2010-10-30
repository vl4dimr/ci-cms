<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Admin extends Controller {

		function Admin()
		{
			parent::Controller();

			$this->load->library('administration');
			$this->user->lang = $this->session->userdata('lang');

			$this->template['module']	= 'freeback';
			$this->template['admin']		= true;

			$this->load->model('freeback_model');

			$this->page_id = ( $this->uri->segment(4) ) ? $this->uri->segment(4) : NULL;

		}

		function index($start = 0)
		{
			$per_page = 20;
			$this->user->check_level($this->template['module'], LEVEL_VIEW);
			$data = $this->freeback_model->getMailto();
			$this->template['rows'] = $data;
			$this->template['pages'] = array_slice($data, $start, $per_page);
			$this->load->library('pagination');
			$config['uri_segment'] = 4;
			$config['first_link'] = __('First');
			$config['last_link'] = __('Last');
			$config['base_url'] = base_url() . 'admin/freeback/index';
			$config['total_rows'] = count($data);
			$config['per_page'] = $per_page;

			$this->pagination->initialize($config);

			$this->template['pager'] = $this->pagination->create_links();
			$this->layout->load($this->template, 'admin');
		}

		function create()
		{
			$this->user->check_level($this->template['module'], LEVEL_ADD);
			if ( $post = $this->input->post('submit') )
			{
				$this->load->library('form_validation');
				$this->form_validation->set_error_delimiters('<p style="color:#900">', '</p>');
				$this->form_validation->set_rules('title', __("Title", $this->template['module']), 'required');
				$this->form_validation->set_message('required', __("The %s field can not be empty", $this->template['module']));
				$this->form_validation->set_rules('email', __("Email", $this->template['module']), 'required|valid_email');
				$this->form_validation->set_message('valid_email', __("The %s field must contain a valid email address.", $this->template['module']));
				if ($this->form_validation->run() == FALSE) {
					$this->session->set_flashdata('notification', $this->form_validation->error_string());
					redirect('admin/freeback/create/');
				} else {
					$data = array(
								'title'				=> strip_tags($this->input->post('title')),
								'status'			=> $this->input->post('status'),
								'email'			=> $this->input->post('email'),
								'lang'				=> $this->input->post('lang')
							);
					$this->db->insert('freeback', $data);
					$id = $this->db->insert_id();
					$this->session->set_flashdata('notification', 'Responder "'.$this->input->post("title").'" has been created, continue editing here');
					redirect('admin/freeback');
				}
			} else {
				$this->layout->load($this->template, 'create');
			}
		}

		function move($direction, $id)
		{

			if (!isset($direction) || !isset($id))
			{
				redirect('admin/freeback');
			}
			$this->freeback_model->move($direction, $id);
			redirect('admin/freeback');
		}

		function edit()
		{
			$this->user->check_level($this->template['module'], LEVEL_EDIT);
			if ( $post = $this->input->post('submit') )
			{
				$this->load->library('form_validation');
				$this->form_validation->set_error_delimiters('<p style="color:#900">', '</p>');
				$this->form_validation->set_rules('title', __("Title", $this->template['module']), 'required');
				$this->form_validation->set_message('required', __("The %s field can not be empty", $this->template['module']));
				$this->form_validation->set_rules('email', __("Email", $this->template['module']), 'required|valid_email');
				$this->form_validation->set_message('valid_email', __("The %s field must contain a valid email address.", $this->template['module']));
				if ($this->form_validation->run() == FALSE) {					$this->session->set_flashdata('notification', $this->form_validation->error_string());
					redirect('admin/freeback/edit/'.$this->page_id);
				} else {					$data = array(
								'title'				=> strip_tags($this->input->post('title')),
								'status'			=> $this->input->post('status'),
								'email'			=> $this->input->post('email'),
								'lang'				=> $this->input->post('lang')
							);
					$this->db->where('id', $this->page_id);
					$this->db->update('freeback', $data);
					$this->session->set_flashdata('notification', 'Responder "'.$this->input->post("title").'" has been saved ...');
					redirect('admin/freeback');
				}
			} else {				$this->template['mailto'] = $this->freeback_model->getMailto( array('id' => $this->page_id) );
				$this->layout->load($this->template, 'edit');
			}
		}

		function delete()
		{
			$this->user->check_level($this->template['module'], LEVEL_DEL);
			if ( $post = $this->input->post('submit') )
			{
				$this->db->where('id', $this->page_id);
				$query = $this->db->delete('freeback');
				$this->session->set_flashdata('notification', 'Responder has been deleted.');
				redirect('admin/freeback');
			}
			else
			{
				$this->template['mailto'] = $this->freeback_model->getMailto( array('id' => $this->page_id) );
				$this->layout->load($this->template, 'delete');
			}
		}
	}

?>