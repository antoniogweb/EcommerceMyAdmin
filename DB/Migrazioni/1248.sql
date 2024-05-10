create table righe_tipologie (
	id_riga_tipologia INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) not null default '',
	titolo_breve varchar(50) not null default '',
	moltiplicatore tinyint not null default 1,
	max_numero_in_ordine tinyint not null default 1,
	classe varchar(255) not null default '',
	id_order INT UNSIGNED NOT NULL default 0
);
