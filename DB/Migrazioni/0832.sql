create table promozioni_tipi_clienti (
	id_promo_tipo_cliente INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_p int(11) UNSIGNED not null,
	id_tipo_cliente int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index p_indx(id_p),
	index c_indx(id_tipo_cliente),
	unique (id_p,id_tipo_cliente)
)engine=innodb;
