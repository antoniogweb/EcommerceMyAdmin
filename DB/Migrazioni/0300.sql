create table pagamenti (
	id_pagamento INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) not null default '',
	codice varchar(255) not null default '',
	attivo tinyint NOT NULL default 1,
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;
