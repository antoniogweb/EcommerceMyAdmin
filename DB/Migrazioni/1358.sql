CREATE TABLE `adminsessions_two` (
	`id_adminsession_two` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	uid_two char(32) not null default '',
	time_creazione int not null default 0,
	time_per_scadenza int not null default 0,
	user_agent_md5 char(32) not null default '',
	user_agent text not null,
	codice_verifica char(10) not null default '',
	tentativi_verifica tinyint not null default 0,
	attivo tinyint not null default 0
) ENGINE=InnoDB;