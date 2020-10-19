create table reggroups_contenuti (
	id_group_cont INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_cont int(11) UNSIGNED not null,
	id_group int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index group_indx(id_group),
	index cont_indx(id_cont),
	foreign key group_fky(id_group) references reggroups (id_group),
	foreign key cont_fky(id_cont) references contenuti (id_cont),
	unique (id_group,id_cont)
)engine=innodb;
