CREATE TABLE `orders_iva_ripartita` (
	`id_orders_iva_ripartita` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	id_o INT UNSIGNED NOT NULL default 0,
	id_iva INT UNSIGNED NOT NULL default 0,
	aliquota_iva DECIMAL(10,2) NOT NULL DEFAULT '0.00',
	ripartizione DECIMAL(18,10) NOT NULL DEFAULT '0.0000000000',
	ripartizione_su_ivato tinyint not null default 1
) ENGINE=InnoDB;