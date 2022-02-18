create table integrazioni_newsletter (
	id_integrazione_newsletter INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 not null default '',
	codice varchar(255) CHARACTER SET utf8 not null default '',
	classe varchar(255) CHARACTER SET utf8 not null default '',
	secret_1 varchar(255) CHARACTER SET utf8 not null default '',
	secret_2 varchar(255) CHARACTER SET utf8 not null default '',
	api_endpoint varchar(255) CHARACTER SET utf8 not null default '',
	codice_lista varchar(255) CHARACTER SET utf8 not null default '',
	attivo tinyint not null default 0,
	id_order INT UNSIGNED NOT NULL
);
