create table liste_regalo (
	id_lista_regalo INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	time_creazione INT UNSIGNED NOT NULL default 0,
	id_lista_tipo INT UNSIGNED NOT NULL default 0,
	titolo varchar(255) CHARACTER SET utf8 null,
	genitore_1 varchar(255) CHARACTER SET utf8 null,
	genitore_2 varchar(255) CHARACTER SET utf8 null,
	sesso ENUM('M', 'F') not null default 'M',
	data_nascita date null,
	data_battesimo date null,
	id_order INT UNSIGNED NOT NULL default 0
);
