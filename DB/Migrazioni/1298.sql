CREATE TABLE `ai_richieste` (
	`id_ai_richiesta` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`id_c` int(10) UNSIGNED NOT NULL DEFAULT '0',
	`id_marchio` int(10) UNSIGNED NOT NULL DEFAULT '0',
	`id_page` int(10) UNSIGNED NOT NULL DEFAULT '0',
	`id_order` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;