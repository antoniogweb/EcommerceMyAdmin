create table eventi_retargeting_gruppi_fonti (
	id_gruppo_retargeting_fonte INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_gruppo_retargeting int(11) UNSIGNED not null,
	id_fonte int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL default 0,
	index eventi_retargeting_gruppi_fonti_gruppi(id_gruppo_retargeting),
	index eventi_retargeting_gruppi_fonti_fonti(id_fonte),
	unique (id_gruppo_retargeting,id_fonte)
)engine=innodb;
