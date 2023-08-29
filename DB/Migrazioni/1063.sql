create table spedizioni_negozio (
	id_spedizione_negozio INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	
	tipologia char(50) CHARACTER SET utf8 not null default 'PORTO_FRANCO',
	
	data_spedizione date null,
	data_invio date null,
	data_consegna date null,
	
	id_user INT UNSIGNED NOT NULL default 0,
	ragione_sociale varchar(255) CHARACTER SET utf8 not null default '',
	ragione_sociale_2 varchar(255) CHARACTER SET utf8 not null default '',
	indirizzo varchar(255) CHARACTER SET utf8 not null default '',
	cap char(20) CHARACTER SET utf8 not null default '',
	citta varchar(255) CHARACTER SET utf8 not null default '',
	provincia varchar(255) CHARACTER SET utf8 not null default '',
	dprovincia varchar(255) CHARACTER SET utf8 not null default '',
	nazione varchar(255) CHARACTER SET utf8 not null default '',
	telefono varchar(255) CHARACTER SET utf8 not null default '',
	email varchar(255) CHARACTER SET utf8 not null default '',
	
	id_spedizioniere INT UNSIGNED NOT NULL default 0,
	
	note text CHARACTER SET utf8mb4 not null default '',
	note_interne text CHARACTER SET utf8mb4 not null default '',
	
	id_order INT UNSIGNED NOT NULL default 0
);
