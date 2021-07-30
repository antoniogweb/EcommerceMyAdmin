create table pages_lingue (
	id_page_lingua INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_page INT UNSIGNED NOT NULL default 0,
	id_lingua INT UNSIGNED NOT NULL default 0,
	lingua char(10) not null default '',
	includi tinyint not null default 1
)engine=innodb;
