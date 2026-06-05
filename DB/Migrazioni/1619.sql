CREATE TABLE ordini_acquisto_righe_tipologie (
    id_ordine_acquisto_riga_tipologia INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    data_creazione timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    titolo varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
    id_order INT UNSIGNED NOT NULL
) ENGINE=InnoDB;