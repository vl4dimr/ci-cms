<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

//update to 1.0.1
$version = "1.0.1";

//Added French language

if ($this->system->modules[$module]['version'] < $version)
{
	$this->session->set_flashdata("notification", sprintf(__("Freeback module updated to %s", $module), $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
}
