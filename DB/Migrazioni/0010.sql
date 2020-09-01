create table pages_link (
	id_page_link INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_page int(11) UNSIGNED not null,
	titolo varchar(255) not null default '',
	url_link varchar(255) not null default '',
	id_order INT UNSIGNED NOT NULL
)engine=innodb;
