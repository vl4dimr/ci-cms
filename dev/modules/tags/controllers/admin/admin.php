<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* $Id$
**/
	
class Admin extends Controller {
	
	
	var $fields;
	var $_settings = array();
	function Admin()
	{

		parent::Controller();
	
		$this->fields = array(
			'userid' => 'heriniaina.eugene',
			'ttl' => 86400,
			'thumbwidth' => 144,
			'maxwidth' => 640
			);
		$this->load->library('administration');
		$this->template['module'] = "palbum";
		$this->template['admin']		= true;
	}
	
	
	function index()
	{
		//fields
		$this->user->check_level($this->template['module'], LEVEL_EDIT);		
		$settings = isset($this->system->palbum_settings) ? unserialize($this->system->palbum_settings) : array();
		foreach ($this->fields as $key => $value)
		{
			$this->_settings[$key] = isset($settings[$key])? $settings[$key] : $value;
		}
		
		$this->template['settings'] = $this->_settings;
			
		$this->layout->load($this->template, 'admin/settings');
	}
	
	function save()
	{
		$this->user->check_level($this->template['module'], LEVEL_EDIT);		
		$setting = is_array($this->input->post('settings')) ? serialize($this->input->post('settings')) : '';
		$this->system->set('palbum_settings', $setting);
		$this->session->set_flashdata('notification', __("Settings saved", $this->template['module']));
		redirect('admin/palbum');
	}

}

?>