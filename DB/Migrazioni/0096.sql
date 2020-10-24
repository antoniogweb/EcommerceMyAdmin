create table reggroups_documenti (
	id_group_doc INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_doc int(11) UNSIGNED not null,
	id_group int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index group_indx(id_group),
	index cont_indx(id_doc),
	foreign key group_fky(id_group) references reggroups (id_group),
	foreign key doc_fky(id_doc) references documenti (id_doc),
	unique (id_group,id_doc)
)engine=innodb;
