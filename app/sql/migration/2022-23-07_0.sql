
-- date when item has been deleted

ALTER TABLE `items` ADD `date_deleted` datetime DEFAULT NULL AFTER `created`;