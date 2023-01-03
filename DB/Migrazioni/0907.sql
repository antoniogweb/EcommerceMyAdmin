create table combinazioni_movimenti (
	id_combinazione_movimento INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(30) not null default '',
	valore int not null default 0,
	id_c INT UNSIGNED NOT NULL default 0,
	id_r INT UNSIGNED NOT NULL default 0,
	id_o INT UNSIGNED NOT NULL default 0,
	giacenza int not null default 0,
	resetta tinyint not null default 0
)engine=innodb;
