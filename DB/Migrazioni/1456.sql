CREATE TABLE `log_tecnici` (
	`id_log_tecnico` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`tipo` char(20) not null default '',
	`descrizione` text not null,
	`da_notificare_via_mail` tinyint NOT NULL DEFAULT '0',
	`notificato` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB;