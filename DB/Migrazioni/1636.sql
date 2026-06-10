create table ordini_acquisto_pdf (
	id_ordine_acquisto_pdf INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_ordine_acquisto INT UNSIGNED NOT NULL default 0,
	titolo varchar(255) not null default '',
	filename varchar(255) not null default '',
	corrente tinyint UNSIGNED NOT NULL default 0,
	inviato tinyint not null default 0,
	id_order INT UNSIGNED NOT NULL default 0
);
