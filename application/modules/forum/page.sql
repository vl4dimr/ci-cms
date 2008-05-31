

CREATE TABLE `ci_pages` (
  `id` int(11) NOT NULL auto_increment,
  `active` tinyint(1) NOT NULL,
  `uri` varchar(40) NOT NULL,
  `menu_title` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `meta_keywords` varchar(255) default NULL,
  `meta_description` varchar(255) default NULL,
  `body` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uri` (`uri`),
  KEY `active` (`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `module_pages`
--

INSERT INTO `ci_pages` (`id`, `active`, `uri`, `menu_title`, `title`, `meta_keywords`, `meta_description`, `body`) VALUES
(1, 1, 'home', 'Home', 'Welcome to Blaze!', 'Blaze, CMS', 'Welcome to the Blaze CMS site ...', '<p>Blaze is a free, open-source content management system built using <a href="http://codeigniter.com">CodeIgniter</a></p>\n<p>You can edit this page in the <a href="admin">administration</a> section of the site.</p>\n<p>Blaze strives to be:</p>\n<ul>\n<li>A Free and fairly licensed CMS</li>\n<li>Easy to Use</li>\n<li>Flexible</li>\n<li>Scalable</li>\n<li>Sexy</li>\n</ul>\n<p>Blaze is headed up by <a href="http://www.haughin.com">Elliot Haughin</a>, a web developer and devout CodeIgniter fan from England. If you have any questions about blaze, you can email him at: elliot@haughin.com</p>\n<p>But, Elliot is not the only person to make Blaze what it is... there are various developers, designers, and general geeks who help make this system what it is.<br />You can read all about them on the <a href="http://blaze.haughin.com/credits/">Blaze Credits</a> page.</p>\n<h2>What is a Content Management System?</h2>\n<p>A content management system (CMS) is a program used to create a framework for the content of a Web site. CMSes are deployed primarily for interactive use by a potentially large number of contributors. For example, the software for the website Wikipedia is based on a wiki, which is a particular type of content management system. As used in this article, Content Management means Web Content Management. Other related forms of content management are listed below.<br /><br />The content managed includes computer files, image media, audio files, electronic documents and web content. The idea behind a CMS is to make these files available inter-office, as well as over the web. A CMS would most often be used as an archive as well. Many companies use a CMS to store files in a non-proprietary form. Companies use a CMS to share files with ease, as most systems use server-based software, even further broadening file availability. As shown below, many CMSs include a feature for Web Content, and some have a feature for a "workflow process".<br /><br />"Workflow" is the idea of moving an electronic document along for either approval, or for adding content. Some CMSs will easily facilitate this process with email notification, and automated routing. This is ideally a collaborative creation of documents. A CMS facilitates the organization, control, and publication of a large body of documents and other content, such as images and multimedia resources.<br /><br />A Web content management system is a CMS with additional features to ease the tasks required to publish web content to web sites.</p>'),
(5, 1, 'about', 'About', 'About Us', 'about, company', 'About our rocking company ...', 'This is a page all about our rocking company!');
