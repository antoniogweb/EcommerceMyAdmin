create table pages_tag (
	id_pt INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_page int(11) UNSIGNED not null,
	id_tag int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index tag_indx(id_tag),
	index page_indx(id_page),
	unique (id_page,id_tag)
)engine=innodb;
