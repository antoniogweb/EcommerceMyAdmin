CREATE TABLE `ai_richieste_messaggi` (
	`id_ai_richiesta_messaggio` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	messaggio text not null,
	`id_ai_richiesta` int UNSIGNED NOT NULL DEFAULT '0',
	`id_admin` int UNSIGNED NOT NULL DEFAULT '0',
	`id_order` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
