CREATE TABLE `ai_richieste_contesti` (
	`id_ai_richiesta_contesto` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`id_ai_richiesta` int(10) UNSIGNED NOT NULL DEFAULT '0',
	`id_page` int(10) UNSIGNED NOT NULL DEFAULT '0',
	`id_order` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
