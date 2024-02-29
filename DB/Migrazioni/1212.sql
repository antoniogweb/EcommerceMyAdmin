create table ticket_messaggi (
	id_ticket_messaggio INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	descrizione text not null,
	id_ticket INT UNSIGNED NOT NULL default 0,
	id_user INT UNSIGNED NOT NULL default 0,
	id_admin INT UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL default 0
);
