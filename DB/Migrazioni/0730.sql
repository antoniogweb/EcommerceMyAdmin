create table liste_regalo_tipi (
	id_lista_tipo INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 null,
	campi varchar(255) CHARACTER SET utf8 null,
	giorni_scadenza int not null default 0,
	id_order INT UNSIGNED NOT NULL default 0
);
