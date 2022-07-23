
CREATE TABLE `tenants` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
  `active` int(1) unsigned NULL,
  `created` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_tenant` int(11) NOT NULL,
  `name` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
  `created` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `migration` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
  `created` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP
);