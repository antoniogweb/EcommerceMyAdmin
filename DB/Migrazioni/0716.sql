create table integrazioni_login (
	id_integrazione_login INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 not null default '',
	codice varchar(255) CHARACTER SET utf8 not null default '',
	classe varchar(255) CHARACTER SET utf8 not null default '',
	app_id varchar(255) CHARACTER SET utf8 not null default '',
	secret_key varchar(255) CHARACTER SET utf8 not null default '',
	app_version varchar(255) CHARACTER SET utf8 not null default '',
	attivo tinyint not null default 0,
	id_order INT UNSIGNED NOT NULL
);
