create table notifiche (
	id_notifica INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	data_risoluzione datetime null,
	titolo varchar(255) not null default '',
	contesto varchar(255) not null default '',
	url varchar(255) not null default '',
	classe varchar(100) not null default '',
	icona varchar(100) not null default '',
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;
