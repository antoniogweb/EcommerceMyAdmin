create table integrazioni_sezioni (
	id_integrazione_sezione INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	sezione varchar(255) not null default '',
	id_integrazione INT UNSIGNED NOT NULL default 0,
	metodo varchar(255) not null default '',
	id_order INT UNSIGNED NOT NULL default 0
);
