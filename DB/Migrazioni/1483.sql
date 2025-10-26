create table embeddings (
	id_embedding INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_page INT UNSIGNED NOT NULL default 0,
	id_marchio INT UNSIGNED NOT NULL default 0,
	id_c INT UNSIGNED NOT NULL default 0,
	embeddings text not null
);