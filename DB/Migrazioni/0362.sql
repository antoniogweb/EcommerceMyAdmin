create table eventi_retargeting (
	id_evento INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 null default '',
	tipo varchar(255) CHARACTER SET utf8 null default '',
	scatta_dopo_ore int not null default 0,
	attivo tinyint not null default 1,
	id_page INT UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL default 0
);
