create table ai_modelli (
	id_ai_modello INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 not null default '',
	attivo tinyint not null default 0,
	codice varchar(50)  not null default '',
	modulo varchar(50)  null default '',
	key_1 varchar(255)  null default '',
	tipo varchar(50)  not null default 'NLP',
	predefinito tinyint not null default 0,
	id_order INT UNSIGNED NOT NULL default 0
);
