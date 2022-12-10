create table orders_gateway_response (
	id_order_gateway_response INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	cart_uid char(255) not null default "",
	response text not null default "",
	risultato_transazione tinyint not null default 0
)engine=innodb;
