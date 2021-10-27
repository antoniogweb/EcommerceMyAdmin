create table eventi_retargeting_fonti (
	id_fonte INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) not null default '',
	fonte varchar(255) not null default '',
	attivo tinyint not null default 1,
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;
