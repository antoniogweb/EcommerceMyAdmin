CREATE TABLE ordini_acquisto_righe (
    id_ordine_acquisto_riga INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    data_creazione timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    titolo varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
    id_admin int UNSIGNED NOT NULL DEFAULT 0,
    codice varchar(50) NOT NULL DEFAULT '',
    prezzo decimal(10,2) not null default 0.00,
    quantita int UNSIGNED not null default 0, 
    id_iva int UNSIGNED NOT NULL DEFAULT 0,
    aliquota_iva decimal(10,2) not null default 0.00,
    id_marchio int UNSIGNED NOT NULL DEFAULT 0,
    id_articolo int UNSIGNED NOT NULL DEFAULT 0,
    id_ordine_acquisto int UNSIGNED NOT NULL DEFAULT 0,
    id_ordine_acquisto_riga_tipologia int UNSIGNED NOT NULL DEFAULT 0,
    id_order INT UNSIGNED NOT NULL
) ENGINE=InnoDB;