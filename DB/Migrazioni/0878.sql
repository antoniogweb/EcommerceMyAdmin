create table admingroups_controllers (
	id_group_controller INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_group int(11) UNSIGNED not null,
	id_controller int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index admingroups_controllers_id_group_indx(id_group),
	index admingroups_controllers_id_controller_indx(id_controller),
	unique (id_group,id_controller)
)engine=innodb;
