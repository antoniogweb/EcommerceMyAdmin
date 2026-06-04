CREATE TABLE magazzino_articoli (
    id_articolo INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    data_creazione timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    titolo varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
    codice varchar(50) NOT NULL DEFAULT '',
    prezzo decimal(10,2) not null default 0.00,
    id_iva int UNSIGNED NOT NULL DEFAULT 0,
    aliquota_iva decimal(10,2) not null default 0.00,
    id_marchio int UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;