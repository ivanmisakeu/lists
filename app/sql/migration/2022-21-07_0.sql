
-- add custom title for tenant

ALTER TABLE `tenants` ADD `title` varchar(60) DEFAULT NULL AFTER `name`;