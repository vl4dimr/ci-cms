--- DROP TABLE IF EXISTS `ci_blog_comments`;
CREATE TABLE `ci_blog_comments` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL,
  `status` enum('pending','approved') NOT NULL,
  `date_posted` int(11) NOT NULL,
  `author` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `website` varchar(150) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `post_id` (`post_id`),
  KEY `date_posted` (`date_posted`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ci_blog_comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `ci_blog_posts`
--

--- DROP TABLE IF EXISTS `ci_blog_posts`;
CREATE TABLE `ci_blog_posts` (
  `id` int(11) NOT NULL auto_increment,
  `uri` varchar(100) NOT NULL,
  `status` enum('draft','private','published') NOT NULL,
  `allow_comments` tinyint(1) NOT NULL,
  `allow_pings` tinyint(1) NOT NULL,
  `date_posted` int(11) NOT NULL,
  `comments` int(4) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `title` varchar(150) NOT NULL,
  `body` text NOT NULL,
  `ext_body` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uri` (`uri`),
  KEY `date_posted` (`date_posted`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ci_blog_posts`
--

INSERT INTO `ci_blog_posts` (`id`, `uri`, `status`, `allow_comments`, `allow_pings`, `date_posted`, `comments`, `meta_keywords`, `meta_description`, `title`, `body`, `ext_body`) VALUES
(4, 'another-blaze-install-happens', 'published', 1, 1, 1206424831, 0, '', '', 'Another Blaze Install Happens', '<p>This site is being powered by Blaze, an open-source content management system.</p>\n<p>This post has been entered in the ''Blog Admin'' section of the site, and can be deleted.</p>', '');

-- --------------------------------------------------------

--
-- Table structure for table `ci_blog_posts_tags`
--

--- DROP TABLE IF EXISTS `ci_blog_posts_tags`;
CREATE TABLE `ci_blog_posts_tags` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `post_id` (`post_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `ci_blog_posts_tags`
--

INSERT INTO `ci_blog_posts_tags` (`id`, `post_id`, `tag_id`) VALUES
(25, 4, 11),
(24, 4, 10),
(23, 4, 9);

-- --------------------------------------------------------

--
-- Table structure for table `ci_blog_tags`
--

--- DROP TABLE IF EXISTS `ci_blog_tags`;
CREATE TABLE `ci_blog_tags` (
  `id` int(11) NOT NULL auto_increment,
  `tag` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `ci_blog_tags`
--

INSERT INTO `ci_blog_tags` (`id`, `tag`) VALUES
(1, 'codeigniter'),
(2, 'php'),
(3, 'mysql'),
(4, 'development'),
(5, 'internet'),
(6, 'internets'),
(7, 'boobs'),
(8, 'rockstars'),
(9, 'blaze'),
(10, 'install'),
(11, 'powered');

-- --------------------------------------------------------

--
-- Table structure for table `ci_blog_trackbacks`
--

--- DROP TABLE IF EXISTS `ci_blog_trackbacks`;
CREATE TABLE `ci_blog_trackbacks` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL,
  `url` varchar(200) NOT NULL,
  `title` varchar(100) NOT NULL,
  `exerpt` text NOT NULL,
  `date_posted` int(11) NOT NULL,
  `ip_address` varchar(16) NOT NULL,
  `status` enum('approved','pending') NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `post_id` (`post_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ci_blog_trackbacks`
--