CREATE TABLE `regusers_notifiche` (
	`id_regusers_notifiche` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`id_user` int NOT NULL DEFAULT '0',
	`id_page` int NOT NULL DEFAULT '0',
	`id_doc` int NOT NULL DEFAULT '0',
	`tipo` char(20) NOT NULL DEFAULT 'DOCUMENTO'
) ENGINE=InnoDB;