create table orders_periodi_reso (
	id_o_periodo_reso INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_o INT UNSIGNED NOT NULL default 0,
	id_admin INT UNSIGNED NOT NULL default 0,
	id_spedizione_negozio INT UNSIGNED NOT NULL default 0,
	data_inizio date not null default '0000-00-00',
	data_fine date not null default '0000-00-00',
	manuale TINYINT UNSIGNED NOT NULL default 0,
	richiesta TINYINT UNSIGNED NOT NULL default 0,
	ip varchar(50) not null default '',
	data_richiesta datetime null default null,
	id_order INT UNSIGNED NOT NULL default 0
);