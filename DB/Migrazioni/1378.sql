CREATE TABLE `orders_date` (
	`id_o_date` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	id_o INT UNSIGNED NOT NULL default 0,
	id_user INT UNSIGNED NOT NULL default 0,
	data_pagamento DATETIME NULL DEFAULT NULL,
	time_pagamento int not null default 0,
	pagato tinyint not null default 0,
	data_annullamento DATETIME NULL DEFAULT NULL,
	time_annullamento int not null default 0,
	annullato tinyint not null default 0
) ENGINE=InnoDB;

