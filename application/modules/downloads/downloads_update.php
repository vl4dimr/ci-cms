<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

//get the new module version from xml file.
$module = 'downloads';

//update to 1.1.0
$version = "1.1.2";

//compare it with the installed module version 

if ($this->system->modules[$module]['version'] < $version)
{
	
	//create missing dir
	@mkdir('media/images/downloads');
	@copy(APPPATH . '/modules/downloads/images/avi.gif', 'media/images/downloads/avi.gif');
	@copy(APPPATH . '/modules/downloads/images/dir.gif', 'media/images/downloads/dir.gif');
	@copy(APPPATH . '/modules/downloads/images/doc.gif', 'media/images/downloads/doc.gif');
	@copy(APPPATH . '/modules/downloads/images/exe.gif', 'media/images/downloads/exe.gif');
	@copy(APPPATH . '/modules/downloads/images/file.gif', 'media/images/downloads/file.gif');
	@copy(APPPATH . '/modules/downloads/images/jpg.gif', 'media/images/downloads/jpg.gif');
	@copy(APPPATH . '/modules/downloads/images/mp3.gif', 'media/images/downloads/mp3.gif');
	@copy(APPPATH . '/modules/downloads/images/pdf.gif', 'media/images/downloads/pdf.gif');
	@copy(APPPATH . '/modules/downloads/images/psd.gif', 'media/images/downloads/psd.gif');
	@copy(APPPATH . '/modules/downloads/images/zip.gif', 'media/images/downloads/zip.gif');

	$this->session->set_flashdata("notification", sprintf(__("%s module updated to %s", $module), $module, $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
}
