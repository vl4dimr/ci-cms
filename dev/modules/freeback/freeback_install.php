<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->dbforge();
$fields = array (
		'id' => array(
			'type' => 'INT',
			'auto_increment' => TRUE,
		),
		'title' => array(
			 'type' => 'VARCHAR',
			 'constraint' => 255,
			 'default' => ''
		),
		'lang' => array(
			 'type' => 'CHAR',
			 'constraint' => 5,
			 'default' => 'en'
		),
		'status' => array(
			 'type' => 'TINYINT',
			 'constraint' => 1,
			 'default' => '1'
		),
		'email' => array(
			 'type' => 'VARCHAR',
			 'constraint' => 255,
			 'default' => ''
		),
		'weight' => array(
			 'type' => 'INT',
			 'constraint' => 3,
			 'default' => '0'
		),
		'hit' => array(
			 'type' => 'INT',
			 'constraint' => 11,
			 'default' => '0'
		));
$this->dbforge->add_field($fields);
$this->dbforge->add_key('id', TRUE);
$this->dbforge->add_key('status');
$this->dbforge->create_table('freeback', TRUE);
