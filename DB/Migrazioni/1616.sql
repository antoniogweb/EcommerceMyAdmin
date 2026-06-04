CREATE TABLE magazzino_articoli_combinazioni (
    id_articolo_combinazione INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    data_creazione timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_articolo int(11) UNSIGNED not null default 0,
	id_c int(11) UNSIGNED not null not null default 0,
	id_order INT UNSIGNED NOT NULL,
	unique (id_articolo,id_c)
) ENGINE=InnoDB;