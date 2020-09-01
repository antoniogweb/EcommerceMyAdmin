create table pages_personalizzazioni (
	id_pp INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_page int(11) UNSIGNED not null,
	id_pers int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index pers_indx(id_pers),
	index page_indx(id_page),
	unique (id_page,id_pers)
)engine=innodb;
