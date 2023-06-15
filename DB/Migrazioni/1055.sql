create table promozioni_invii (
	id_promozione_invio INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	time_creazione INT UNSIGNED NOT NULL default 0,
	id_p INT UNSIGNED NOT NULL default 0,
	nome varchar(255) not null default '',
	cognome varchar(255) not null default '',
	email varchar(255) not null default '',
	inviato TINYINT UNSIGNED NOT NULL default 0,
	numero_tentativi TINYINT UNSIGNED NOT NULL default 0,
	time_ultimo_invio INT UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL default 0
);
