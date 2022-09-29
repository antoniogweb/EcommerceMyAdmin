create table combinazioni_alias (
	id_c_alias INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_c INT UNSIGNED NOT NULL default 0,
	alias_codice varchar(255) CHARACTER SET utf8 not null default '',
	alias_attributi_codice varchar(255) CHARACTER SET utf8 not null default '',
	lingua char(10) not null default 'it'
);
