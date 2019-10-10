-- ezCMS SQL Dump (FOR UPGRADE FROM SITE BUILDER)
ALTER TABLE `pages` 
ADD `priority` INT(3) NOT NULL DEFAULT '50' 
  COMMENT 'search priority' AFTER `nositemap`, 
ADD `img` VARCHAR(512) NULL DEFAULT NULL 
  COMMENT 'featured image' AFTER `priority`, 
ADD INDEX `proprity` (`priority`);

ALTER TABLE `git_pages` 
ADD `priority` INT(3) NOT NULL DEFAULT '50' 
  COMMENT 'search priority' AFTER `nositemap`, 
ADD `img` VARCHAR(512) NULL DEFAULT NULL 
  COMMENT 'featured image' AFTER `priority`;