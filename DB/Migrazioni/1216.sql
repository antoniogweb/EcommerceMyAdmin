create table ticket_file (
	id_ticket_file INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	filename varchar(255) not null default '',
	clean_filename varchar(255) not null default '',
	tipo char(10) not null default 'IMMAGINE',
	estensione char(10) not null default '',
	mime_type varchar(100) not null default '',
	id_ticket INT UNSIGNED NOT NULL default 0,
	id_user INT UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL default 0
);
