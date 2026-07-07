CREATE TABLE `fornitori_import` (
  `id_fornitore_import` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_fornitore` int UNSIGNED NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `clean_filename` varchar(255) NOT NULL DEFAULT '',
  `foglio` tinyint NOT NULL DEFAULT 0,
  `colonna_descrizione` char(3) NOT NULL DEFAULT '',
  `colonna_codice_sku` char(3) NOT NULL DEFAULT '',
  `colonna_codice_ean_gtin` char(3) NOT NULL DEFAULT '',
  `colonna_codice_mpn_barcode` char(3) NOT NULL DEFAULT '',
  `id_order` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB;