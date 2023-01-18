create table ip_filter (
	id_ip_filter INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	time_creazione INT UNSIGNED NOT NULL default 0,
	ip char(20) not null default "",
	whitelist tinyint not null default 1
)engine=innodb;
