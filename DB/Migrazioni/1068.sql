create table spedizioni_negozio_stati (
	id_spedizione_negozio_stato INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	codice char(2) not null default 'A',
	titolo varchar(255) not null default '',
	style varchar(255) not null default '',
	id_order INT UNSIGNED NOT NULL default 0
);
