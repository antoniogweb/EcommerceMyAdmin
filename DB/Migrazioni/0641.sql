alter table promozioni add tipo_sconto ENUM('PERCENTUALE', 'ASSOLUTO') not null default 'PERCENTUALE';
