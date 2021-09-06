create table help (
	id_help INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo char(255) CHARACTER SET utf8 not null default "",
	controlleraction varchar(100) CHARACTER SET utf8 not null default "",
	tag varchar(100) CHARACTER SET utf8 not null default "",
	descrizione text CHARACTER SET utf8 not null default "",
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;
