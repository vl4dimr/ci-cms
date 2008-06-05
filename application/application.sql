-- phpMyAdmin SQL Dump
-- version 2.9.1.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generato il: 03 Giu, 2008 at 02:27 PM
-- Versione MySQL: 5.0.27
-- Versione PHP: 5.2.0
-- 
-- Database: `ci`
-- 

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;

-- 
-- Dump dei dati per la tabella `ci_blocks`
-- 

INSERT INTO `ci_blocks` (`id`, `area`, `theme`, `weight`, `module`, `method`) VALUES 
(102, 1, 'default', 0, 'blog', 'latest_items');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Dump dei dati per la tabella `ci_languages`
-- 

INSERT INTO `ci_languages` (`id`, `code`, `name`, `ordering`, `active`, `default`) VALUES 
(1, 'en', 'English', 0, 1, 0),
(2, 'fr', 'Fran�ais', 0, 1, 1);

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
  PRIMARY KEY  (`id`),
  KEY `active` (`active`),
  KEY `weight` (`weight`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Dump dei dati per la tabella `ci_navigation`
-- 

INSERT INTO `ci_navigation` (`id`, `parent_id`, `active`, `weight`, `title`, `uri`) VALUES 
(1, 0, 1, 1, 'Home', ''),
(3, 0, 1, 3, 'About', 'about'),
(5, 0, 1, 2, 'Blog', 'blog');

-- --------------------------------------------------------

-- 
-- Struttura della tabella `ci_pages`
-- 

DROP TABLE IF EXISTS `ci_pages`;
CREATE TABLE `ci_pages` (
  `id` int(11) NOT NULL auto_increment,
  `active` tinyint(1) NOT NULL default '0',
  `uri` varchar(40) NOT NULL default '',
  `menu_title` varchar(100) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `meta_keywords` varchar(255) default NULL,
  `meta_description` varchar(255) default NULL,
  `body` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uri` (`uri`),
  KEY `active` (`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- 
-- Dump dei dati per la tabella `ci_pages`
-- 

INSERT INTO `ci_pages` (`id`, `active`, `uri`, `menu_title`, `title`, `meta_keywords`, `meta_description`, `body`) VALUES 
(1, 1, 'home', 'Home', 'What is a Content Management System?', 'Blaze, CMS', 'Welcome to the Blaze CMS site ...', '<p>A content management system (CMS) is a program used to create a framework for the content of a Web site. CMSes are deployed primarily for interactive use by a potentially large number of contributors. For example, the software for the website Wikipedia is based on a wiki, which is a particular type of content management system. As used in this article, Content Management means Web Content Management. Other related forms of content management are listed below.<br /><br />The content managed includes computer files, image media, audio files, electronic documents and web content. The idea behind a CMS is to make these files available inter-office, as well as over the web. A CMS would most often be used as an archive as well. Many companies use a CMS to store files in a non-proprietary form. Companies use a CMS to share files with ease, as most systems use server-based software, even further broadening file availability. As shown below, many CMSs include a feature for Web Content, and some have a feature for a "workflow process".<br /><br />"Workflow" is the idea of moving an electronic document along for either approval, or for adding content. Some CMSs will easily facilitate this process with email notification, and automated routing. This is ideally a collaborative creation of documents. A CMS facilitates the organization, control, and publication of a large body of documents and other content, such as images and multimedia resources.<br /><br />A Web content management system is a CMS with additional features to ease the tasks required to publish web content to web sites.</p>');

-- --------------------------------------------------------

-- 
-- Struttura della tabella `ci_sessions`
-- 

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL default '0',
  `ip_address` varchar(16) NOT NULL default '0',
  `user_agent` varchar(50) NOT NULL default '',
  `last_activity` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dump dei dati per la tabella `ci_sessions`
-- 

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`) VALUES 
('13a3f21c0de44a8134f67e721b9c0ccb', '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1;', 1212495759);

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
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- 
-- Dump dei dati per la tabella `ci_settings`
-- 

INSERT INTO `ci_settings` (`id`, `name`, `value`) VALUES 
(1, 'site_name', 'Codeigniter CMS'),
(2, 'meta_keywords', 'CMS, CodeIgniter'),
(3, 'meta_description', 'Yet another CMS with Codeigniter'),
(4, 'cache', '0'),
(5, 'cache_time', '300'),
(6, 'theme', 'default'),
(7, 'template', 'index'),
(8, 'page_home', 'home');

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
  PRIMARY KEY  (`id`),
  KEY `username` (`username`),
  KEY `password` (`password`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- Dump dei dati per la tabella `ci_users`
-- 
