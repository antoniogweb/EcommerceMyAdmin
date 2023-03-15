create table ricerche (
	id_ricerca INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	termini varchar(255)  not null default '',
	cart_uid char(32)  not null default ''
);
