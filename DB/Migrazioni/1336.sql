CREATE TABLE `sorgenti` (
	`id_sorgente` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	cart_uid varchar(255) not null default '',
	sorgente varchar(255) not null default '',
	time_creazione int not null default 0
) ENGINE=InnoDB;
