CREATE TABLE `pages_nazioni` (
  `id_page_nazione` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  id_page int UNSIGNED NOT NULL DEFAULT 0,
  nazione char(2) not null default '',
  unique (id_page,nazione)
) ENGINE=InnoDB;