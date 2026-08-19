CREATE TABLE `fornitori_contatti` (
  `id_fornitore_contatto` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  id_fornitore int UNSIGNED NOT NULL DEFAULT 0,
  nome varchar(255) not null default '',
  cognome varchar(255) not null default '',
  telefono varchar(255) not null default '',
  email varchar(255) not null default '',
  id_order int UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;