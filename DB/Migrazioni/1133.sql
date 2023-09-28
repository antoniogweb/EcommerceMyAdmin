create table spedizioni_negozio_invii (
	id_spedizione_negozio_invio INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	data_spedizione date null,
	id_spedizioniere INT UNSIGNED NOT NULL default 0,
	stato char(2) not null default 'A'
);
