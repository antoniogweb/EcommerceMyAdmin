create table categories_caratteristiche (
	id_c_car INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_c int(11) UNSIGNED not null,
	id_car int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index categories_caratteristiche_id_c(id_c),
	index categories_caratteristiche_id_car(id_car),
	unique (id_c,id_car)
)engine=innodb;
