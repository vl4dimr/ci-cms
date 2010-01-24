<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$tables = array(
		'tag_items' => array (
			'fields' => array (
				'id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				  ),
				'tag' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '100',
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
				'srcid' => array(
					 'type' =>'INT',
					 'default' => '0',
					  ),

				'module' => array(
					 'type' => 'VARCHAR',
					 'constraint' => '100',
					  ),
				'summary' => array(
					'type' => 'TEXT',
					'null' => TRUE,
				),
				'hit' => array(
					'type' => 'INT',
					'constraint' => 5,
					'default' => '0',
				)
			),
			'keys' => array (
				'id' => TRUE,
				'tag' => FALSE
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