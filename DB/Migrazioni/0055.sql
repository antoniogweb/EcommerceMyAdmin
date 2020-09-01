create table personalizzazioni (
	id_pers INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 not null default '',
	tipo char(30) not null default "TESTO",
	numero_caratteri int not null default 0
)engine=innodb;
