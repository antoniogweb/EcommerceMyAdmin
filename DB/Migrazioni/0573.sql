create table pages_stats (
	id_page_stat INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_page int(11) UNSIGNED not null,
	id_contatto int(11) UNSIGNED not null,
	id_user int(11) UNSIGNED not null
)engine=innodb;
