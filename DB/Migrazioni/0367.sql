create table eventi_retargeting_elemento (
	id_evento_elemento INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_evento INT UNSIGNED NOT NULL default 0,
	id_elemento INT UNSIGNED NOT NULL default 0,
	id_page INT UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL default 0
);
