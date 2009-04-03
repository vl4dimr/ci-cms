-- $Id$
-- --------------------------------------------------------

-- 
-- Struttura della tabella `ci_blocks`
-- 

DROP TABLE IF EXISTS `ci_blocks`;
CREATE TABLE `ci_blocks` (
  `id` int(11) NOT NULL auto_increment,
  `area` int(11) NOT NULL default '0',
  `theme` varchar(50) NOT NULL default '',
  `weight` tinyint(4) NOT NULL default '0',
  `module` varchar(50) NOT NULL default '',
  `method` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
);

-- 
-- Dump dei dati per la tabella `ci_blocks`
-- 

INSERT INTO `ci_blocks` VALUES (177, 2, 'default', 0, 'page', 'new_pages');
INSERT INTO `ci_blocks` VALUES (176, 1, 'default', 0, 'language', 'language_links');

-- --------------------------------------------------------

-- 
-- Struttura della tabella `ci_languages`
-- 

DROP TABLE IF EXISTS `ci_languages`;
CREATE TABLE `ci_languages` (
  `id` int(11) NOT NULL auto_increment,
  `code` char(2) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `ordering` tinyint(4) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '0',
  `default` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `code` (`code`)
);

-- 
-- Dump dei dati per la tabella `ci_languages`
-- 

INSERT INTO `ci_languages` VALUES (1, 'en', 'English', 0, 1, 0);
INSERT INTO `ci_languages` VALUES (3, 'fr', 'Français', 0, 1, 0);

-- --------------------------------------------------------

-- 
-- Struttura della tabella `ci_navigation`
-- 
DROP TABLE IF EXISTS `ci_navigation`;
CREATE TABLE `ci_navigation` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '0',
  `weight` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `uri` varchar(100) NOT NULL default '',
  `lang` varchar(5) NOT NULL default 'en',
  PRIMARY KEY  (`id`),
  KEY `active` (`active`),
  KEY `weight` (`weight`),
  KEY `parent_id` (`parent_id`)
);

-- 
-- Dump dei dati per la tabella `ci_navigation`
-- 

INSERT INTO `ci_navigation` VALUES (1, 0, 1, 1, 'Home', '', 'en');
INSERT INTO `ci_navigation` VALUES (3, 0, 1, 2, 'About', 'about', 'en');


-- --------------------------------------------------------

-- 
-- Struttura della tabella `ci_pages`
-- 

DROP TABLE IF EXISTS `ci_pages`;
CREATE TABLE `ci_pages` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '0',
  `uri` varchar(40) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `meta_keywords` varchar(255) default NULL,
  `meta_description` varchar(255) default NULL,
  `body` text NOT NULL,
  `lang` varchar(5) NOT NULL default 'en',
  `weight` int(11) NOT NULL default '0',
  `hit` int(11) NOT NULL default '0',
  `options` text NOT NULL,
  `date` INT NOT NULL ,
  PRIMARY KEY  (`id`),
  KEY `uri` (`uri`),
  KEY `active` (`active`)
) ;

-- 
-- Dump dei dati per la tabella `ci_pages`
-- 

INSERT INTO `ci_pages` (`id`, `active`, `uri`, `title`, `meta_keywords`, `meta_description`, `body`) VALUES 
(1, 1, 'home', 'What is a Content Management System?', 'Blaze, CMS', 'Welcome to the Blaze CMS site ...', '<p>A content management system (CMS) is a program used to create a framework for the content of a Web site. CMSes are deployed primarily for interactive use by a potentially large number of contributors. For example, the software for the website Wikipedia is based on a wiki, which is a particular type of content management system. As used in this article, Content Management means Web Content Management. Other related forms of content management are listed below.<br /><br />The content managed includes computer files, image media, audio files, electronic documents and web content. The idea behind a CMS is to make these files available inter-office, as well as over the web. A CMS would most often be used as an archive as well. Many companies use a CMS to store files in a non-proprietary form. Companies use a CMS to share files with ease, as most systems use server-based software, even further broadening file availability. As shown below, many CMSs include a feature for Web Content, and some have a feature for a "workflow process".<br /><br />"Workflow" is the idea of moving an electronic document along for either approval, or for adding content. Some CMSs will easily facilitate this process with email notification, and automated routing. This is ideally a collaborative creation of documents. A CMS facilitates the organization, control, and publication of a large body of documents and other content, such as images and multimedia resources.<br /><br />A Web content management system is a CMS with additional features to ease the tasks required to publish web content to web sites.</p>');

-- --------------------------------------------------------

-- 
-- Struttura della tabella `ci_sessions`
-- 

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS  `ci_sessions` (
	session_id varchar(40) DEFAULT '0' NOT NULL,
	ip_address varchar(16) DEFAULT '0' NOT NULL,
	user_agent varchar(50) NOT NULL,
	last_activity int(10) unsigned DEFAULT 0 NOT NULL,
	user_data text NOT NULL,
	PRIMARY KEY (session_id)
);

-- --------------------------------------------------------

-- 
-- Struttura della tabella `ci_settings`
-- 

DROP TABLE IF EXISTS `ci_settings`;
CREATE TABLE `ci_settings` (
  `id` tinyint(4) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ;

-- 
-- Dump dei dati per la tabella `ci_settings`
-- 

INSERT INTO `ci_settings` VALUES (1, 'site_name', 'Codeigniter CMS');
INSERT INTO `ci_settings` VALUES (2, 'meta_keywords', 'CMS, CodeIgniter');
INSERT INTO `ci_settings` VALUES (3, 'meta_description', 'Yet another CMS with Codeigniter');
INSERT INTO `ci_settings` VALUES (4, 'cache', '0');
INSERT INTO `ci_settings` VALUES (5, 'cache_time', '300');
INSERT INTO `ci_settings` VALUES (6, 'theme', 'default');
INSERT INTO `ci_settings` VALUES (7, 'template', 'index');
INSERT INTO `ci_settings` VALUES (8, 'page_home', 'home');
INSERT INTO `ci_settings` VALUES (9, 'debug', '0');
INSERT INTO `ci_settings` VALUES (10, 'version', '0.2.0.1');

-- --------------------------------------------------------

-- 
-- Struttura della tabella `ci_users`
-- 

DROP TABLE IF EXISTS `ci_users`;
CREATE TABLE `ci_users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `password` varchar(40) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `status` enum('active','disabled') NOT NULL default 'active',
  `lastvisit` int(11) NOT NULL default '0',
  `registered` int(11) NOT NULL default '0',
  `activation` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `username` (`username`),
  KEY `password` (`password`),
  KEY `status` (`status`)
) ;

     

-- 
-- Struttura della tabella `ci_modules`
-- 
DROP TABLE IF EXISTS `ci_modules`;
CREATE TABLE `ci_modules` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `with_admin` tinyint(4) NOT NULL default '0',
  `version` varchar(10) NOT NULL default '',
  `status` tinyint(4) NOT NULL default '0',
  `ordering` tinyint(4) NOT NULL default '0',
  `info` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
)  ;

-- 
-- Dump dei dati per la tabella `ci_modules`
-- 
INSERT INTO `ci_modules` VALUES (1, 'admin', 0, '1.0.1', 1, 5, '', 'Admin core module');
INSERT INTO `ci_modules` VALUES (2, 'module', 0, '1.0.0', 1, 20, '', 'Module core module');
INSERT INTO `ci_modules` VALUES (3, 'page', 1, '1.0.2', 1, 60, '', 'Page core module');
INSERT INTO `ci_modules` VALUES (4, 'language', 1, '1.0.0', 1, 10, '', 'Language core module');
INSERT INTO `ci_modules` VALUES (5, 'member', 1, '1.0.0', 1, 30, '', 'Member core module');   
INSERT INTO `ci_modules` VALUES (6, 'search', 0, '1.0.0', 1, 50, '', 'Search core module');   

-- 
-- Struttura della tabella `ci_admins`
-- 

DROP TABLE IF EXISTS `ci_admins`;
CREATE TABLE `ci_admins` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL default '',
  `module` varchar(100) NOT NULL default '',
  `level` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `username` (`username`)
)  ;


DROP TABLE IF EXISTS `ci_group_members`;
CREATE TABLE IF NOT EXISTS ci_group_members (
  id int(11) NOT NULL auto_increment,
  g_user varchar(255) NOT NULL default '',
  g_id varchar(20) NOT NULL default '',
  g_from int(11) NOT NULL default '0',
  g_to int(11) NOT NULL default '0',
  g_date int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY g_user (g_user,g_id)
);


DROP TABLE IF EXISTS `ci_groups`;
CREATE TABLE IF NOT EXISTS ci_groups (
  id int(11) NOT NULL auto_increment,
  g_id varchar(20) NOT NULL default '',
  g_name varchar(255) NOT NULL default '',
  g_desc text NOT NULL,
  g_date int(11) NOT NULL default '0',
  g_info text NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY g_id (g_id,g_name)
);

INSERT INTO ci_groups (g_id, g_name, g_desc) VALUES ('0', 'Everybody', 'This is everybody who visits the site including non members');
INSERT INTO ci_groups (g_id, g_name, g_desc) VALUES ('1', 'Members Only', 'This is everybody who is member of the site');

DROP TABLE IF EXISTS `ci_search_results`;
CREATE TABLE IF NOT EXISTS ci_search_results (
  id int(11) NOT NULL auto_increment,
  s_rows text NOT NULL,
  s_tosearch varchar(255) NOT NULL default '',
  s_date int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);

CREATE TABLE IF NOT EXISTS `ci_images` (
  `id` int(11) NOT NULL auto_increment,
  `module` varchar(100) NOT NULL default '',
  `file` varchar(255) NOT NULL default '',
  `src_id` int(11) NOT NULL default '0',
  `ordering` tinyint(4) NOT NULL default '0',
  `info` text NOT NULL,
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE IF NOT EXISTS ci_captcha(
	captcha_id bigint( 13 ) unsigned NOT NULL AUTO_INCREMENT ,
	captcha_time int( 10 ) unsigned NOT NULL ,
	ip_address varchar( 16 ) default '0' NOT NULL ,
	word varchar( 20 ) NOT NULL ,
	PRIMARY KEY ( captcha_id ) ,
	KEY ( word )
); 
