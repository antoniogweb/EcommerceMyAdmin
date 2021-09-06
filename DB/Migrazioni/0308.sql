create table help_item (
	id_help_item INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_help INT UNSIGNED NOT NULL default 0,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo char(255) CHARACTER SET utf8 not null default "",
	selettore varchar(30) CHARACTER SET utf8 not null default "",
	descrizione text CHARACTER SET utf8 not null default "",
	mostra tinyint not null default 1,
	letto tinyint not null default 0,
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;
