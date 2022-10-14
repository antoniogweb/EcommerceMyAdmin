create table regusers_integrazioni_login (
	id_user_integrazione INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_user INT UNSIGNED NOT NULL default 0,
	id_integrazione_login varchar(255) CHARACTER SET utf8 not null default '',
	codice varchar(255) CHARACTER SET utf8 not null default '',
	user_id_app varchar(255) CHARACTER SET utf8 not null default '',
	time_ultimo_accesso int not null default 0
);
