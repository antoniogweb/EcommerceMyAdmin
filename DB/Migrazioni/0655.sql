create table cart_elementi (
	id_cart_elemento INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	email varchar(255) CHARACTER SET utf8 not null default '',
	testo text CHARACTER SET utf8 null,
	id_order INT UNSIGNED NOT NULL default 0
);
