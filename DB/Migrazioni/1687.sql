create table ordini_acquisto_ricezioni (
	id_ordine_acquisto_ricezione INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	data_ricezione_merce date default null,
	numero_documento_trasporto varchar(100) not null default '',
	creato_da INT UNSIGNED NOT NULL default 0,
	data_ultima_modifica datetime null default null,
	modificato_da INT UNSIGNED NOT NULL default 0
);