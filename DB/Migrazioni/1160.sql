create table promozioni_marchi (
	id_pm INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_p int(11) UNSIGNED not null,
	id_marchio int(11) UNSIGNED not null,
	includi tinyint not null default 1,
	id_order INT UNSIGNED NOT NULL,
	index promozioni_marchi_id_p_indx(id_p),
	index promozioni_marchi_id_marchio_indx(id_marchio),
	unique (id_p,id_marchio)
)engine=innodb;
