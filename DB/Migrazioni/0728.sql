alter table pages add tipo_sconto ENUM('PERCENTUALE', 'ASSOLUTO') not null default 'PERCENTUALE' after in_promozione;
