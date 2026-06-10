CREATE TABLE `ordini_acquisto_stati_storico` (
  `id_ordine_acquisto_stato_storico`INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_creazione` int UNSIGNED NOT NULL DEFAULT '0',
  `id_ordine_acquisto` int UNSIGNED NOT NULL DEFAULT '0',
  `id_ordine_acquisto_stato` int UNSIGNED NOT NULL DEFAULT '0',
  `id_admin` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB; 