create table feed (
	id_feed INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 not null default '',
	attivo tinyint not null default 0,
	codice varchar(50)  not null default '',
	modulo varchar(50)  null default '',
	id_order INT UNSIGNED NOT NULL default 0
);
