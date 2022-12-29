create table gestionali_variabili (
	id_gestionale_variabile INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) not null default '',
	valore varchar(255) not null default '',
	codice_gestionale varchar(255) not null default '',
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;
