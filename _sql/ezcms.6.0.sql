-- ezCMS SQL Dump
-- version 6.0
-- http://www.hmi-tech.net
--
-- Generation Time: Sep 06, 2021 at 05:28 AM
-- PHP Version: 7.4
-- drop database if exists ezcms_db;
-- create database ezcms_db;
-- use ezcms_db;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ezsite_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `git_files`
--

DROP TABLE IF EXISTS `git_files`;
CREATE TABLE IF NOT EXISTS `git_files` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'id of revision',
  `content` longtext COMMENT 'contents of the file',
  `fullpath` varchar(1000) NOT NULL COMMENT 'Full Path and Name of file',
  `revmsg` text COMMENT 'Revision Message',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'Id of the user who created this Revision',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Revision date and time',
  PRIMARY KEY (`id`),
  KEY `fullpath` (`fullpath`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='revision log of files';

-- --------------------------------------------------------

--
-- Table structure for table `git_pages`
--

DROP TABLE IF EXISTS `git_pages`;
CREATE TABLE IF NOT EXISTS `git_pages` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'Revision id of page',
  `page_id` int(16) NOT NULL COMMENT 'id of original page',
  `pagename` varchar(512) NOT NULL COMMENT 'name of page',
  `title` varchar(1024) NOT NULL COMMENT 'title of page',
  `keywords` varchar(1024) DEFAULT NULL COMMENT 'keywords for page',
  `description` varchar(1024) DEFAULT NULL COMMENT 'decription of page',
  `maincontent` longtext COMMENT 'main content of page',
  `useheader` tinyint(1) DEFAULT NULL COMMENT 'true to use header else defaults',
  `headercontent` longtext COMMENT 'header content of page',
  `usefooter` tinyint(1) DEFAULT NULL COMMENT 'true to use footer else defaults',
  `footercontent` longtext COMMENT 'footer content of page',
  `useside` tinyint(1) DEFAULT NULL COMMENT 'true to use side bar else defaults',
  `sidecontent` longtext COMMENT 'side content of page',
  `published` tinyint(1) DEFAULT NULL COMMENT 'true if pulished on site',
  `parentid` int(16) DEFAULT NULL COMMENT 'id of parent page',
  `place` int(8) NOT NULL DEFAULT '0' COMMENT 'position of the page',
  `url` text COMMENT 'the seo friendly url',
  `sidercontent` longtext COMMENT 'right side-bar content',
  `usesider` tinyint(1) DEFAULT '0' COMMENT 'append keyword',
  `head` text NOT NULL COMMENT 'contents of custom head',
  `notes` text NOT NULL COMMENT 'contents of internal notes',
  `layout` text COMMENT 'name of the layout file to use with this page',
  `nositemap` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True to skip in sitemap',
  `priority` int(3) NOT NULL DEFAULT '50' COMMENT 'search priority',
  `img` varchar(512) DEFAULT NULL COMMENT 'featured image',
  `hidechildpages` int(1) NOT NULL DEFAULT '0' COMMENT 'Hide Child Pages in ezCMS',
  `revmsg` text COMMENT 'Revision Message',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'Id of the user who created this Revision',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Revision date and time',
  PRIMARY KEY (`id`),
  KEY `createdby` (`createdby`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Revision of web pages in the site';

-- --------------------------------------------------------

--
-- Table structure for table `log404`
--

DROP TABLE IF EXISTS `log404`;
CREATE TABLE IF NOT EXISTS `log404` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'id of 404 error',
  `url` varchar(700) NOT NULL COMMENT '404 url',
  `refer` varchar(1024) NOT NULL COMMENT 'http referer url',
  `ip` varchar(15) NOT NULL COMMENT 'ip address',
  `useragent` text COMMENT 'user agent',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on date and time',
  PRIMARY KEY (`id`),
  KEY `url` (`url`),
  KEY `ip` (`ip`),
  KEY `createdon` (`createdon`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='404 error log';

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'id of page',
  `pagename` varchar(512) NOT NULL COMMENT 'name of page',
  `title` varchar(1024) NOT NULL COMMENT 'title of page',
  `keywords` varchar(1024) DEFAULT NULL COMMENT 'keywords for page',
  `description` varchar(1024) DEFAULT NULL COMMENT 'description of page',
  `maincontent` longtext COMMENT 'main content of page',
  `useheader` tinyint(1) DEFAULT NULL COMMENT 'true to use header else defaults',
  `headercontent` longtext COMMENT 'header content of page',
  `usefooter` tinyint(1) DEFAULT NULL COMMENT 'true to use footer else defaults',
  `footercontent` longtext COMMENT 'footer content of page',
  `useside` tinyint(1) DEFAULT NULL COMMENT 'true to use aside else defaults',
  `sidecontent` longtext COMMENT 'side content of page',
  `published` tinyint(1) DEFAULT NULL COMMENT 'true if pulished on site',
  `parentid` int(16) DEFAULT NULL COMMENT 'id of parent page',
  `place` int(8) NOT NULL DEFAULT '0' COMMENT 'position of the page',
  `url` varchar(900) NOT NULL COMMENT 'the seo friendly url',
  `sidercontent` longtext COMMENT 'right aside content',
  `usesider` tinyint(1) DEFAULT '0' COMMENT 'true to use aside else defaults',
  `head` longtext COMMENT 'contents of custom head',
  `notes` text NOT NULL COMMENT 'contents of internal notes',
  `layout` text COMMENT 'name of the layout file for this page',
  `nositemap` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'true to skip in sitemap',
  `priority` int(3) NOT NULL DEFAULT '50' COMMENT 'search priority',
  `img` varchar(512) DEFAULT NULL COMMENT 'featured image',
  `hidechildpages` int(1) NOT NULL DEFAULT '0' COMMENT 'Hide Child Pages in ezCMS',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'id of the user made this page',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'creation date and time',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `published` (`published`),
  KEY `createdby` (`createdby`),
  KEY `place` (`place`),
  KEY `parentid` (`parentid`),
  KEY `title` (`title`(1000)),
  KEY `keywords` (`keywords`(1000)),
  KEY `description` (`description`(1000)),
  KEY `url_2` (`url`),
  KEY `proprity` (`priority`),
  KEY `priority` (`priority`),
  KEY `nositemap` (`nositemap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='the web pages';

-- --------------------------------------------------------

--
-- Data for table `pages`
--

INSERT INTO `pages` (`id`, `pagename`, `title`, `keywords`, `description`, `maincontent`, `useheader`, `headercontent`, `usefooter`, `footercontent`, `useside`, `sidecontent`, `published`, `parentid`, `place`, `url`, `sidercontent`, `usesider`, `head`, `layout`, `nositemap`, `notes`, `createdby`) VALUES
(1,
	'home',
	'Home Page',
	'',
	'',
	'<!--  Content  -->
<h1>Welcome to ezCMS - Home page</h1>
<p>Edit this content from the ezCMS using the <strong>''Pages''</strong> Menu.</p>
<p><a target="_blank" href="login/pages.php#content">go to editor</a></p>', 0,
	'', 1,
	'<!--  Footer  -->
<p>CUSTOM HOMEPAGE FOOTER <a target="_blank" href="login/pages.php#footers">editor link</a></p>', 0,
	'', 1, 0, 1,
	'/',
	'', 0,
	'<!--  Head content  -->',
	'layout.php', 0, '', 1),
(2,
	'404 Page',
	'Page not found',
	'',
	'',
	'<!--  Content  -->
<h1>404 - Page not Found</h1>
<p>Edit this content from the ezCMS using the <strong>''Pages'' - ''404 Page''</strong> Menu.</p>
<p><a target="_blank" href="login/pages.php?id=2#content">go to editor</a></p>', 0,
	'', 0,
	'', 0,
	'', 1, 0, 1,
	'/.',
	'', 0,
	'',
	'layout.full-width.php', 0, '', 1),
(3,
	'contact',
	'Contact Page',
	'',
	'',
	'<!--  Content  -->
<h1>Contact page</h1>
<p><a target="_blank" href="login/pages.php?id=3#content">go to editor</a></p>', 0,
	'', 0,
	'', 0,
	'', 1, 1, 2,
	'/contact',
	'', 0,
	'',
	'layout.right-aside.php', 0, '', 1),
(4,
	'about',
	'About Page',
	'',
	'',
	'<!--  Content  -->
<h1>About page</h1>
<p><a target="_blank" href="login/pages.php?id=4#content">go to editor</a></p>', 0,
	'', 0,
	'', 0,
	'', 1, 1, 1,
	'/about',
	'', 0,
	'',
	'layout.left-aside.php', 0, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `redirects`
--

DROP TABLE IF EXISTS `redirects`;
CREATE TABLE IF NOT EXISTS `redirects` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'id of redirect',
  `srcurl` varchar(700) NOT NULL COMMENT 'source url',
  `desurl` varchar(700) NOT NULL COMMENT 'destination url',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'enabled or disabled',
  `actioncount` int(11) NOT NULL DEFAULT '0' COMMENT 'count redirect action taken',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'id of the user made this redirect',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on date and time',
  PRIMARY KEY (`id`),
  KEY `srcurl` (`srcurl`),
  KEY `enabled` (`enabled`),
  KEY `createdon` (`createdon`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='redirected URLs';

-- --------------------------------------------------------

--
-- Table structure for table `site`
--

DROP TABLE IF EXISTS `site`;
CREATE TABLE IF NOT EXISTS `site` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'id of site settings',
  `headercontent` longtext COMMENT 'header content of page',
  `footercontent` longtext COMMENT 'footer content of page',
  `sidecontent` longtext COMMENT 'side content of page',
  `sidercontent` longtext COMMENT 'right side-bar content',
  `revmsg` text COMMENT 'Revision Message',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'User who changed the settings',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Revision date and time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='the default settings used in the site';

-- --------------------------------------------------------

--
-- Data for table `site`
--

INSERT INTO `site` (`id`, `headercontent`, `footercontent`, `sidecontent`, `sidercontent`, `createdby`) VALUES
(1,
	'<!--  Header  -->
<h2>DEFAULT SITE HEADER <a target="_blank" href="login/setting.php#header">editor link</a></h2>
<nav><a href="./">HOME</a><a href="about">ABOUT</a><a href="contact">CONTACT</a><a href="404">404</a></nav>',
	'<!--  Footer  -->\n<p><em>DEFAULT SITE FOOTER</em> <a target="_blank" href="login/setting.php#footers">editor link</a></p>',
	'<!--  Aside 1  -->
<h4>DEFAULT SITE ASIDE 1 </h4>
<p>Edit this content from the ezCMS using the <strong>''Template'' - ''Default settings Menu''</strong>.<br>
<a target="_blank" href="login/setting.php#sidebar">go to editor</a></p>',
	'<!--  Aside 2  -->
<h4>DEFAULT SITE ASIDE 2</h4>
<p>Edit this content from the ezCMS using the <strong>''Template'' - ''Default settings Menu''</strong>.<br>
<a target="_blank" href="login/setting.php#siderbar">go to editor</a></p>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'id of user',
  `username` varchar(512) NOT NULL COMMENT 'name of user',
  `email` varchar(512) NOT NULL COMMENT 'email address of user',
  `passwd` varchar(512) NOT NULL COMMENT 'password has for the user',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'able to login',
  `editpage` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit page',
  `delpage` tinyint(1) NOT NULL DEFAULT '0',
  `viewstats` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'can view site statistics',
  `edituser` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'can administer other users',
  `deluser` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'delete users',
  `editsettings` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit settings',
  `editcont` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit controller',
  `editlayout` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit layout',
  `editcss` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit css',
  `editjs` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit js',
  `editor` tinyint(1) NOT NULL DEFAULT '3' COMMENT 'cms editor type',
  `cmtheme` varchar(32) NOT NULL DEFAULT 'default' COMMENT 'code mirror theme',
  `cmscolor` varchar(8) NOT NULL DEFAULT '#FFFFFF' COMMENT 'cms background color',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Revision date and time',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `passwd` (`passwd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='the users of this site';

-- --------------------------------------------------------

--
-- Data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `passwd`, `active`, `editpage`, `delpage`, `edituser`, `deluser`, `editsettings`, `editcont`, `editlayout`, `editcss`, `editjs`) VALUES
(1, 'admin', 'admin@localhost', 'ezcms', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------
