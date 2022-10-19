create table liste_regalo_pages (
	id_lista_regalo_page INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	time_creazione INT UNSIGNED NOT NULL default 0,
	id_lista_regalo INT UNSIGNED NOT NULL default 0,
	id_page INT UNSIGNED NOT NULL default 0,
	id_c INT UNSIGNED NOT NULL default 0,
	titolo varchar(255) not null default '',
	quantity int not null default 0,
	id_order INT UNSIGNED NOT NULL default 0
);
