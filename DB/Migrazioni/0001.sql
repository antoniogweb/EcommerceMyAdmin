create table documenti (
	id_doc INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 not null default '',
	filename varchar(255) CHARACTER SET utf8 not null default '',
	clean_filename varchar(255) CHARACTER SET utf8 not null default '',
	lingua char(10) not null default 'tutte',
	estensione char(10) not null,
	content_type varchar(100) not null,
	id_page INT UNSIGNED NOT NULL,
	id_order INT UNSIGNED NOT NULL,
	data_documento date NOT NULL
)engine=innodb;
