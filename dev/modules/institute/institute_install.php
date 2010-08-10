<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$query =
"CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('guestbook_posts') . " (
  id int(11) NOT NULL auto_increment,
  g_name varchar(255) NOT NULL default '',
  g_site varchar(255) NOT NULL default '',
  g_email varchar(255) NOT NULL default '',
  g_ip varchar(255) NOT NULL default '',
  g_msg text NOT NULL,
  g_date int(11) NOT NULL default '0',
  g_country varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY g_name (g_name)
);";

$this->db->query($query);

$query = 
"CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('guestbook_settings')  . " (
`id` INT NOT NULL  AUTO_INCREMENT,
`name` VARCHAR( 100 ) NOT NULL ,
`value` TEXT NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `name` )
);";

$this->db->query($query);

