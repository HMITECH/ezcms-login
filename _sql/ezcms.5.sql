#  ezcms.5.sql
# drop database if exists ezcms;
# create database ezcms;
# use ezcms;

DROP TABLE IF EXISTS git_files;

CREATE TABLE IF NOT EXISTS `git_files` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'id of file revision',
  `content` longtext COMMENT 'contents of the file',
  `fullpath` varchar(700) NOT NULL COMMENT 'name of file',
  `revmsg` TEXT NULL DEFAULT NULL COMMENT 'revision message',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'id of the user made this revision',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'revision date and time',
  PRIMARY KEY (`id`),
  KEY `createdby` (`createdby`),  
  KEY `fullpath` (`fullpath`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='revision log of files';


DROP TABLE IF EXISTS git_pages;
CREATE TABLE IF NOT EXISTS `git_pages` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'revision id of page',
  `page_id` int(16) NOT NULL COMMENT 'id of original page',
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
  `sidecontent` longtext COMMENT 'aside content of page',
  `usesider` tinyint(1) DEFAULT '0' COMMENT 'true to use aside else defaults',    
  `sidercontent` longtext COMMENT 'right aside content',
  `published` tinyint(1) DEFAULT NULL COMMENT 'true if published on site',
  `parentid` int(16) DEFAULT NULL COMMENT 'id of parent page',
  `place` int(8) NOT NULL DEFAULT '0' COMMENT 'position of the page',
  `url` varchar(900) NOT NULL COMMENT 'the seo friendly url',
  `head` text NOT NULL COMMENT 'contents of custom head',
  `notes` text NOT NULL COMMENT 'contents of internal notes',
  `layout` text COMMENT 'name of the layout file to use with this page',
  `revmsg` TEXT NULL DEFAULT NULL COMMENT 'revision message',
  `nositemap` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'true to skip in sitemap',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'id of the user made this revision',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'revision date and time',
  PRIMARY KEY (`id`),
  KEY `createdby` (`createdby`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='revision of web pages in the site';

DROP TABLE IF EXISTS pages;
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
  `head` text NOT NULL COMMENT 'contents of custom head',
  `notes` text NOT NULL COMMENT 'contents of internal notes',
  `layout` text COMMENT 'name of the layout file for this page',
  `nositemap` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'true to skip in sitemap',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'id of the user made this page',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'creation date and time',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `published` (`published`),
  KEY `createdby` (`createdby`),
  KEY `place` (`place`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='the web pages';

INSERT INTO `pages` (`id`, `pagename`, `title`, `keywords`, `description`, `maincontent`, `useheader`, `headercontent`, `usefooter`, `footercontent`, `useside`, `sidecontent`, `published`, `parentid`, `place`, `url`, `sidercontent`, `usesider`, `head`, `layout`, `nositemap`, `createdby`) VALUES
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
	'layout.php', 0, 1),
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
	'layout.full-width.php', 0, 1),
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
	'layout.right-aside.php', 0, 1),
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
	'layout.left-aside.php', 0, 1);

DROP TABLE IF EXISTS site;
CREATE TABLE IF NOT EXISTS `site` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'id of site settings',
  `headercontent` longtext COMMENT 'header content of page',
  `footercontent` longtext COMMENT 'footer content of page',
  `sidecontent` longtext COMMENT 'aside content of page',
  `sidercontent` longtext COMMENT 'right aside content',
  `revmsg` TEXT NULL DEFAULT NULL COMMENT 'revision Message',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'id of the user made this',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'revision date and time',
  PRIMARY KEY (`id`),
  KEY `createdby` (`createdby`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='the default blocks used in the site';

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

DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'id of user',
  `username` varchar(512) NOT NULL COMMENT 'name of user',
  `email` varchar(512) NOT NULL COMMENT 'email address of user',
  `passwd` varchar(512) NOT NULL COMMENT 'password has for the user',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'able to login',
  `editpage` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit page',
  `delpage` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'delete page',
  `edituser` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'can administer other users',
  `deluser` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'delete users',
  `editsettings` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit settings',
  `editcont` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit controller',
  `editlayout` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit layout',
  `editcss` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit css',
  `editjs` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'edit js',
  `editor` TINYINT(1) NOT NULL DEFAULT '3' COMMENT 'cms editor type', 
  `cmtheme` VARCHAR(32) NOT NULL DEFAULT 'default' COMMENT 'code mirror theme', 
  `cmscolor` VARCHAR(8) NOT NULL DEFAULT '#FFFFFF' COMMENT 'cms background color',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'revision date and time',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `passwd` (`passwd`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='the admins';


INSERT INTO `users` (`id`, `username`, `email`, `passwd`, `active`, `editpage`, `delpage`, `edituser`, `deluser`, `editsettings`, `editcont`, `editlayout`, `editcss`, `editjs`) VALUES
(1, 'admin', 'admin@localhost', 'ezcms', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
