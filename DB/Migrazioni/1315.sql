CREATE TABLE `traduzioni_correzioni` (
	`id_t_c` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	lingua char(10) not null default 'it',
	parola_tradotta_da_correggere varchar(255) not null default '',
	parola_tradotta_corretta varchar(255) not null default ''
) ENGINE=InnoDB;
