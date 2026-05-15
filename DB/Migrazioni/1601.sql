CREATE TABLE `orders_stati` (
  `id_o_stato`INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_creazione` int UNSIGNED NOT NULL DEFAULT '0',
  `id_o` int UNSIGNED NOT NULL DEFAULT '0',
  `stato` varchar(255) NOT NULL DEFAULT '',
  `id_user` int UNSIGNED NOT NULL DEFAULT '0',
  `id_admin` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB; 