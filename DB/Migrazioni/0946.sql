create table pixel_eventi (
	id_pixel_evento INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	evento varchar(50)  not null default '',
	tabella_elemento varchar(50)  not null default '',
	id_elemento INT UNSIGNED NOT NULL default 0,
	id_pixel INT UNSIGNED NOT NULL default 0
);
