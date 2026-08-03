CREATE TABLE `categories_nazioni` (
  `id_c_nazione` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  id_c int UNSIGNED NOT NULL DEFAULT 0,
  nazione char(2) not null default '',
  unique (id_c,nazione)
) ENGINE=InnoDB;