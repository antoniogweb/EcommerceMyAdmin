create table adminusers_opzioni (
	id_adminuser_opzione INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	time_creazione int not null default 0,
	id_user INT UNSIGNED NOT NULL default 0,
	app varchar(30) not null default '',
	controller varchar(30) not null default '',
	action varchar(30) not null default '',
	id_record INT UNSIGNED NOT NULL default 0,
	valore text not null default ''
);