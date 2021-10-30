create table sitemap (
	id_sitemap INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	loc varchar(255) not null default '',
	priorita decimal(4,1) not null default 0.8,
	ultima_modifica datetime null,
	id_c INT UNSIGNED NOT NULL default 0,
	id_page INT UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;
