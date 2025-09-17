CREATE TABLE `elementi_tema_contenuti` (
	`id_elemento_tema_contenuto` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	id_elemento_tema INT UNSIGNED NOT NULL default 0,
	id_elemento INT UNSIGNED NOT NULL default 0,
	`tipo_contenuto` char(20) not null default 'pagina',
	nome_file varchar(255) not null default 'default'
) ENGINE=InnoDB;