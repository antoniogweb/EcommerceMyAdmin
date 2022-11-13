create table liste_regalo_email (
	id_lista_regalo_email INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	email varchar(255) CHARACTER SET utf8 default '',
	firma varchar(255) CHARACTER SET utf8 not null default '',
	dedica text CHARACTER SET utf8 null,
	id_lista_regalo INT UNSIGNED NOT NULL default 0,
	id_user INT UNSIGNED NOT NULL default 0,
	id_admin INT UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL default 0
);
