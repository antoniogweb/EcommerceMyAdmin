create table spedizioni_negozio_righe (
	id_spedizione_negozio_riga INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_spedizione_negozio INT UNSIGNED NOT NULL default 0,
	id_r INT UNSIGNED NOT NULL default 0,
	quantity INT UNSIGNED NOT NULL default 0,
	peso decimal(10,2)	 NOT NULL default 0.00
);
