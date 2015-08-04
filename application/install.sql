CREATE TABLE `stecmail` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `from` varchar(100) COLLATE 'utf8_general_ci' NOT NULL,
  `email` varchar(100) COLLATE 'utf8_general_ci' NOT NULL,
  `subject` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
  `body` text COLLATE 'utf8_general_ci' NOT NULL,
  `priority` int NOT NULL,
  `status` int NOT NULL,
  `from_date` DATETIME NULL
) COMMENT='';
