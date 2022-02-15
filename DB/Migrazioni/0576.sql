create table regusers_nazioni (
	id_un INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_user int(11) UNSIGNED not null,
	id_nazione int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	unique (id_user,id_nazione)
)engine=innodb;
