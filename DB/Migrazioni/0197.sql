create table feedback (
	id_feedback INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	attivo tinyint not null default 1,
	autore varchar(255) CHARACTER SET utf8 not null default '',
	testo text CHARACTER SET utf8 null,
	voto DECIMAL(6, 1) NOT NULL default 0.0,
	is_admin tinyint NOT NULL default 0,
	id_page INT UNSIGNED NOT NULL default 0,
	id_user INT UNSIGNED NOT NULL default 0,
	id_p INT UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL
);
