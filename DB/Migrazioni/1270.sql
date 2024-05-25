create table commessi (
	id_commesso INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) not null default '',
	id_order INT UNSIGNED NOT NULL default 0
);
