create table orders_pdf (
	id_o_pdf INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_o INT UNSIGNED NOT NULL default 0,
	titolo varchar(255) not null default '',
	filename varchar(255) not null default '',
	corrente tinyint UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL default 0
);
