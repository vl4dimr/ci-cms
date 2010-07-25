<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!is_dir('./media/thumbnail'))
{
	@mkdir('./media/thumbnail');
}	

$tables = array(
		'links_links' => array (
			'fields' => array (
				'id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				  ),
				'title' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					  ),
				'url' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					  ),
				'lang' => array(
					 'type' => 'CHAR',
					 'constraint' => '5',
					  ),
				'cat' => array(
					 'type' =>'INT',
					 'default' => '0',
					  ),

				'description' => array(
					'type' => 'TEXT',
					'null' => TRUE,
				),
				'hit' => array(
					'type' => 'INT',
					'constraint' => 5,
					'default' => '0',
				),
				'username' => array(
					'type' =>'VARCHAR',
					'constraint' => '250',
					'default' => '',
				),
				'icon' => array(
					'type' =>'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'date' => array(
					'type' =>'INT',
					'default' => '0',
				)
			),
			'keys' => array (
				'id' => TRUE,
				'title' => FALSE
			)
		),
		'links_cat' => array(
			'fields' => array(
				'id' => array(
					 'type' => 'INT',
					 'constraint' => 5,
					 'unsigned' => TRUE,
					 'auto_increment' => TRUE
				  ),
				'title' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '255',
					  ),
				'lang' => array(
					 'type' => 'CHAR',
					 'constraint' => '5',
					  ),
				'pid' => array(
					 'type' =>'INT',
					 'default' => '0',
					  ),

				'description' => array(
					'type' => 'TEXT',
					'null' => TRUE,
				),
				'weight' => array(
					'type' => 'INT',
					'default' => '0',
				),
				'icon' => array(
					'type' =>'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'username' => array(
					'type' =>'VARCHAR',
					'constraint' => '250',
					'default' => '',
				),
				'date' => array(
					'type' =>'INT',
					'default' => '0',
				)
			),
			'keys' => array (
				'id' => TRUE,
				'title' => FALSE
			)
		)
	);

$this->load->dbforge();
foreach ($tables as $key => $table)
{
	$this->dbforge->add_field($table['fields']);
	foreach ($table['keys'] as $key1 => $val1)
	{
		$this->dbforge->add_key($key1, $val1);
	}
	$this->dbforge->create_table($key, TRUE);
}	

?>