CREATE TABLE `ordini_acquisto_stati` (
  `id_ordine_acquisto_stato` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titolo` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `classe` varchar(50) not null default '',
  chiuso tinyint not null default 0,
  inviato tinyint not null default 0,
  `id_order` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB;