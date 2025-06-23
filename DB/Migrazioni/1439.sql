CREATE TABLE `regsessions_two` (
	`id_regsessions_two` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`uid_two` char(32) NOT NULL DEFAULT '',
	`time_creazione` int NOT NULL DEFAULT '0',
	`time_per_scadenza` int NOT NULL DEFAULT '0',
	`user_agent_md5` char(32) NOT NULL DEFAULT '',
	`user_agent` text NOT NULL,
	`codice_verifica` char(10) NOT NULL DEFAULT '',
	`tentativi_verifica` tinyint NOT NULL DEFAULT '0',
	`attivo` tinyint NOT NULL DEFAULT '0',
	`id_user` int NOT NULL DEFAULT '0',
	`uid` char(32) NOT NULL DEFAULT '',
	`ip` char(20) NOT NULL DEFAULT '',
	`numero_invii_codice` tinyint NOT NULL DEFAULT '0',
	`sistema_operativo` varchar(50) NOT NULL DEFAULT '',
	`browser` varchar(50) NOT NULL DEFAULT '',
	`versione_browser` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB;