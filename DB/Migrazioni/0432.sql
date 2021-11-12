create table opzioni (
	id_opzione INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	attivo tinyint not null default 1,
	titolo varchar(255) CHARACTER SET utf8 not null default '',
	valore varchar(255) CHARACTER SET utf8 not null default '',
	codice varchar(255) CHARACTER SET utf8 not null default '',
	id_order INT UNSIGNED NOT NULL
);
