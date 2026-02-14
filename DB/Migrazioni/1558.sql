create table cidr_filter (
	id_cidr_filter INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	time_creazione INT UNSIGNED NOT NULL default 0,
	cidr char(60) not null default "",
	whitelist tinyint not null default 1,
	rete varchar(100) not null default "",
	ip_version tinyint not null default 0
)engine=innodb;