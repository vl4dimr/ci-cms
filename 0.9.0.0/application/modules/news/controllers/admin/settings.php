<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
class Settings extends Controller {
	
	
	var $fields;
	var $_settings = array();
	function Settings()
	{

		parent::Controller();
	
		$this->fields = array(
			'allow_comments' => 1,
			'approve_comments' => 1,
			'notify_admin' => 0
			);
		$this->load->library('administration');
		$this->lang = $this->session->userdata('lang');
		$this->template['module'] = "news";
		$this->template['admin']		= true;
	}
	
	
	function index()
	{
		//fields
		$this->user->check_level($this->template['module'], LEVEL_EDIT);		
		$settings = isset($this->system->news_settings) ? unserialize($this->system->news_settings) : array();
		foreach ($this->fields as $key => $value)
		{
			$this->_settings[$key] = isset($settings[$key])? $settings[$key] : $value;
		}
		
		$this->template['settings'] = $this->_settings;
			
		$this->layout->load($this->template, 'settings');
	}
	
	function save()
	{
		$setting = is_array($this->input->post('settings')) ? serialize($this->input->post('settings')) : '';
		$this->system->set('news_settings', $setting);
		$this->session->set_flashdata('notification', __("Settings saved", $this->template['module']));
		redirect('admin/news/settings');
	}

}

?>