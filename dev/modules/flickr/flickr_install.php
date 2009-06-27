<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$this->load->dbforge();

$fields = array(
		'id' => array(
								 'type' => 'INT',
								 'constraint' => 5,
								 'unsigned' => TRUE,
								 'auto_increment' => TRUE
						  ),
		'name' => array(
								 'type' => 'VARCHAR',
								 'constraint' => '100',
						  ),
		'value' => array(
								 'type' => 'TEXT',
						  )
);
$this->dbforge->add_field($fields); 
$this->dbforge->add_key('id', TRUE);
$this->dbforge->add_key('name');
$this->dbforge->create_table('flickr_settings', TRUE);
