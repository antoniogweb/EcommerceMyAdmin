create table gestionali (
	id_gestionale INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) not null default '',
	codice varchar(255) not null default '',
	classe varchar(255) not null default '',
	param_1 varchar(255) not null default '',
	param_2 varchar(255) not null default '',
	api_endpoint varchar(255) not null default '',
	attivo tinyint not null default 0,
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;
