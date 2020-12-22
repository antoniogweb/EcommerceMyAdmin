create table conteggio_query (
	id_conteggio INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	numero INT UNSIGNED NOT NULL default 0
);
