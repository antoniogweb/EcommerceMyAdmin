INSERT INTO `categories` (`id_c`, `data_creazione`, `attivo`, `title`, `description`, `alias`, `id_p`, `lft`, `rgt`, `id_order`, `section`, `keywords`, `meta_description`, `add_in_sitemap`, `template`, `immagine`, `mostra_in_home`) VALUES (NULL, CURRENT_TIMESTAMP, '', 'Avvisi', '', 'avvisi', '1', '', '', (SELECT MAX(id_order) FROM categories as c) + 1, 'avvisi', '', '', 'Y', '', '', 'Y')
