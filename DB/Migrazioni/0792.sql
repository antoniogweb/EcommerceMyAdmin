create table ip_check (
	id_ip_check INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	time_creazione INT UNSIGNED NOT NULL default 0,
	ip char(20) not null default "",
	chiave varchar(50) not null default '',
	superato_limite_istantaneo tinyint UNSIGNED not null default 0,
	superato_limite_minuto tinyint UNSIGNED not null default 0,
	superato_limite_orario tinyint UNSIGNED not null default 0
)engine=innodb;
