create table ip_location (
	id_ip_location INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	ip char(20) not null default '',
	nazione char(2) not null default '',
	time_creazione int not null default 0
);
