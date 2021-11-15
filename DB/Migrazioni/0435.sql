create table elementi_tema (
	id_elemento_tema INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 not null default '',
	codice varchar(255) CHARACTER SET utf8 not null default '',
	percorso varchar(255) CHARACTER SET utf8 not null default '',
	nome_file varchar(255) CHARACTER SET utf8 not null default 'default',
	id_order INT UNSIGNED NOT NULL
);
