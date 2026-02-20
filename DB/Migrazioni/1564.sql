CREATE TABLE `ai_richieste_cache` (
	`id_ai_richiesta_cache` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	time_creazione int not null default 0,
	messaggio char(32) not null default '',
	contesto char(32) not null default '',
	istruzioni char(32) not null default '',
	id_modello int not null default 0,
	output text not null
) ENGINE=InnoDB;
