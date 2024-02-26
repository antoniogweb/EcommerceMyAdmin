create table ticket_tipologie (
	id_ticket_tipologia INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) not null default '',
	tipo char(255) not null default 'ORDINE',
	id_order INT UNSIGNED NOT NULL default 0
);
