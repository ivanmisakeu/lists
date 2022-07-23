
-- creating col for columns to items will be not deleted, jus marked as deleted

ALTER TABLE `items` ADD `deleted` tinyint(1) DEFAULT 0 AFTER `name`;