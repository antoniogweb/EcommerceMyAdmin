CREATE TABLE `magazzino_articoli_listini` (
  `id_articolo_listino` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  id_import int UNSIGNED NOT NULL DEFAULT '0',
  id_fornitore int UNSIGNED NOT NULL DEFAULT '0',
  titolo varchar(255) NOT NULL DEFAULT '',
  codice varchar(50) NOT NULL DEFAULT '',
  prezzo decimal(10,2) not null default 0.00,
  gtin varchar(100) not null default '',
  mpn varchar(100) not null default ''
) ENGINE=InnoDB;