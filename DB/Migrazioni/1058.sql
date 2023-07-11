create table immagini_tipologie (
	id_immagine_tipologia INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255)  not null default '',
	id_order int UNSIGNED not null default 0
);
