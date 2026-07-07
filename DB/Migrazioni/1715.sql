ALTER TABLE magazzino_articoli
  ADD INDEX idx_magazzino_articoli_gtin (gtin),
  ADD INDEX idx_magazzino_articoli_mpn (mpn);