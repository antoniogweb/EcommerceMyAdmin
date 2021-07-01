create table pages_regioni (
	id_page_regione INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_page int UNSIGNED not null default 0,
	id_regione int UNSIGNED not null default 0,
	id_nazione int UNSIGNED not null default 0,
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;
