create table applicazioni (
	id_applicazione INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 not null default '',
	codice varchar(50) CHARACTER SET utf8 not null default '',
	db_version char(10) CHARACTER SET utf8 not null default '',
	attivo tinyint not null default 0,
	id_order INT UNSIGNED NOT NULL
);
