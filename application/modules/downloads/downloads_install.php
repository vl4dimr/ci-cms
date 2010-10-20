<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

@mkdir('./media/files');

$this->db->query("
CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('download_doc') . "` (
  `id` int(11) NOT NULL auto_increment,
  `cat` int(11) NOT NULL default '0',
  `file_id` int(11) NOT NULL default '0',
  `file_link` varchar(255) NOT NULL default '',
  `weight` tinyint(4) NOT NULL default '0',
  `type` varchar(20) NOT NULL default 'link',
  `lang` varchar(10) NOT NULL default '',
  `pic` tinytext NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `username` varchar(20) NOT NULL default '',
  `keywords` text NOT NULL,
  `date` int(11) NOT NULL default '0',
  `hit` mediumint(9) NOT NULL default '0',
  `status` tinyint(4) NOT NULL default '0',
  `acces` varchar(20) NOT NULL default '0',
  `icon` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `cat` (`cat`,`title`)
);
");

$this->db->query("
CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('download_cat') . "` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `icon` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `date` int(11) NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `lang` char(5) NOT NULL default '',
  `weight` int(11) NOT NULL default '0',
  `status` int(5) NOT NULL default '1',
  `acces` varchar(20) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `title` (`title`)
);
");

$this->db->query("
CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('download_files') . "` (
`id` INT NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 255 ) NOT NULL ,
`file` VARCHAR( 255 ) NOT NULL ,
`date` INT NOT NULL ,
`size` DOUBLE NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `name` )
);
");

$this->db->query("
CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('download_settings') . "` (
`id` INT NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 255 ) NOT NULL ,
`value` TEXT,
PRIMARY KEY ( `id` ) ,
INDEX ( `name` )
);
");

$this->db->query("INSERT INTO `" . $this->db->dbprefix('download_settings') . "` (name, value) VALUES ('allowed_file_types', 'gif|jpg|png|bmp|doc|docx|xls|mp3|swf|exe|pdf|wav|zip'), ('upload_path', './media/files/')");