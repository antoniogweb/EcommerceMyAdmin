create table crediti (
	id_crediti INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_user INT UNSIGNED NOT NULL default 0,
	attivo tinyint not null default 0,
	azione char(1) not null default 'S',
	numero_crediti int not null default 0,
	data_scadenza date not null default '0000-00-00',
	in_scadenza tinyint not null default 0,
	data_invio_avviso date not null default '0000-00-00',
	time_invio_avviso int not null default 0,
	fonte varchar(255) not null default 'ORDINE',
	email varchar(255) not null default '',
	lingua char(2) not null default 'it',
	nazione char(2) not null default 'IT',
	id_r INT UNSIGNED NOT NULL default 0,
	id_o INT UNSIGNED NOT NULL default 0
);
