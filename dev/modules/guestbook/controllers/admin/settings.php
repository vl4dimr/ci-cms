<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
class Settings extends Controller {
	
	
	var $fields;
	var $_settings = array();
	function Settings()
	{

		parent::Controller();
		$this->load->library('administration');
		$this->template['module'] = "guestbook";
		$this->load->model('guestbook_model', 'gbook');
		$this->template['admin']		= true;
	}
	
	
	function index()
	{
		//fields
		
		$this->user->check_level($this->template['module'], LEVEL_EDIT);		
		
		$this->template['title'] = __("Guestbook settings", "guestbook");

		foreach ($this->gbook->settings as $key => $value)
		{
			
			$this->_settings[$key] = $this->gbook->settings[$key];
		}
		
		$this->template['settings'] = $this->_settings;
			
		$this->layout->load($this->template, 'admin/settings');
	}
	
	function save()
	{
		$settings = $this->input->post('settings');
		foreach($settings as $key => $val)
		{
			$this->gbook->save_settings($key, $val);
		}
		$this->session->set_flashdata('notification', __("Settings saved", $this->template['module']));
		redirect('admin/guestbook/settings');
	}

}

?>