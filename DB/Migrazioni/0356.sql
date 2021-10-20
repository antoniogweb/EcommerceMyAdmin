create table log_piattaforma (
	id_log INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	ip varchar(255) not null default '',
	`_post` text not null default '',
	`_get` text not null default '',
	cart_uid varchar(255) not null default '',
	numero_prodotti_carrello INT UNSIGNED NOT NULL default 0,
	tipo char(30) not null default '',
	`errori` text not null default '',
	risultato varchar(255) not null default '',
	`user_agent` text not null default '',
	time_inserimento INT UNSIGNED NOT NULL default 0
);
