create table regioni (
	id_regione INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_nazione INT UNSIGNED NOT NULL,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 not null,
	tipo CHAR(3) NOT NULL,
	nazione char(2) not null default '',
	id_order INT UNSIGNED NOT NULL
);

