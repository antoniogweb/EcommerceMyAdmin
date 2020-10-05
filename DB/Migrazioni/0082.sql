create table fasce_prezzo (
	id_fascia_prezzo INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 null,
	da decimal(10,2) not null default 0.00,
	a decimal(10,2) not null default 0.00
);
