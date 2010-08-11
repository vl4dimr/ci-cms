<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$query =
"CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('institute_profiles') . " (
  id int(11) NOT NULL auto_increment,
  p_id varchar(20) NOT NULL default '',
  p_name varchar(255) NOT NULL default '',
  p_address varchar(255) NOT NULL default '',
  p_city varchar(255) NOT NULL default '',
  p_state varchar(255) NOT NULL default '',
  p_zip varchar(255) NOT NULL default '',
  p_country varchar(255) NOT NULL default '',
  p_phone varchar(255) NOT NULL default '',
  p_date int(11) NOT NULL default 0,
  PRIMARY KEY  (id),
  INDEX (p_name)
);";

$this->db->query($query);

$query = 
"CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('institute_registrations')  . " (
`id` INT NOT NULL  AUTO_INCREMENT,
`student_id` VARCHAR( 100 ) NOT NULL default '',
`class_id` VARCHAR(100) NOT NULL default '',
PRIMARY KEY ( `id` ) ,
INDEX ( `student_id` )
);";

$this->db->query($query);


$query = 
"CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('institute_classes')  . " (
`id` INT NOT NULL  AUTO_INCREMENT,
`c_id` VARCHAR( 100 ) NOT NULL ,
`c_parent` VARCHAR( 100 ) NOT NULL ,
`c_name` VARCHAR( 100 ) NOT NULL ,
`c_description` TEXT NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `c_id` ),
INDEX ( `c_name` )
);";

$this->db->query($query);

