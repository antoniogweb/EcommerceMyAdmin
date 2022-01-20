update nazioni inner join iva on nazioni.iso_country_code = iva.nazione set nazioni.id_iva = iva.id_iva where nazioni.id_iva = 0;
