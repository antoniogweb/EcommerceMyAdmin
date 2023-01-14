create table pages_ricerca (
	id_page_ricerca INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_page int UNSIGNED not null,
	titolo varchar(255) not null default '',
	valore varchar(255) not null default '',
	lingua char(10) not null default 'it',
	id_order INT UNSIGNED NOT NULL
)engine=innodb;
