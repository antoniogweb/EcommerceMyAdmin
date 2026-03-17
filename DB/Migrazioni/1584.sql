CREATE TABLE `ai_richieste_response` (
  `id_ai_richieste_response`INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_ai_richiesta` int UNSIGNED NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  request text NOT NULL,
  response text NOT NULL,
  `user_agent` varchar(255) NOT NULL DEFAULT '',
  tipo char(25) NOT NULL DEFAULT ''
) ENGINE=InnoDB;