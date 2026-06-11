create table immagini_archivi (
	id_immagine_archivio INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	immagine varchar(255) not null default '',
	id_c INT UNSIGNED NOT NULL default 0,
	id_marchio INT UNSIGNED NOT NULL default 0,
	id_tag INT UNSIGNED NOT NULL default 0,
	id_immagine_tipologia INT UNSIGNED NOT NULL default 0,
	alt_tag varchar(255) not null default '',
	id_order INT UNSIGNED NOT NULL default 0
);
