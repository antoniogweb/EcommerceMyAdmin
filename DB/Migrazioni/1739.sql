CREATE TABLE `ai_richieste_contesti_frontend` (
  `id_ai_richiesta_contesto_frontend` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `data_creazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  id_ai_richiesta int UNSIGNED NOT NULL DEFAULT 0,
  tipo char(10) not null default 'ORDER',
  contesto text null
) ENGINE=InnoDB;