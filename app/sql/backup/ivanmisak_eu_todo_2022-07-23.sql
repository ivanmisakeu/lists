
-- Database backup of List app by Ivan Misak (info@ivanmisak.eu)
-- Created: 23.07.2022 20:14:42
-- Dump script: /scripts/DbBackup.php
-- MySQL version: 5.5.59
-- Database name: ivanmisak_eu_todo

-- Dump for table 'items'

CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tenant` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `created` datetime NOT NULL,
  `deleted_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=63

INSERT INTO `items` (`id`, `id_tenant`, `name`, `deleted`, `created`, `deleted_date`) VALUES 
('17', '3', 'Ponožky ocko', '0', '2022-07-19 18:27:20', NULL),
('18', '3', 'boxerky Michal', '0', '2022-07-19 18:27:20', NULL),
('19', '3', 'tabletky', '0', '2022-07-19 18:27:20', NULL),
('34', '4', 'admin rozhranie', '0', '2022-07-21 15:56:53', NULL),
('35', '4', 'moznost pridat title pri vytvarani noveho tenanta', '0', '2022-07-21 15:57:09', NULL),
('36', '4', 'item - date deleted', '1', '2022-07-21 15:57:27', '2022-07-23 11:19:47'),
('37', '4', 'admin - prehlad logov', '0', '2022-07-21 15:57:47', NULL),
('38', '4', 'admin - zoznam tenantov a editacia', '0', '2022-07-21 15:57:56', NULL),
('39', '4', 'admin - zoznam poloziek u tenanta a editacia', '0', '2022-07-21 15:58:07', NULL),
('40', '4', 'admin - autorizacia', '0', '2022-07-21 15:58:18', NULL),
('41', '4', 'update skript pre odstranene items', '0', '2022-07-21 15:59:13', NULL),
('48', '5', 'ivan je tu.', '0', '2022-07-22 12:17:28', NULL),
('49', '4', 'moznost zmenit nazov zoznamu', '1', '2022-07-22 14:36:54', '2022-07-23 12:14:41'),
('50', '1', 'vlozky do topanok (43)', '1', '2022-07-22 15:26:12', '2022-07-23 14:07:43'),
('54', '4', 'db backup', '0', '2022-07-23 10:37:08', NULL),
('55', '1', 'flasa 0', '1', '2022-07-23 12:37:30', '2022-07-23 12:37:37'),
('56', '1', '5l', '1', '2022-07-23 12:37:30', '2022-07-23 12:37:35'),
('57', '1', 'flasa 0.5l', '1', '2022-07-23 12:38:04', '2022-07-23 14:02:46'),
('58', '1', 'bref gel nadobka', '0', '2022-07-23 12:59:27', NULL),
('59', '4', 'do not explode number by comma', '0', '2022-07-23 13:00:14', NULL),
('60', '4', 'close modal on ESC', '0', '2022-07-23 13:09:11', NULL),
('61', '4', 'maintenance mode', '0', '2022-07-23 18:16:30', NULL),
('62', '4', 'find translation script - clean escape options', '0', '2022-07-23 19:39:14', NULL);

-- Dump for table 'migration'

CREATE TABLE `migration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=10

INSERT INTO `migration` (`id`, `name`, `created`) VALUES 
('1', '--MIGRATION SCRIPT CHECK--', '2022-07-16 23:11:34'),
('2', '2022-16-07_0.sql', '2022-07-16 23:11:34'),
('3', '2022-19-07_0.sql', '2022-07-19 07:58:30'),
('4', '2022-19-07_1.sql', '2022-07-19 07:58:30'),
('5', '2022-19-07_2.sql', '2022-07-19 14:04:58'),
('6', '2022-21-07_0.sql', '2022-07-21 15:48:45'),
('7', '2022-23-07_0.sql', '2022-07-23 11:19:18'),
('8', '2022-23-07_1.sql', '2022-07-23 11:19:18'),
('9', '2022-23-07_2.sql', '2022-07-23 11:19:18');

-- Dump for table 'settings'

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=4

INSERT INTO `settings` (`id`, `name`, `value`, `updated`, `created`) VALUES 
('2', 'APP_VERSION', '1.7', '2022-07-23 11:19:21', '2022-07-21 15:48:45'),
('3', 'DB_BACKUP', '2022-07-23 20:12:49', '2022-07-23 20:12:49', '2022-07-23 20:11:31');

-- Dump for table 'tenants'

CREATE TABLE `tenants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(60) DEFAULT NULL,
  `active` int(1) unsigned DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=6

INSERT INTO `tenants` (`id`, `name`, `title`, `active`, `created`) VALUES 
('1', 'ivan', 'Nákupy', '1', '2022-07-15 13:41:08'),
('2', 'pn-michal', NULL, '1', '2022-07-17 15:26:56'),
('3', 'eva', 'Nákupy', '1', '2022-07-17 16:27:16'),
('4', 'application-todo', 'Application todo', '1', '2022-07-21 15:49:52'),
('5', 'milan', 'Poznámky', '1', '2022-07-22 12:12:39');
