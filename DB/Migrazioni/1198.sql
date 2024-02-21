create table pages_associate (
	id_page_associata INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_page int UNSIGNED not null default 0,
	id_associata int UNSIGNED not null default 0,
	numero_acquisti int UNSIGNED not null default 0
);
