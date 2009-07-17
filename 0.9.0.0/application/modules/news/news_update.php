<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

//get the new module version from xml file.
$module = 'news';

//update to 1.1.0
$version = "1.1.0";

//compare it with the installed module version 

if ($this->system->modules[$module]['version'] < $version)
{
	
	$this->load->dbforge();
	$fields = array(
		'ordering' => array('type' => 'int', 'default' => 0)
	);
	
	$this->dbforge->add_column('news', $fields);
	
	$this->session->set_flashdata("notification", sprintf(__("News module updated to %s", $module), $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
}

