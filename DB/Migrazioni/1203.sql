create table ticket (
	id_ticket INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_ticket_tipologia INT UNSIGNED NOT NULL default 0,
	id_user INT UNSIGNED NOT NULL default 0,
	id_admin INT UNSIGNED NOT NULL default 0,
	
	ticket_uid char(32) not null default '',
	
	stato char(255) not null default 'B',
	data_invio timestamp default CURRENT_TIMESTAMP,
	data_preso_in_carico timestamp default CURRENT_TIMESTAMP,
	data_chiusura timestamp default CURRENT_TIMESTAMP,
	
	oggetto varchar(255) not null default '',
	descrizione text not null,
	
	immagine_1 varchar(40) not null default '',
	content_type_1 varchar(30) not null default '',
	
	immagine_2 varchar(40) not null default '',
	content_type_2 varchar(30) not null default '',
	
	immagine_3 varchar(40) not null default '',
	content_type_3 varchar(30) not null default '',
	
	immagine_4 varchar(40) not null default '',
	content_type_4 varchar(30) not null default '',
	
	immagine_5 varchar(40) not null default '',
	content_type_5 varchar(30) not null default '',
	
	immagine_scontrino varchar(40) not null default '',
	content_type_scontrino varchar(30) not null default '',
	
	video varchar(40) not null default '',
	content_type_video varchar(30) not null default '',
	
	id_order INT UNSIGNED NOT NULL default 0
);
