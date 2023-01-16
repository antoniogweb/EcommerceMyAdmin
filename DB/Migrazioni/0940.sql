create table integrazioni_newsletter_variabili (
	id_integrazione_newsletter_variabile INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	codice_campo varchar(255) not null default '',
	nome_campo varchar(255) not null default '',
	codice_integrazione_newsletter varchar(255) not null default '',
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;
