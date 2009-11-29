<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

//get the new module version from xml file.
$module = 'page';

//update to 1.0.1
$version = "1.0.1";

//compare it with the installed module version 

if ($this->system->modules[$module]['version'] < $version)
{
	
	$this->load->dbforge();
	$fields = array(
		'date' => array('type' => 'int', 'default' => mktime())
	);
	
	$this->dbforge->add_column('pages', $fields);
	
	$this->session->set_flashdata("notification", sprintf(__("Page module updated to %s", $module), $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
}

//update to 1.0.2
$version = "1.0.2";

//compare it with the installed module version 

if ($this->system->modules[$module]['version'] < $version)
{
	
	$this->load->dbforge();
	$fields = array(
		'ordering' => array('type' => 'int', 'default' => '0')
	);
	
	$this->dbforge->add_column('images', $fields);
	
	$this->session->set_flashdata("notification", sprintf(__("Page module updated to %s", $module), $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
}

//update to 1.0.3
$version = "1.0.3";

// adding comments support

if ($this->system->modules[$module]['version'] < $version)
{

	$this->load->dbforge();


	$fields = array(
			'id' => array(
									 'type' => 'INT',
									 'constraint' => 11,
									 'unsigned' => TRUE,
									 'auto_increment' => TRUE
							  ),
			'page_id' => array(
									 'type' => 'INT',
									 'constraint' => '11',
							  ),
			'status' => array(
									 'type' => 'TINYINT',
									 'constraint' => '1',
									 'default' => 0
							  ),
			'date' => array(
									 'type' => 'INT',
									 'constraint' => '11',
									 'default' => 0
							  ),
			'author' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
			'email' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
			'website' => array(
									 'type' => 'VARCHAR',
									 'constraint' => '255',
							  ),
			'body' => array(
									 'type' => 'TEXT',
									 'null' => TRUE,
							  ),
			'ip' => array(
									 'type' =>'VARCHAR',
									 'constraint' => '100',
									 'default' => '',
							  )
			);
	$this->dbforge->add_field($fields); 
	$this->dbforge->add_key('id', TRUE);
	$this->dbforge->add_key('page_id');
	$this->dbforge->create_table('page_comments', TRUE);
	
	

	$fields = array(
		'email' => array('type' => 'varchar', 'constraint' => 255, 'default' => $this->system->admin_email)
	);
	
	$this->dbforge->add_column('pages', $fields);
	

	$this->session->set_flashdata("notification", sprintf(__("Page module updated to %s", $module), $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
	
}

//update to 1.0.4
$version = "1.0.4";

// captcha only if not logged in

if ($this->system->modules[$module]['version'] < $version)
{

	$this->session->set_flashdata("notification", sprintf(__("Page module updated to %s", $module), $version)) ;
	
	$data = array('version' => $version);
	$this->db->where(array('name'=> $module));
	$this->db->update('modules', $data);
	redirect("admin/module");
	
}