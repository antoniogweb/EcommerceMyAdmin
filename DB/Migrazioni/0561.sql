create table reggroups_tipi (
	id_rgt INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_group int(11) UNSIGNED not null,
	id_tipo int(11) UNSIGNED not null,
	tipo char(2) not null default '',
	id_order INT UNSIGNED NOT NULL,
	index id_tipo_indx(id_tipo),
	index user_tipi_indx(id_group),
	unique (id_group,id_tipo,tipo)
)engine=innodb;
