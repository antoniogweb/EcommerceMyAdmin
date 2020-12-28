create table adminusers (
   id_user INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
   username VARCHAR(80) binary NOT NULL,
   password CHAR(40) binary NOT NULL,
   last_failure INT UNSIGNED NOT NULL,
   has_confirmed INT UNSIGNED NOT NULL,
   unique(username),
   index(username, password)
)engine=innodb;

create table admingroups (
	id_group INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(30) binary NOT NULL,
	unique(name)
)engine=innodb;

create table adminusers_groups (
	id_ug INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_user int(11) UNSIGNED not null,
	id_group int(11) UNSIGNED not null,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_order INT UNSIGNED NOT NULL,
	index group_indx(id_group),
	index user_indx(id_user),
	foreign key group_fky(id_group) references admingroups (id_group),
	foreign key user_fky(id_user) references adminusers (id_user),
	unique (id_group,id_user)
)engine=innodb;


insert into adminusers (username,password) values ('admin',sha1('admin'));

insert into admingroups (name) values ('admin');

CREATE TABLE adminsessions (
   uid CHAR(32) NOT NULL,
   token CHAR(32) NOT NULL,
   id_user INT UNSIGNED NOT NULL,
   creation_date INT UNSIGNED NOT NULL,
   user_agent CHAR(32) NOT NULL,
   INDEX(uid)
);


create table accesses (
	id int(12) not null auto_increment primary key,
	ip char(20) not null,data char(10) not null,
	ora char(8) not null,
	username varchar(30) not null
);


create table categories (
	id_c INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	attivo char(1) not null default 'Y',
	title varchar(300) CHARACTER SET utf8 not null, 
	description text CHARACTER SET utf8 not null,
	alias varchar(300) CHARACTER SET utf8 not null,
	section char(20) CHARACTER SET utf8 not null,
	id_p INT UNSIGNED NOT NULL,
	lft INT UNSIGNED NOT NULL,
	rgt INT UNSIGNED NOT NULL,
	keywords varchar(400) CHARACTER SET utf8 not null,
	meta_description text CHARACTER SET utf8 not null,
	add_in_sitemap ENUM('Y', 'N') not null default 'Y',
	template varchar(100) CHARACTER SET utf8 not null,
	immagine varchar(200) CHARACTER SET utf8 not null,
	id_order INT UNSIGNED NOT NULL
)engine=innodb;

insert into categories (title) values ("-- root --");

create table pages (
	id_page INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	attivo char(1) not null default 'Y', 
	title varchar(300) CHARACTER SET utf8 not null,
	title_en varchar(300) CHARACTER SET utf8 not null,
	description text CHARACTER SET utf8 not null,
	descrizione_breve text CHARACTER SET utf8 not null,
	sottotitolo varchar(255) CHARACTER SET utf8 not null,
	alias varchar(300) CHARACTER SET utf8 not null,
	id_p INT UNSIGNED NOT NULL,
	id_c INT UNSIGNED NOT NULL,
	lft INT UNSIGNED NOT NULL,
	rgt INT UNSIGNED NOT NULL,
	price DECIMAL(12, 4) NOT NULL default 0.0000,
	in_evidenza char(1) not null default 'N',
	in_promozione char(1) not null default 'N',
	prezzo_promozione DECIMAL(10, 2) NOT NULL,
	dal date NOT NULL,
	al date NOT NULL,
	codice varchar(100) CHARACTER SET utf8 not null,
	peso DECIMAL(10, 2) NOT NULL,
	codice_alfa varchar(32) CHARACTER SET utf8 not null,
	principale ENUM('Y', 'N') not null default 'Y',
	keywords varchar(400) CHARACTER SET utf8 not null,
	meta_description text CHARACTER SET utf8 not null,
	add_in_sitemap ENUM('Y', 'N') not null default 'Y',
	gruppi varchar(300) CHARACTER SET utf8 not null,
	immagine varchar(300) CHARACTER SET utf8 not null,
	immagine_2 varchar(255) CHARACTER SET utf8 not null,
	template varchar(100) CHARACTER SET utf8 not null,
	use_editor ENUM('Y', 'N') not null default 'Y',
	data_news date NULL,
	url varchar(255) CHARACTER SET utf8 not null default "",
	
	css text CHARACTER SET utf8 not null default "",
	
	data_masterspeed varchar(100) CHARACTER SET utf8 not null default "",
	data_transition varchar(100) CHARACTER SET utf8 not null default "",
	
	id_iva int(11) UNSIGNED not null default 0,
	id_marchio int(11) UNSIGNED not null default 0,
	
	link_id_page INT UNSIGNED NOT NULL default 0,
	link_id_c INT UNSIGNED NOT NULL default 0,
	video_thumb varchar(255) not null default '',
	video text CHARACTER SET utf8 not null,
	codice_nazione char(2) not null default '',
	coordinate varchar(100) CHARACTER SET utf8 not null,
	
	id_order INT UNSIGNED NOT NULL
)engine=innodb;

create table prodotti_correlati (
	id_c INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_page int(11) UNSIGNED not null,
	id_corr int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index corr_indx(id_corr),
	index page_indx(id_page),
	foreign key corr_fky(id_corr) references pages (id_page),
	foreign key pages_fky(id_page) references pages (id_page),
	unique (id_page,id_corr)
)engine=innodb;

create table pages_link (
	id_page_link INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_page int(11) UNSIGNED not null,
	titolo varchar(255) not null default '',
	url_link varchar(255) not null default '',
	id_order INT UNSIGNED NOT NULL
)engine=innodb;

create table immagini (
	id_immagine INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	immagine varchar(300) CHARACTER SET utf8 not null,
	id_page INT UNSIGNED NOT NULL,
	id_order INT UNSIGNED NOT NULL
)engine=innodb;

create table menu (
	id_m INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	attivo char(1) not null default 'Y',
	active_link char(1) not null default 'Y',
	title varchar(300) CHARACTER SET utf8 not null,
	alias varchar(300) CHARACTER SET utf8 not null,
	link_alias varchar(300) CHARACTER SET utf8 not null,
	link_to char(10) CHARACTER SET utf8 not null,
	id_p INT UNSIGNED NOT NULL,
	id_c INT UNSIGNED NOT NULL,
	id_page INT UNSIGNED NOT NULL,
	lft INT UNSIGNED NOT NULL,
	rgt INT UNSIGNED NOT NULL,
	id_order INT UNSIGNED NOT NULL
);

insert into menu (title) values ("-- Radice --");

create table menu_sec (
	id_m INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	attivo char(1) not null default 'Y',
	active_link char(1) not null default 'Y',
	title varchar(300) CHARACTER SET utf8 not null,
	alias varchar(300) CHARACTER SET utf8 not null,
	link_alias varchar(300) CHARACTER SET utf8 not null,
	link_to char(10) CHARACTER SET utf8 not null,
	id_p INT UNSIGNED NOT NULL,
	id_c INT UNSIGNED NOT NULL,
	id_page INT UNSIGNED NOT NULL,
	lft INT UNSIGNED NOT NULL,
	rgt INT UNSIGNED NOT NULL,
	id_order INT UNSIGNED NOT NULL
);

insert into menu_sec (title) values ("-- root --");

create table cart (
	id_cart INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	cart_uid CHAR(32) NOT NULL,
	id_page INT UNSIGNED NOT NULL,
	id_c INT UNSIGNED NOT NULL,
	attributi text CHARACTER SET utf8 not null,
	quantity INT UNSIGNED NOT NULL,
	creation_time INT UNSIGNED NOT NULL,
	price DECIMAL(12, 4) NOT NULL default 0.0000,
	in_promozione char(1) not null default 'N',
	prezzo_intero DECIMAL(12, 4) NOT NULL default 0.0000,
	codice varchar(100) CHARACTER SET utf8 not null,
	title varchar(300) CHARACTER SET utf8 not null,
	immagine varchar(100) CHARACTER SET utf8 not null,
	peso DECIMAL(10, 2) NOT NULL,
	json_sconti text null,
	id_iva int(11) UNSIGNED not null default 0,
	iva DECIMAL(10, 2) NOT NULL default 0.00,
	id_order INT UNSIGNED NOT NULL
);

create table wishlist (
	id_wishlist INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	wishlist_uid CHAR(32) NOT NULL,
	id_page INT UNSIGNED NOT NULL,
	creation_time INT UNSIGNED NOT NULL,
	id_order INT UNSIGNED NOT NULL
);

create table orders (
	id_o INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	cart_uid CHAR(32) NOT NULL,
	admin_token CHAR(32) NOT NULL,
	banca_token CHAR(32) NOT NULL,
	txn_id CHAR(32) NOT NULL,
	nome varchar(200) CHARACTER SET utf8 not null,
	cognome varchar(200) CHARACTER SET utf8 not null,
	ragione_sociale varchar(200) CHARACTER SET utf8 not null,
	p_iva varchar(200) CHARACTER SET utf8 not null,
	codice_fiscale varchar(200) CHARACTER SET utf8 not null,
	indirizzo varchar(200) CHARACTER SET utf8 not null,
	cap varchar(200) CHARACTER SET utf8 not null,
	provincia varchar(200) CHARACTER SET utf8 not null,
	citta varchar(200) CHARACTER SET utf8 not null,
	telefono varchar(200) CHARACTER SET utf8 not null,
	email varchar(200) CHARACTER SET utf8 not null,
	pagamento varchar(200) CHARACTER SET utf8 not null,
	accetto varchar(200) CHARACTER SET utf8 not null,
	tipo_cliente varchar(200) CHARACTER SET utf8 not null,
	indirizzo_spedizione text CHARACTER SET utf8 not null,
	descrizione_acquisto text CHARACTER SET utf8 not null,
	stato VARCHAR(30) NOT NULL,
	creation_time INT UNSIGNED NOT NULL,
	subtotal DECIMAL(10, 2) NOT NULL,
	spedizione DECIMAL(10, 2) NOT NULL,
	registrato char(1) not null default 'N',
	iva DECIMAL(10, 2) NOT NULL,
	total DECIMAL(10, 2) NOT NULL,
	prezzo_scontato DECIMAL(10, 2) NOT NULL,
	codice_promozione CHAR(32) NOT NULL,
	nome_promozione varchar(200) CHARACTER SET utf8 not null,
	usata_promozione char(1) not null default 'N',
	peso DECIMAL(10, 2) NOT NULL,
	id_user INT UNSIGNED NOT NULL default 0,
	
	indirizzo_spedizione varchar(200) CHARACTER SET utf8 not null,
	cap_spedizione char(10) CHARACTER SET utf8 not null,
	provincia_spedizione varchar(200) CHARACTER SET utf8 not null,
	nazione_spedizione varchar(200) CHARACTER SET utf8 not null,
	citta_spedizione varchar(200) CHARACTER SET utf8 not null,
	telefono_spedizione varchar(200) CHARACTER SET utf8 not null,
	
	aggiungi_nuovo_indirizzo ENUM('Y', 'N') not null default 'N',
	id_spedizione INT UNSIGNED NOT NULL,
	id_corriere INT UNSIGNED NOT NULL,
	id_order INT UNSIGNED NOT NULL
)engine=innodb;

create table righe (
	id_r INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	cart_uid CHAR(32) NOT NULL,
	id_page INT UNSIGNED NOT NULL,
	id_c INT UNSIGNED NOT NULL,
	attributi text CHARACTER SET utf8 not null,
	quantity INT UNSIGNED NOT NULL,
	creation_time INT UNSIGNED NOT NULL,
	price DECIMAL(12, 4) NOT NULL default 0.0000,
	in_promozione char(1) not null default 'N',
	prezzo_intero DECIMAL(12, 4) NOT NULL default 0.0000,
	codice varchar(100) CHARACTER SET utf8 not null,
	title varchar(300) CHARACTER SET utf8 not null,
	immagine varchar(100) CHARACTER SET utf8 not null,
	peso DECIMAL(10, 2) NOT NULL,
	id_o INT UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL,
	id_iva int(11) UNSIGNED not null default 0,
	iva DECIMAL(10, 2) NOT NULL default 0.00,
	json_sconti text null
)engine=innodb;

create table promozioni (
	id_p INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	attivo char(1) not null default 'Y',	
	dal date NOT NULL,
	al date NOT NULL,
	sconto smallint UNSIGNED NOT NULL default 0,
	titolo varchar(200) CHARACTER SET utf8 not null,
	codice CHAR(32) NOT NULL,
	numero_utilizzi int not null default 1,
	id_order INT UNSIGNED NOT NULL
);

create table regusers (
	id_user INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(80) binary NOT NULL,
	password CHAR(40) binary NOT NULL,
	last_failure INT UNSIGNED NOT NULL,
	has_confirmed INT UNSIGNED NOT NULL,
	ha_confermato INT UNSIGNED NOT NULL DEFAULT 1,
	`confirmation_token` char(32) NOT NULL,
	`creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`creation_time` int(10) unsigned NOT NULL,
	`temp_field` char(32) NOT NULL,
	`deleted` char(4) NOT NULL DEFAULT 'no',
	`forgot_token` char(32) NOT NULL,
	`forgot_time` int(10) unsigned NOT NULL,
	
	nome varchar(200) CHARACTER SET utf8 not null,
	cognome varchar(200) CHARACTER SET utf8 not null,
	ragione_sociale varchar(200) CHARACTER SET utf8 not null,
	p_iva varchar(200) CHARACTER SET utf8 not null,
	codice_fiscale varchar(200) CHARACTER SET utf8 not null,
	indirizzo varchar(200) CHARACTER SET utf8 not null,
	cap varchar(200) CHARACTER SET utf8 not null,
	provincia varchar(200) CHARACTER SET utf8 not null,
	citta varchar(200) CHARACTER SET utf8 not null,
	telefono varchar(200) CHARACTER SET utf8 not null,
	email varchar(200) CHARACTER SET utf8 not null,
	tipo_cliente varchar(200) CHARACTER SET utf8 not null,
	accetto varchar(200) CHARACTER SET utf8 not null,
	indirizzo_spedizione text CHARACTER SET utf8 not null,
	
	id_classe INT UNSIGNED NOT NULL,
	
	unique(username),
	index(username, password)
)engine=innodb;


create table reggroups (
	id_group INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(30) binary NOT NULL,
	unique(name)
)engine=innodb;

create table regusers_groups (
	id_ug INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_user int(11) UNSIGNED not null,
	id_group int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index group_indx(id_group),
	index user_indx(id_user),
	foreign key group_fky(id_group) references reggroups (id_group),
	foreign key user_fky(id_user) references regusers (id_user),
	unique (id_group,id_user)
)engine=innodb;

insert into reggroups (name) values ('gruppo_1');
insert into reggroups (name) values ('gruppo_2');

CREATE TABLE regsessions (
	uid CHAR(32) NOT NULL,
	token CHAR(32) NOT NULL,
	id_user INT UNSIGNED NOT NULL,
	creation_date INT UNSIGNED NOT NULL,
	user_agent CHAR(32) NOT NULL,
   INDEX(uid)
)engine=innodb;


create table regaccesses (
	id int(12) not null auto_increment primary key,
	ip char(20) not null,data char(10) not null,
	ora char(8) not null,
	username varchar(30) not null
);

create table reggroups_categories (
	id_gc INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_c int(11) UNSIGNED not null,
	id_group int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index group_indx(id_group),
	index cat_indx(id_c),
	foreign key group_fky(id_group) references reggroups (id_group),
	foreign key cat_fky(id_c) references categories (id_c),
	unique (id_group,id_c)
)engine=innodb;

create table news (
	id_n INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(300) CHARACTER SET utf8 not null,
	sotto_titolo varchar(200) CHARACTER SET utf8 not null,
	alias varchar(300) CHARACTER SET utf8 not null,
	immagine varchar(200) CHARACTER SET utf8 not null,
	documento varchar(200) CHARACTER SET utf8 not null,
	clean_immagine varchar(200) CHARACTER SET utf8 not null,
	clean_documento varchar(200) CHARACTER SET utf8 not null,
	descrizione text CHARACTER SET utf8 not null,
	attivo char(1) not null default 'Y',
	data_news date NOT NULL,
	keywords varchar(400) CHARACTER SET utf8 not null,
	meta_description text CHARACTER SET utf8 not null,
	id_order INT UNSIGNED NOT NULL
);

create table attributi (
	id_a INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(200) CHARACTER SET utf8 not null,
	immagine varchar(200) CHARACTER SET utf8 not null,
	id_order INT UNSIGNED NOT NULL
)engine=innodb;

create table attributi_valori (
	id_av INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_a INT UNSIGNED NOT NULL default 0,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(200) CHARACTER SET utf8 not null,
	id_order INT UNSIGNED NOT NULL
)engine=innodb;

create table pages_attributi (
	id_pa INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_page int(11) UNSIGNED not null,
	id_a int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	colonna char(5) not null,
	index attr_indx(id_a),
	index page_indx(id_page),
	foreign key attr_fky(id_a) references attributi (id_a),
	foreign key pages_fky(id_page) references pages (id_page),
	unique (id_page,id_a)
)engine=innodb;

create table combinazioni (
	id_c INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	col_1 int(11) UNSIGNED not null,
	col_2 int(11) UNSIGNED not null,
	col_3 int(11) UNSIGNED not null,
	col_4 int(11) UNSIGNED not null,
	col_5 int(11) UNSIGNED not null,
	col_6 int(11) UNSIGNED not null,
	col_7 int(11) UNSIGNED not null,
	col_8 int(11) UNSIGNED not null,
	id_page INT UNSIGNED NOT NULL default 0,
	immagine varchar(100) CHARACTER SET utf8 not null,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	price DECIMAL(10, 4) NOT NULL,
	codice varchar(100) CHARACTER SET utf8 not null,
	peso DECIMAL(10, 2) NOT NULL,
	id_order INT UNSIGNED NOT NULL
)engine=innodb;

create table fatture (
	id_f INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_o INT UNSIGNED NOT NULL default 0,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	filename varchar(200) CHARACTER SET utf8 not null,
	numero INT UNSIGNED NOT NULL default 0,
	id_order INT UNSIGNED NOT NULL
)engine=innodb;

create table caratteristiche (
	id_car INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(200) CHARACTER SET utf8 not null,
	id_order INT UNSIGNED NOT NULL
)engine=innodb;

create table caratteristiche_valori (
	id_cv INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_car INT UNSIGNED NOT NULL default 0,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(200) CHARACTER SET utf8 not null,
	id_order INT UNSIGNED NOT NULL
)engine=innodb;

create table pages_caratteristiche_valori (
	id_pcv INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_page int(11) UNSIGNED not null,
	id_cv int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index attr_indx(id_cv),
	index page_indx(id_page),
	foreign key val_fky(id_cv) references caratteristiche_valori (id_cv),
	foreign key pages_fky(id_page) references pages (id_page),
	unique (id_page,id_cv)
)engine=innodb;

-- contiene i testi usati per la traduzione dal front-end
create table testi (
	id_t INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	chiave varchar(200) binary CHARACTER SET utf8 not null,
	valore text CHARACTER SET utf8 not null,
	immagine varchar(200) CHARACTER SET utf8 not null,
	lingua char(2) not null default "it",
	alt varchar(200) CHARACTER SET utf8 not null,
	unique (chiave, lingua)
);

create table traduzioni (
	id_t INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	chiave varchar(200) binary CHARACTER SET utf8 not null,
	valore tinytext CHARACTER SET utf8 not null,
	lingua char(2) not null default "it",
	contesto char(12) not null default "front",
	unique (chiave, lingua, contesto)
)engine=innodb;

create table scaglioni (
	id_scaglione INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_page INT UNSIGNED NOT NULL,
	quantita INT UNSIGNED NOT NULL,
	sconto DECIMAL(10, 2) NOT NULL,
	id_order INT UNSIGNED NOT NULL
)engine=innodb;

create table classi_sconto (
	id_classe INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(200) CHARACTER SET utf8 not null,
	sconto DECIMAL(10, 2) NOT NULL,
	id_order INT UNSIGNED NOT NULL
)engine=innodb;

create table classi_sconto_categories (
	id_csc INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_c int(11) UNSIGNED not null,
	id_classe int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index classe_indx(id_classe),
	index cat_indx(id_c),
	foreign key group_fky(id_classe) references classi_sconto (id_classe),
	foreign key cat_fky(id_c) references categories (id_c),
	unique (id_classe,id_c)
)engine=innodb;

create table spedizioni (
	id_spedizione INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	indirizzo_spedizione varchar(200) CHARACTER SET utf8 not null,
	cap_spedizione char(10) CHARACTER SET utf8 not null,
	provincia_spedizione varchar(200) CHARACTER SET utf8 not null,
	nazione_spedizione varchar(200) CHARACTER SET utf8 not null,
	citta_spedizione varchar(200) CHARACTER SET utf8 not null,
	telefono_spedizione varchar(200) CHARACTER SET utf8 not null,
	id_order INT UNSIGNED NOT NULL,
	ultimo_usato ENUM('Y', 'N') not null default 'N',
	id_user INT UNSIGNED NOT NULL
)engine=innodb;

create table corrieri (
	id_corriere INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	titolo varchar(200) CHARACTER SET utf8 null,
	prezzo DECIMAL(10, 2) NULL,
	id_order INT UNSIGNED NOT NULL
)engine=innodb;

create table corrieri_spese (
	id_spesa INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	peso DECIMAL(10, 2) NULL,
	prezzo DECIMAL(10, 2) NULL,
	id_corriere INT UNSIGNED NOT NULL
)engine=innodb;

create table impostazioni (
	id_imp INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	iva char(10) not null default 22,
	iva_inclusa ENUM('Y', 'N') not null default 'N',
	
	usa_smtp ENUM('Y', 'N') not null default 'N',
	smtp_host varchar(200) CHARACTER SET utf8 not null default "",
	smtp_port varchar(200) CHARACTER SET utf8 not null default "",
	smtp_user varchar(200) CHARACTER SET utf8 not null default "",
	smtp_psw varchar(200) CHARACTER SET utf8 not null default "",
	smtp_from varchar(200) CHARACTER SET utf8 not null default "",
	smtp_nome varchar(200) CHARACTER SET utf8 not null default "",
	bcc varchar(200) CHARACTER SET utf8 not null default "",
	
	mail_invio_ordine varchar(200) CHARACTER SET utf8 not null default "",
	mail_invio_conferma_pagamento varchar(200) CHARACTER SET utf8 not null default "",
	nome_sito varchar(200) CHARACTER SET utf8 not null default "",
	
	title_home_page varchar(200) CHARACTER SET utf8 not null default "",
	
	usa_sandbox ENUM('Y', 'N') not null default 'Y',
	paypal_seller varchar(200) CHARACTER SET utf8 not null default "",
	paypal_sandbox_seller varchar(200) CHARACTER SET utf8 not null default "",
	esponi_prezzi_ivati ENUM('Y', 'N') not null default 'N',
	redirect_immediato_a_paypal ENUM('Y', 'N') not null default 'N',
	mailchimp_api_key varchar(250) CHARACTER SET utf8 not null default "",
	mailchimp_list_id varchar(250) CHARACTER SET utf8 not null default "",
	mostra_scritta_iva_inclusa ENUM('Y', 'N') not null default 'N',
	analytics text not null default "",
	manda_mail_fattura_in_automatico ENUM('Y', 'N') not null default 'N',
	
	impostazioni add meta_description text not null default "",
	impostazioni add keywords text not null default ""
	
)engine=innodb;

INSERT INTO `impostazioni` (`id_imp`, `data_creazione`, `iva`) VALUES (NULL, CURRENT_TIMESTAMP, '22');

create table slide_layer (
	id_layer INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 not null default "",
	id_page INT UNSIGNED NOT NULL,
	
	testo text CHARACTER SET utf8 not null default "",
	immagine varchar(255) CHARACTER SET utf8 not null default "",
	
	larghezza_1 varchar(100) CHARACTER SET utf8 not null default "",
	larghezza_2 varchar(100) CHARACTER SET utf8 not null default "",
	larghezza_3 varchar(100) CHARACTER SET utf8 not null default "",
	larghezza_4 varchar(100) CHARACTER SET utf8 not null default "",
	
	x_1 varchar(100) CHARACTER SET utf8 not null default "",
	x_2 varchar(100) CHARACTER SET utf8 not null default "",
	x_3 varchar(100) CHARACTER SET utf8 not null default "",
	x_4 varchar(100) CHARACTER SET utf8 not null default "",
	
	y_1 varchar(100) CHARACTER SET utf8 not null default "",
	y_2 varchar(100) CHARACTER SET utf8 not null default "",
	y_3 varchar(100) CHARACTER SET utf8 not null default "",
	y_4 varchar(100) CHARACTER SET utf8 not null default "",
	
	animazione varchar(100) CHARACTER SET utf8 not null default "",
	url varchar(255) CHARACTER SET utf8 not null default "",
	id_order INT UNSIGNED NOT NULL default 0
	
)engine=innodb;

create table iva (
	id_iva INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo char(100) CHARACTER SET utf8 not null default "",
	valore DECIMAL(10, 2) NOT NULL default 0.00,
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;

create table mail_ordini (
	id_mail INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_o INT UNSIGNED NOT NULL,
	tipo ENUM('F', 'P', 'C', 'A', 'R') not null default 'A' -- Fattura, Pagamento, Chiusura (spedizione), Annullato, Ricevuto
)engine=innodb;

create table contenuti_tradotti (
	id_ct INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	lingua varchar(2) CHARACTER SET utf8 null,
	title varchar(255) CHARACTER SET utf8 null,
	alias varchar(300) CHARACTER SET utf8 null,
	description text CHARACTER SET utf8 null,
	keywords varchar(400) CHARACTER SET utf8 null,
	meta_description text CHARACTER SET utf8 null,
	id_c INT UNSIGNED NOT NULL default 0,
	id_page INT UNSIGNED NOT NULL default 0,
	id_car INT UNSIGNED NOT NULL default 0,
	id_cv INT UNSIGNED NOT NULL default 0,
	url varchar(255) not null default '',
	sottotitolo varchar(255) not null default '',
	titolo text CHARACTER SET utf8 null
);

create table contenuti (
	id_cont INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	lingua char(5) CHARACTER SET utf8 null,
	titolo varchar(255) CHARACTER SET utf8 null,
	descrizione text CHARACTER SET utf8 null,
	immagine_1 varchar(200) CHARACTER SET utf8 null,
	immagine_2 varchar(200) CHARACTER SET utf8 null,
	link_contenuto INT UNSIGNED NOT NULL default 0,
	link_libero varchar(200) CHARACTER SET utf8 null,
	target ENUM('STESSO_TAB', 'NUOVO_TAB') not null default 'STESSO_TAB',
	id_tipo INT UNSIGNED NOT NULL default 0,
	id_c INT UNSIGNED NOT NULL default 0,
	id_page INT UNSIGNED NOT NULL default 0,
	attivo ENUM('Y', 'N') not null default 'Y',
	id_order INT UNSIGNED NOT NULL default 0
);

create table lingue (
	id_lingua INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	codice char(5) CHARACTER SET utf8 null,
	descrizione varchar(255) CHARACTER SET utf8 null,
	id_order INT UNSIGNED NOT NULL default 0,
	principale tinyint not null default 0,
	attiva tinyint not null default 1
);

INSERT INTO `lingue` (`id_lingua`, `data_creazione`, `codice`, `descrizione`, `id_order`) VALUES (NULL, CURRENT_TIMESTAMP, 'it', 'Italiano', '1'), (NULL, CURRENT_TIMESTAMP, 'en', 'Inglese', '2');

create table tipi_contenuto (
	id_tipo INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 null,
	descrizione text CHARACTER SET utf8 null,
	id_order INT UNSIGNED NOT NULL default 0
);

INSERT INTO `tipi_contenuto` (`id_tipo`, `data_creazione`, `titolo`, `id_order`) VALUES (NULL, CURRENT_TIMESTAMP, 'fascia tipo 1', '1'), (NULL, CURRENT_TIMESTAMP, 'fascia tipo 2', '2');

create table marchi (
	id_marchio INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo char(100) CHARACTER SET utf8 not null default "",
	alias varchar(100) CHARACTER SET utf8 not null default "",
	descrizione text CHARACTER SET utf8 not null default "",
	immagine varchar(200) CHARACTER SET utf8 not null,
	id_order INT UNSIGNED NOT NULL default 0
)engine=innodb;

create table promozioni_categorie (
	id_pc INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_p int(11) UNSIGNED not null,
	id_c int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index p_indx(id_p),
	index c_indx(id_c),
	unique (id_p,id_c)
)engine=innodb;

create table promozioni_pages (
	id_pp INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	id_p int(11) UNSIGNED not null,
	id_page int(11) UNSIGNED not null,
	id_order INT UNSIGNED NOT NULL,
	index p_indx(id_p),
	index page_indx(id_page),
	unique (id_p,id_page)
)engine=innodb;

create table variabili (
	id_v INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	chiave varchar(200) CHARACTER SET utf8 not null,
	valore varchar(255) CHARACTER SET utf8 not null,
	unique (chiave)
)engine=innodb;

create table ruoli (
	id_ruolo INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	titolo varchar(255) CHARACTER SET utf8 null,
	id_order INT UNSIGNED NOT NULL default 0
);
