create table menu_admin (
	id_menu_admin INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	attivo tinyint not null default 1,
	titolo varchar(255) CHARACTER SET utf8 not null default '',
	icona varchar(50) CHARACTER SET utf8 not null default '',
	controller varchar(255) CHARACTER SET utf8 not null default '',
	condizioni varchar(255) CHARACTER SET utf8 not null default '',
	tipo varchar(50) CHARACTER SET utf8 not null default '',
	contesto varchar(50) CHARACTER SET utf8 not null default '',
	url varchar(255) CHARACTER SET utf8 not null default '',
	id_p INT UNSIGNED NOT NULL,
	lft INT UNSIGNED NOT NULL,
	rgt INT UNSIGNED NOT NULL,
	id_order INT UNSIGNED NOT NULL
);
