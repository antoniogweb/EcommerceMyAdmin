create table spedizioni_negozio_eventi (
	id_spedizione_negozio_evento INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	codice char(2) not null default 'I',
	titolo varchar(255) not null default ''
);
