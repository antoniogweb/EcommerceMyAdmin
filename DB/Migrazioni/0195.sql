create table sections_sections (
	id_sec_sec INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	section varchar(255) CHARACTER SET utf8 null,
	in_section varchar(255) CHARACTER SET utf8 null,
	id_order INT UNSIGNED NOT NULL default 0
);
