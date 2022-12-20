create table spedizionieri (
	id_spedizioniere INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) not null default '',
	attivo tinyint not null default 1,
	id_admin INT UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;
