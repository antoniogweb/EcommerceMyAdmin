create table ticket_pages (
	id_ticket_page INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_ticket INT UNSIGNED NOT NULL default 0,
	id_page INT UNSIGNED NOT NULL default 0,
	numero_seriale varchar(255) not null default '',
	id_order INT UNSIGNED NOT NULL default 0
);
