
-- hmm, better rename the column..

ALTER TABLE `items` CHANGE `date_deleted` `deleted_date` datetime NULL AFTER `created`;