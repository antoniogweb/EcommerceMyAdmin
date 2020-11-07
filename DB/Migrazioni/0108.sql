create table combinazioni_listini (
	id_combinazione_listino INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_c INT UNSIGNED NOT NULL default 0,
	nazione char(2) not null default 'W',
	price DECIMAL(10, 4) NOT NULL default 0.0000
);
