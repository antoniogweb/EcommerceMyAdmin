create table note (
	id_nota INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) not null default '',
	testo text not null default '',
	tabella_rif varchar(50) not null default '',
	id_rif INT UNSIGNED NOT NULL default 0,
	id_admin INT UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;
