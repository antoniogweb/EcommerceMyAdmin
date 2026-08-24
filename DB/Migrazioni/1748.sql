create table pages_articoli (
	id_page_articolo INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_page INT UNSIGNED NOT NULL default 0,
	id_articolo INT UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL default 0,
	unique (id_page,id_articolo)
);