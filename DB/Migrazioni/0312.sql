create table help_user (
	id_help_user INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_help INT UNSIGNED NOT NULL default 0,
	id_user INT UNSIGNED NOT NULL default 0,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	unique (id_help,id_user)
)engine=innodb;
