CREATE TABLE `documenti_download` (
	`id_doc_dow` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	id_doc INT UNSIGNED NOT NULL default 0,
	id_user INT UNSIGNED NOT NULL default 0
) ENGINE=InnoDB;