<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$query =
"CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('institute_students') . " (
  id int(11) NOT NULL auto_increment,
  s_id varchar(20) NOT NULL default '',
  s_name varchar(255) NOT NULL default '',
  s_address varchar(255) NOT NULL default '',
  s_city varchar(255) NOT NULL default '',
  s_state varchar(255) NOT NULL default '',
  s_zip varchar(255) NOT NULL default '',
  s_country varchar(255) NOT NULL default '',
  s_phone varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY g_name (g_name)
);";

$this->db->query($query);

$query = 
"CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('institute_classes')  . " (
`id` INT NOT NULL  AUTO_INCREMENT,
`name` VARCHAR( 100 ) NOT NULL ,
`value` TEXT NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `name` )
);";

$this->db->query($query);

