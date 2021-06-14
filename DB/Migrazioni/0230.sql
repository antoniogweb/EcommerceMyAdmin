create table tipi_documento_estensioni (
	id_tipo_doc_est INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	estensione char(10) not null default '',
	id_tipo_doc INT UNSIGNED NOT NULL default 0
);
