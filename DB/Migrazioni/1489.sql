create table log_account (
	id_log_account INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	time_creazione int not null default 0,
	id_user INT UNSIGNED NOT NULL default 0,
	email varchar(100) not null default '',
	risultato tinyint not null default 0,
	azione char(30) not null default '',
	contesto char(5) not null default 'FRONT',
	in_pausa tinyint not null default 0
);