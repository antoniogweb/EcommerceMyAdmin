create table ticket_stati (
	id_ticket_stato INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	codice char(2) not null default '',
	titolo varchar(255) not null default '',
	stile varchar(255) not null default '',
	id_order INT UNSIGNED NOT NULL default 0
);
