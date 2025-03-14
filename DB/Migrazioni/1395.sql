CREATE TABLE `calendario_chiusure` (
	`id_calendario` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	data_chiusura date null default null
) ENGINE=InnoDB;