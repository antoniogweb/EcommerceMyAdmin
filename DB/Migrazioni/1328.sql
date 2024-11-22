CREATE TABLE `cookie_archivio` (
	`id_cookie_archivio` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	titolo varchar(255) not null default '',
	dominio varchar(255) not null default '',
	path varchar(255) not null default '',
	durata varchar(255) not null default '',
	servizio varchar(255) not null default '',
	secure char(20) not null default '',
	same_site char(10) not null default '',
	cross_site char(20) not null default ''
) ENGINE=InnoDB;
