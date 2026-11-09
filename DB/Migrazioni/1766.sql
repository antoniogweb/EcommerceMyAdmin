ALTER TABLE spedizioni_negozio
ADD INDEX idx_id_spedizioniere (id_spedizioniere),
ADD INDEX idx_id_spedizione_negozio_invio (id_spedizione_negozio_invio);