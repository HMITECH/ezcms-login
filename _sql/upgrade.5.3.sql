-- ezCMS SQL Dump (FOR UPGRADE FROM SITE BUILDER)
-- http://www.hmi-tech.net
--
-- Desc: This sql file will update the database to ezCMS 5.3
-- Author: Mohd Ahmed (mo.ahmed@hmi-tech.net)
--
-- Host: localhost
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ezsite_db`
--

-- --------------------------------------------------------

ALTER TABLE `pages` ADD `hidechildpages` INT(1) NOT NULL DEFAULT '0' COMMENT 'Hide Child Pages in ezCMS' AFTER `nositemap`;

ALTER TABLE `git_pages` ADD `hidechildpages` INT(1) NOT NULL DEFAULT '0' COMMENT 'Hide Child Pages in ezCMS' AFTER `nositemap`;


-- CREATE redirects Tables
CREATE TABLE IF NOT EXISTS `redirects` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'id of redirect',
  `srcurl` varchar(700) NOT NULL COMMENT 'source url', 
  `desurl` varchar(700) NOT NULL COMMENT 'destination url', 
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'enabled or disabled',
  `actioncount` int(0) NOT NULL DEFAULT '0' COMMENT 'count redirect action taken',
  `createdby` int(16) NOT NULL DEFAULT '1' COMMENT 'id of the user made this redirect',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on date and time',
  PRIMARY KEY (`id`),
  KEY `srcurl` (`srcurl`),
  KEY `enabled` (`enabled`),
  KEY `createdon` (`createdon`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='redirectekod URLs';

-- CREATE 404 Tables
CREATE TABLE IF NOT EXISTS `log404` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'id of 404 error',
  `url` varchar(700) NOT NULL COMMENT '404 url',
  `refer` varchar(1024) NOT NULL COMMENT 'http referer url',
  `ip` varchar(15) NOT NULL COMMENT 'ip address',
  `useragent` TEXT NULL DEFAULT NULL COMMENT 'user agent',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on date and time',
  PRIMARY KEY (`id`),
  KEY `url` (`url`),
  KEY `ip` (`ip`),
  KEY `createdon` (`createdon`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='404 error log';