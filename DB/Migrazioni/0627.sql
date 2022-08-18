create table redirect (
	id_redirect INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	vecchio_url varchar(255) not null default '',
	nuovo_url varchar(255) not null default '',
	codice_redirect char(10) not null default '301',
	id_order INT UNSIGNED NOT NULL
);
