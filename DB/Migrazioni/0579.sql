create table routine_aggiornamento (
	id_routine INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 not null default '',
	classe varchar(255) CHARACTER SET utf8 not null default '',
	metodo varchar(255) CHARACTER SET utf8 not null default '',
	eseguita tinyint not null default 0,
	eseguita_il datetime null,
	id_order INT UNSIGNED NOT NULL
);
