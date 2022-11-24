create table pages_categories (
	id_page_category INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_page int(11) UNSIGNED not null,
	id_c int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index pages_categories_id_page_indx(id_page),
	index pages_categories_id_c_indx(id_c),
	unique (id_page,id_c)
)engine=innodb;
