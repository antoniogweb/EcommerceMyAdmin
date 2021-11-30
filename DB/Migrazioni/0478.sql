create table integrazioni_sezioni_invii (
	id_integrazione_sezione_invio INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_integrazione INT UNSIGNED NOT NULL default 0,
	id_integrazione_sezione INT UNSIGNED NOT NULL default 0,
	sezione varchar(255) not null default '',
	codice_piattaforma varchar(255) not null default '',
	id_order INT UNSIGNED NOT NULL default 0
);
