create table ricerche_sinonimi (
	id_ricerca_sinonimo INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255)  not null default '',
	sinonimi varchar(255)  not null default ''
);
