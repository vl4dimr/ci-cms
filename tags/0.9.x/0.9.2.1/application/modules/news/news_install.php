<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!is_dir('./media/captcha'))
{
	@mkdir('./media/captcha');
}		
@mkdir('./media/images');
@mkdir('./media/images/s');
@mkdir('./media/images/m');
@mkdir('./media/images/o');

$news_settings = serialize(array(
	'allow_comments' => 1,
	'approve_comments' => 1
	));
	
$this->system->set('news_settings', $news_settings);

$this->db->query("CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('news') . " ( `id` INT NOT NULL AUTO_INCREMENT , `cat` INT NOT NULL DEFAULT '0', `title` VARCHAR( 255 ) NOT NULL , `uri` VARCHAR( 255 ) NOT NULL , `lang` VARCHAR( 255 ) NOT NULL , `body` TEXT NOT NULL , `allow_comments` tinyint(1) NOT NULL DEFAULT '1', `comments` int(4) NOT NULL, `status` INT(3) NOT NULL DEFAULT '0', `date` INT NOT NULL , `author`VARCHAR( 255 ) NOT NULL , `email` VARCHAR( 255 ) NOT NULL , `notify` TINYINT NOT NULL , `hit` INT(11) NOT NULL DEFAULT '0', `ordering` INT(11) NOT NULL DEFAULT '0', PRIMARY KEY ( `id` ) , INDEX ( `title` ) )"); 

$this->db->query("CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('news_comments') . " (   `id` int(11) NOT NULL auto_increment,   `news_id` int(11) NOT NULL,   `status` int(2) NOT NULL DEFAULT '0',   `date` int(11) NOT NULL,   `author` varchar(50) NOT NULL,   `email` varchar(100) NOT NULL,   `website` varchar(150) NOT NULL,   `body` text NOT NULL,   `ip` varchar(150) NOT NULL,     PRIMARY KEY  (`id`),   KEY `news_id` (`news_id`),   KEY `date` (`date`),   KEY `status` (`status`) )");

$this->db->query("CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('captcha') . " ( captcha_id bigint( 13 ) unsigned NOT NULL AUTO_INCREMENT , captcha_time int( 10 ) unsigned NOT NULL , ip_address varchar( 16 ) default '0' NOT NULL , word varchar( 20 ) NOT NULL , PRIMARY KEY ( captcha_id ) , KEY ( word ) )");

$this->db->query("CREATE TABLE IF NOT EXISTS " . $this->db->dbprefix('news_cat') . "  ( `id` int(11) NOT NULL auto_increment, `pid` int(11) NOT NULL default '0', `title` varchar(255) NOT NULL default '', `icon` varchar(255) NOT NULL default '', `desc` text NOT NULL, `date` int(11) NOT NULL default '0', `username` varchar(20) NOT NULL default '', `lang` char(5) NOT NULL default '', `weight` int(11) NOT NULL default '0', `status` int(5) NOT NULL default '1', `acces` varchar(20) NOT NULL default '0', `uri` varchar(100) NOT NULL default '',PRIMARY KEY  (`id`), KEY `title` (`title`) )");

$fields = array(
	'id' => array(
			 'type' => 'INT',
			 'constraint' => 5,
			 'unsigned' => TRUE,
			 'auto_increment' => TRUE
	  ),
	'tag' => array(
			 'type' => 'VARCHAR',
			 'constraint' => '255',
	  ),
	'uri' => array(
			 'type' => 'VARCHAR',
			 'constraint' => '255',
	  ),
	'news_id' => array(
			 'type' => 'INT',
			 'constraint' => '5',
	  )
);
$this->dbforge->add_field($fields); 
$this->dbforge->add_key('id', TRUE);
$this->dbforge->add_key('tag');
$this->dbforge->create_table('news_tags', TRUE);

