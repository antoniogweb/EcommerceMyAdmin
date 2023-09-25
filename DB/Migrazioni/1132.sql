create table spedizioni_negozio_info (
	id_spedizione_negozio_info INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_spedizione_negozio INT UNSIGNED NOT NULL default 0,
	codice_info varchar(50) not null default '',
	codice_corriere varchar(50) not null default '',
	descrizione MEDIUMTEXT not null
);
