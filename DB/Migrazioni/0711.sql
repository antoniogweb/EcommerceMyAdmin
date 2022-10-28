alter table promozioni add tipo_credito ENUM('ESAURIMENTO', 'INFINITO') not null default 'ESAURIMENTO';
