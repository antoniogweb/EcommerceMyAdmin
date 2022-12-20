create table stati_elementi (
	id_stato_elemento INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_stato INT UNSIGNED NOT NULL default 0,
	tabella_rif varchar(50) not null default '',
	id_rif INT UNSIGNED NOT NULL default 0,
	id_admin INT UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;
