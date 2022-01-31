create table regusers_groups_temp (
	id_ugt INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_user int(11) UNSIGNED not null,
	id_group int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index groupt_indx(id_group),
	index usert_indx(id_user),
	unique (id_group,id_user)
)engine=innodb;
