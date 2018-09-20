#  ezcms.5.1-shop.sql
# drop database if exists ezcms;
# create database ezcms;
# use ezcms;

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'id of admin',
  `name` varchar(255) NOT NULL COMMENT 'full name of admin',
  `email` varchar(255) NOT NULL COMMENT 'email of admin',
  `phone` varchar(128) DEFAULT NULL COMMENT 'phone number',
  `passwd` varchar(512) NOT NULL COMMENT 'password hash',
  `active` int(1) NOT NULL DEFAULT '0' COMMENT 'able to login',
  `super` int(1) NOT NULL DEFAULT '0' COMMENT 'is super admin',
  `lastlogin` datetime DEFAULT NULL COMMENT 'last login date and time',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `login` (`email`,`passwd`),
  KEY `createdon` (`createdon`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='the admins';

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`name`, `email`, `phone`, `passwd`, `active`, `super`) VALUES
('Mo Ahmed', 'mo.ahmed@hmi-tech.net', NULL, 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', 1, 1);



CREATE TABLE `categories` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) NOT NULL COMMENT 'full name',
  `parent_id` int(8) NOT NULL COMMENT 'id of parent category',
  `keywords` varchar(1024) DEFAULT NULL COMMENT 'keywords for page',
  `description` varchar(1024) DEFAULT NULL COMMENT 'description of page',
  `maincontent` longtext COMMENT 'main content of page',
  `url` varchar(900) NOT NULL COMMENT 'the seo friendly url',
  `disabled` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'enabled or disable',
  `notes` text NOT NULL COMMENT 'contents of internal notes',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on date and time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='the categories';

CREATE TABLE `brands` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) NOT NULL COMMENT 'full name',
  `publicname` varchar(1024) NOT NULL COMMENT 'full name',
  `code` varchar(16) NOT NULL COMMENT 'brand code',
  `page_id` int(8) NOT NULL COMMENT 'id of linked page',
  `url` varchar(900) NOT NULL COMMENT 'the seo friendly url',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on date and time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='the categories';

CREATE TABLE `products` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) NOT NULL COMMENT 'full name',
  `brand_id` int(8) NOT NULL COMMENT 'id of brand',
  `keywords` varchar(1024) DEFAULT NULL COMMENT 'keywords for page',
  `description` varchar(1024) DEFAULT NULL COMMENT 'description of page',
  `maincontent` longtext COMMENT 'main content of page',
  `url` varchar(900) NOT NULL COMMENT 'the seo friendly url',
  `disabled` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'enabled or disable',
  `notes` text NOT NULL COMMENT 'contents of internal notes',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on date and time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='the products';


CREATE TABLE `attributes` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) NOT NULL COMMENT 'full name',
  `notes` text NOT NULL COMMENT 'contents of internal notes',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on date and time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='the product attributes';

CREATE TABLE `attributesdetails` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) NOT NULL COMMENT 'full name',
  `attrib_id` int(8) NOT NULL COMMENT 'id of order',
  `notes` text NOT NULL COMMENT 'contents of internal notes',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on date and time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='the orders details';

CREATE TABLE `customers` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) NOT NULL COMMENT 'full name',
  `email` varchar(512) NOT NULL COMMENT 'email address of user',
  `passwd` varchar(512) NOT NULL COMMENT 'password hash for the user',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'able to login',
  `trade` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'able for trade clients',
  `notes` text NOT NULL COMMENT 'contents of internal notes',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on date and time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='the customers';

CREATE TABLE `orders` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `numb` varchar(1024) NOT NULL COMMENT 'sales order number',
  `customer_id` int(8) NOT NULL COMMENT 'id of customer',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'ordered, processing, complete, cancelled',
  `notes` text NOT NULL COMMENT 'contents of internal notes',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on date and time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='the orders';

CREATE TABLE `ordersdetails` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) NOT NULL COMMENT 'full name',
  `order_id` int(8) NOT NULL COMMENT 'id of order',
  `product_id` int(8) NOT NULL COMMENT 'id of product',
  `qty` int(8) NOT NULL COMMENT 'quantity',
  `notes` text NOT NULL COMMENT 'contents of internal notes',
  `createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created on date and time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='the orders details';