create table ordini_acquisto_ricezioni_righe (
	id_ordine_acquisto_ricezione_riga INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_admin INT UNSIGNED NOT NULL default 0,
	id_ordine_acquisto_riga INT UNSIGNED NOT NULL default 0,
	quantita int not null default 0,
	creato_da INT UNSIGNED NOT NULL default 0,
	data_ultima_modifica datetime null default null,
	modificato_da INT UNSIGNED NOT NULL default 0
);