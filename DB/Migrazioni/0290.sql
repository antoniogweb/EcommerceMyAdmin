create table documenti_lingue (
	id_documento_lingua INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_doc INT UNSIGNED NOT NULL default 0,
	id_lingua INT UNSIGNED NOT NULL default 0,
	lingua char(2) not null default '',
	includi tinyint not null default 1
)engine=innodb;
