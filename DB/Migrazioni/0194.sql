create table pages_pages (
	id_page_page INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_page INT UNSIGNED NOT NULL default 0,
	id_corr INT UNSIGNED NOT NULL default 0,
	section varchar(255) CHARACTER SET utf8 null,
	id_order INT UNSIGNED NOT NULL default 0
);
