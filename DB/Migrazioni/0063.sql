create table tag (
	id_tag INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 null,
	alias varchar(255) CHARACTER SET utf8 null,
	id_order INT UNSIGNED NOT NULL default 0
);
