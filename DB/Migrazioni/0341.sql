create table contatti (
	id_contatto INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	email varchar(200) CHARACTER SET utf8 not null,
	nome varchar(200) CHARACTER SET utf8 not null,
	cognome varchar(200) CHARACTER SET utf8 not null,
	telefono varchar(200) CHARACTER SET utf8 not null,
	id_order INT UNSIGNED NOT NULL,
	unique(email),
	index(email)
)engine=innodb;
