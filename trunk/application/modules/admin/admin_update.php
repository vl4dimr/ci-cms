<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

//get the new module version from xml file.
$module = 'admin';

//update to 1.0.1
$version = "1.0.1";

//compare it with the installed module version 

if ($this->system->modules[$module]['version'] < $version)
{
	/*
	$this->load->dbforge();
	$fields = array(
		'activation' => array('type' => 'varchar', 'constraint' => '100', 'default' => '')
	);
	
	$this->dbforge->add_column('users', $fields);
	*/
	$this->session->set_flashdata("notification", sprintf(__("Admin module updated to %s"), $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
}


?>