
-- creating users table

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
  `mail` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
  `password` varchar(128) NOT NULL,
  `admin` int(1) DEFAULT 0,
  `token` varchar(128) DEFAULT NULL,
  `deleted` int(1) DEFAULT 0,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL
);