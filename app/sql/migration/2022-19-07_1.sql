
-- creating settings table for store general app data

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
  `value` varchar(255) COLLATE 'utf8_general_ci' DEFAULT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL
);