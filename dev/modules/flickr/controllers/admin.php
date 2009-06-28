<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Admin extends Controller {
		
		function Admin()
		{
			parent::Controller();
			
			$this->load->library('administration');
			$this->template['module']	= 'flickr';
			$this->template['admin']		= true;
			$this->load->model('flickr_model', 'flickr');
			$this->flickr->get_settings();
		}

		function index()
		{
			$this->user->check_level($this->template['module'], LEVEL_DEL);
			if ($post = $this->input->post('submit') )
			{
				$fields = array('flickr_api_key', 'flickr_api_secret', 'flickr_user_name', 'flickr_col_num');
				
				foreach ($fields as $field)
				{
					if ( $this->input->post($field) !== false)
					{
						$this->flickr->set($field, $this->input->post($field));
					}
				}
				$this->session->set_flashdata('notification', __("Settings updated", $this->template['module']));	
				redirect('admin/flickr');
			}
			else
			{
				$this->layout->load($this->template, 'settings');
			}
		}
	}

?>