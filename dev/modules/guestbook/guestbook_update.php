<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

//get the new module version from xml file.
$module = 'guestbook';


$version = "1.2.0";

//Bug fixed when sending notification by email

if ($this->system->modules[$module]['version'] < $version)
{
	
	
	$this->session->set_flashdata("notification", sprintf(__("Forum module updated to %s", $module), $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
}

