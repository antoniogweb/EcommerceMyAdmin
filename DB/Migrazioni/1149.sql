create table spedizionieri_lettere_vettura (
	id_spedizioniere_lettera_vettura INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_spedizioniere INT UNSIGNED NOT NULL default 0,
	titolo varchar(255) not null default '',
	filename varchar(255) not null default '',
	clean_filename varchar(255) not null default '',
	id_order INT UNSIGNED NOT NULL default 0
);
