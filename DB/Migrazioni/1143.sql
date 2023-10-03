create table spedizioni_negozio_servizi (
	id_spedizione_negozio_servizio INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_spedizione_negozio INT UNSIGNED NOT NULL default 0,
	titolo varchar(50) not null default '',
	codice varchar(50) not null default '',
	id_order INT UNSIGNED NOT NULL default 0
);
