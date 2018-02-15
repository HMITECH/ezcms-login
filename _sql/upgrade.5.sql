-- ezCMS SQL Dump (FOR UPGRADE FROM SITE BUILDER)
-- version 2.0.010413
-- http://www.hmi-tech.net
--
-- Desc: This sql file will update the database to ezCMS 5
-- Author: Mohd Ahmed (mo.ahmed@hmi-tech.net)
--
-- Host: localhost
-- Generation Time: Apr 05, 2013 at 11:01 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ezsite_db`
--

-- --------------------------------------------------------

DROP TABLE 	`phptraffica_conf`, 
			`phptraffica_conf_ipban`, 
			`phptraffica_conf_sites`, 
			`traffic__acces`, 
			`traffic__browser`, 
			`traffic__country`, 
			`traffic__day`, 
			`traffic__host`, 
			`traffic__hour`, 
			`traffic__iplist`, 
			`traffic__keyword`, 
			`traffic__os`, 
			`traffic__pages`, 
			`traffic__path`, 
			`traffic__referrer`, 
			`traffic__resolution`, 
			`traffic__retention`, 
			`traffic__uniq`;

DROP TABLE `phpTrafficA_conf`, `phpTrafficA_conf_ipban`, `phpTrafficA_conf_sites`;


ALTER TABLE `pages`
  DROP `showinmenu`,
  DROP `isredirected`,
  DROP `redirect`,
  DROP `cont`;

ALTER TABLE `users`
  DROP `viewstats`;
  
ALTER TABLE `site` 
	DROP `title` ,
	DROP `keywords` ,
	DROP `description` ,
	DROP `appendtitle` ,
	DROP `appendkey` ,
	DROP `appenddesc` ;


-- CREATE GIT Tables
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


-- END CREATE GIT Tables
ALTER TABLE `git_pages` ADD `revmsg` TEXT NULL DEFAULT NULL COMMENT 'Revision Message' AFTER `nositemap`;
ALTER TABLE `git_pages` ADD `notes`  TEXT NULL DEFAULT NULL COMMENT 'Notes' AFTER `revmsg`;
ALTER TABLE `git_files` ADD `revmsg` TEXT NULL DEFAULT NULL COMMENT 'Revision Message' AFTER `fullpath`;
-- Do AVOVE ONLY IF GIT TABLES ARE PRESENT

ALTER TABLE `site` ADD `revmsg` TEXT NULL DEFAULT NULL COMMENT 'Revision Message' AFTER `sidercontent`;



ALTER TABLE `users` 
ADD `editor` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'Editor to use in the cms',
ADD `createdon` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on';

ALTER TABLE `users` CHANGE `username` `username` VARCHAR(512) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'name of user';
ALTER TABLE `users` CHANGE `email` `email` VARCHAR(512) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'email address of user';
ALTER TABLE `users` CHANGE `passwd` `passwd` VARCHAR( 512 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'password has for the user';
		ALTER TABLE `users` ADD INDEX ( `email` , `passwd` ) ;
ALTER TABLE `users` ADD UNIQUE(`email`);

ALTER TABLE `users` 
	ADD `editor` TINYINT(1) NOT NULL DEFAULT '3' COMMENT 'cms editor type' AFTER `editjs`, 
	ADD `cmtheme` VARCHAR(32) NOT NULL DEFAULT 'default' COMMENT 'code mirror theme' AFTER `editor`, 
	ADD `cmscolor` VARCHAR(8) NOT NULL DEFAULT '#FFFFFF' COMMENT 'cms background color' AFTER `cmtheme`;


UPDATE `users` SET `passwd` = SHA2 (`passwd`, 512);

ALTER TABLE `pages` CHANGE `pagename` `pagename` VARCHAR(512) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'name of page';
ALTER TABLE `pages` CHANGE `title` `title` VARCHAR(1024) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'title of page';
ALTER TABLE `pages` CHANGE `keywords` `keywords` VARCHAR(1024) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'keywords for page';
ALTER TABLE `pages` CHANGE `description` `description` VARCHAR(1024) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'decription of page';
ALTER TABLE `pages` CHANGE `url` `url` VARCHAR(2048) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'the seo friendly url';
		ALTER TABLE `pages` ADD UNIQUE(`url`);
		ALTER TABLE `pages` ADD INDEX(`createdby`);
ALTER TABLE `pages` ADD INDEX(`place`);

ALTER TABLE `pages` ADD `notes` text NOT NULL COMMENT 'contents of internal notes' after `head`;

# ALTER TABLE `git_pages` ADD `notes` text NOT NULL COMMENT 'contents of internal notes' after `head`;
#

ALTER TABLE `pages` ADD `nositemap` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'true to skip in sitemap';
ALTER TABLE `pages` ADD `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'id of the user made this page';
ALTER TABLE `pages` ADD `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'creation date and time';

ALTER TABLE `site` ADD `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'id of the user made this rev';
ALTER TABLE `site` ADD `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'creation date and time';