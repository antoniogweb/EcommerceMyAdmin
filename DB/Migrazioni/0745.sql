create table liste_regalo_link (
	id_lista_regalo_link INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	time_creazione INT UNSIGNED NOT NULL default 0,
	id_lista_regalo INT UNSIGNED NOT NULL default 0,
	nome varchar(255) not null default '',
	cognome varchar(255) not null default '',
	email varchar(255) not null default '',
	id_order INT UNSIGNED NOT NULL default 0
);
