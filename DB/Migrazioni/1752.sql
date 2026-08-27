CREATE OR REPLACE VIEW righe_da_ordinare AS
SELECT
	righe.id_r,
	righe.id_o,
	righe.id_c,
	righe.id_page,
	righe.quantity,
	righe.codice,
	righe.title,
	righe.attributi,
	righe.price_ivato,
	righe.prezzo_finale_ivato,
	righe.id_riga_tipologia,
	righe.id_order,
	righe.qta_da_ordinare,
	righe.attributi_backend,
	coalesce(magazzino_articoli_combinazioni.id_articolo,0) as id_articolo,
	1 AS quantita_articolo
FROM righe
LEFT JOIN magazzino_articoli_combinazioni ON magazzino_articoli_combinazioni.id_c = righe.id_c
WHERE righe.gift_card = 0
AND righe.prodotto_digitale = 0
AND righe.prodotto_crediti = 0
AND righe.id_riga_tipologia = 0
AND NOT EXISTS (
	SELECT 1
	FROM pages_articoli
	WHERE pages_articoli.id_page = righe.id_page
)

UNION ALL

SELECT
	righe.id_r,
	righe.id_o,
	magazzino_articoli_combinazioni.id_c,
	magazzino_articoli_combinazioni.id_page,
	righe.quantity,
	righe.codice,
	righe.title,
	righe.attributi,
	righe.price_ivato,
	righe.prezzo_finale_ivato,
	righe.id_riga_tipologia,
	righe.id_order,
	(righe.qta_da_ordinare * pages_articoli.quantita) AS qta_da_ordinare,
	righe.attributi_backend,
	pages_articoli.id_articolo,
	pages_articoli.quantita AS quantita_articolo
FROM righe
INNER JOIN pages_articoli ON pages_articoli.id_page = righe.id_page
INNER JOIN magazzino_articoli_combinazioni ON magazzino_articoli_combinazioni.id_articolo = pages_articoli.id_articolo
WHERE righe.gift_card = 0
AND righe.prodotto_digitale = 0
AND righe.prodotto_crediti = 0
AND righe.id_riga_tipologia = 0;
