<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
class Settings extends Controller {
	
	
	var $fields;
	var $_settings = array();
	function Settings()
	{

		parent::Controller();
	
		$this->fields = array(
			'apikey' => '',
			'rootname' => 'Root',
			'rootdesc' => 'Here are some links that we want to share to you',
			);
		$this->load->library('administration');
		$this->template['module'] = "links";
		$this->template['admin']		= true;
	}
	
	
	function index()
	{
		//fields
		$this->user->check_level($this->template['module'], LEVEL_EDIT);		
		$settings = isset($this->system->links_settings) ? unserialize($this->system->links_settings) : array();
		foreach ($this->fields as $key => $value)
		{
			$this->_settings[$key] = isset($settings[$key])? $settings[$key] : $value;
		}
		
		$this->template['settings'] = $this->_settings;
			
		$this->template['themes'] = $this->layout->get_themes();
		$this->layout->load($this->template, 'admin/settings');
	}
	
	function save()
	{
		$setting = is_array($this->input->post('settings')) ? serialize($this->input->post('settings')) : '';
		$this->system->set('links_settings', $setting);
		$this->session->set_flashdata('notification', __("Settings saved", $this->template['module']));
		redirect('admin/links/settings');
	}

}

?>