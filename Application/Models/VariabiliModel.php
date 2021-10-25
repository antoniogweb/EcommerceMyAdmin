<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of EcommerceMyAdmin
//
// EcommerceMyAdmin is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// EcommerceMyAdmin is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with EcommerceMyAdmin.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');

class VariabiliModel extends GenericModel {
	
	public static $valori = array();
	
	public static $placeholders = null;
	
	public static $variabili = array(
		"usa_marchi"				=>	"1",
		"db_version"				=>	0,
		"contenuti_in_prodotti"		=>	1,
		"contenuti_in_categorie"	=>	0,
		"fasce_in_prodotti"			=>	0,
		"fasce_in_categorie"		=>	0,
		"scaglioni_in_prodotti"		=>	1,
		"correlati_in_prodotti"		=>	1,
		"caratteristiche_in_prodotti"=>	1,
		"combinazioni_in_prodotti"	=>	1,
		"attiva_personalizzazioni"	=>	0,
		"documenti_in_prodotti"		=>	1,
		"ecommerce_attivo"			=>	1,
		"mostra_link_in_blog"		=>	0,
		"has_child_class"			=>	"menu-item-has-children",
		"attiva_ruoli"				=>	0,
		"in_evidenza_blog"			=>	0,
		"contenuti_in_blog"			=>	0,
		"team_attivo"				=>	1,
		"immagini_in_referenze"		=>	0,
		"nome_cognome_anche_azienda"=>	0,
		"attiva_gruppi_utenti"		=>	1,
		"accessori_in_prodotti"		=>	1,
		"contenuti_in_pagine"		=>	1,
		"fasce_in_pagine"			=>	1,
		"mostra_tipi_documento"		=>	1,
		"download_attivi"			=>	1,
		"attiva_giacenza"			=>	0,
		"usa_tag"					=>	0,
		"shop_in_alias_marchio"		=>	1,
		"reg_expr_file"				=>	"/^[a-zA-Z0-9_\-]+\.(jpg|jpeg|gif|png)$/i",
		"nazione_default"			=>	"IT", // Codice ISO nazione di default
		"referenze_attive"			=>	1,
		"blog_attivo"				=>	1,
		"divisone_breadcrum"		=>	" » ",
		"shop_in_alias_tag"			=>	0,
		"menu_class_prefix"			=>	"",
		"primo_attributo_selezionato"	=>	0,
		"prodotti_per_pagina"		=>	999999,
		"template_attributo"		=>	"",
		"template_personalizzazione"=>	"",
		"usa_https"					=>	0,
		"codice_cron"				=>	"",
		"mostra_fasce_prezzo"		=>	0,
		"estrai_materiali"			=>	0,
		"immagine_2_in_slide"		=>	0,
		"mostra_seconda_immagine_categoria_prodotti"	=>	0,
		"mostra_seconda_immagine_tag"	=>	0,
		"mostra_colore_testo"		=>	0,
		"attiva_gruppi"				=>	0,
		"attiva_gruppi_contenuti"	=>	0,
		"attiva_gruppi_documenti"	=>	0,
		"mostra_descrizione_in_prodotti"=>	1,
		"mostra_pulsanti_ordinamenti"	=>	0,
		"fatture_attive"			=>	1,
		"favicon_url"				=>	"",
		"cifre_decimali"			=>	2,
		"link_cms"					=>	"blog/main",
		"attiva_ip_location"		=>	0,
		"mostra_tipi_fasce"			=>	1,
		"prezzi_ivati_in_carrello"	=>	0,
		"prezzi_ivati_in_prodotti"	=>	0,
		"attiva_tipi_azienda"		=>	0,
		"redirect_permessi"			=>	"checkout", // URL di redirect ammessi dopo login, divisi da ,
		"controlla_p_iva"			=>	0,
		"ecommerce_online"			=>	1,
		"theme_folder"				=>	"",
		"traduzione_frontend"		=>	0,
		"lista_variabili_gestibili"	=>	"ecommerce_online,traduzione_frontend",
		"submenu_class"				=>	"uk-nav uk-nav-default",
		"current_menu_item"			=>	"uk-active",
		"submenu_wrap_open"			=>	'<div class="uk-navbar-dropdown uk-margin-remove ">',
		"submenu_wrap_close"		=>	'</div>',
		"in_link_html_after"		=>	'<span uk-icon="icon: chevron-down; ratio: .75;"></span>',
		"mail_template"				=>	'default',
		"mostra_gestione_testi"		=>	0,
		"mostra_avvisi"				=>	0,
		"breadcrumb_element_open"	=>	"",
		"breadcrumb_element_close"	=>	"",
		"codice_gtm"				=>	"",
		"thumb_ajax_w"				=>	40,
		"thumb_ajax_h"				=>	40,
		"alert_error_class"			=>	"alert",
		"alert_success_class"		=>	"executed",
		"facebook_link"				=>	"",
		"twitter_link"				=>	"",
		"youtube_link"				=>	"",
		"instagram_link"			=>	"",
		"indirizzo_aziendale"		=>	"",
		"telefono_aziendale"		=>	"",
		"numero_in_evidenza"		=>	4,
		"pagamenti_permessi"		=>	"bonifico,paypal",
		"estrai_in_promozione_home"	=>	0,
		"news_per_pagina"			=>	16,
		"email_aziendale"			=>	"",
		"immagine_in_varianti"		=>	0,
		"piattaforma_in_sviluppo"	=>	1,
		"email_sviluppo"			=>	"",
		"classe_variante_radio"		=>	"",
		"insert_account_fields"		=>	"nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,username,accetto,tipo_cliente,nazione,pec,codice_destinatario,dprovincia,telefono_2",
		"account_attiva_conferma_password"	=>	1,
		"account_attiva_conferma_username"	=>	1,
		"insert_account_nominativo_obbligatorio"	=>	1,
		"insert_account_cf_obbligatorio"			=>	1,
		"insert_account_p_iva_obbligatorio"			=>	1,
		"url_elenco_ordini"			=>	"ordini/main",
		"insert_account_indirizzo_obbligatorio"	=>	1,
		"insert_account_citta_obbligatoria"		=>	1,
		"insert_account_telefono_obbligatorio"	=>	1,
		"insert_account_nazione_obbligatoria"	=>	1,
		"insert_account_provincia_obbligatoria"	=>	1,
		"insert_account_cap_obbligatorio"		=>	1,
		"numero_per_pagina_magazzino"	=>	50,
		"numero_per_pagina_pages"	=>	30,
		"attiva_cache_prodotti"		=>	1,
		"check_fatture"				=>	0,
		"debug_get_variable"		=>	"",
		"insert_account_sdi_pec_obbligatorio"	=>	1,
		"conferma_registrazione"	=>	0,
		"ore_durata_link_conferma"	=>	24,
		"main_slide_order"			=>	"pages.id_order desc",
		"salva_conteggio_query"		=>	0,
		"abilita_solo_nazione_navigazione"	=>	1,
		"abilita_blocco_acquisto_diretto"	=>	0,
		"tipo_cliente_default"		=>	"privato",
		"codice_gtm_analytics"		=>	"",
		"codice_gtm_analytics_noscript"	=>	"",
		"codice_fbk"				=>	"",
		"codice_fbk_noscript"		=>	"",
		"debug_js"					=>	0,
		"email_log_errori"			=>	"",
		"mostra_tipo_caratteristica"=>	0,
		"immagine_in_caratteristiche"	=>	0,
		"caratteristiche_in_tab_separate"	=>	0,
		"mostra_faq"				=>	0,
		"mostra_tendina_prodotto_principale"	=>	0,
		"usa_transactions"			=>	1,
		"lingue_abilitate_frontend"	=>	"it",
		"abilita_feedback"			=>	0,
		"abilita_menu_semplice"		=>	0,
		"current_menu_item_link"	=>	"",
		"mostra_testimonial"		=>	0,
		"campo_form_contatti"		=>	"nome,email,messaggio,accetto",
		"curl_curlopt_interface"	=>	"",
		"messenger_link"			=>	"",
		"fascia_contenuto_class"	=>	"",
		"usa_fasce_in_home"			=>	0,
		"numero_news_in_evidenza"	=>	4,
		"solo_utenti_privati"		=>	0,
		"abilita_rich_snippet"		=>	1,
		"abilita_codice_fiscale"	=>	1,
		"mostra_eventi"				=>	0,
		"mostra_gallery"			=>	0,
		"attiva_immagine_sfondo"	=>	0,
		"attiva_tutte_le_categorie"	=>	0,
		"url_elenco_slide"			=>	"slide/main",
		"traduzione_backend"		=>	0,
		"permetti_cambio_lingua"	=>	0,
		"google_plus"				=>	"",
		"numero_eventi_home"		=>	3,
		"menu_link_class"			=>	"link_item",
		"menu_item_class"			=>	"menu-item",
		"submenu_link_class"		=>	"link_item",
		"submenu_item_class"		=>	"menu-item",
		"linkTextWrapTag"			=>	"",
		"linkTextWrapClass"			=>	"",
		"mostra_autore_in_blog"		=>	0,
		"campo_form_newsletter"		=>	"email,accetto",
		"variabili_gestibili_da_fasce"	=>	"",
		"tag_blocco_testo"			=>	"div",
		"campo_captcha_form"		=>	"cognome",
		"riconoscimento_tipo_documento_automatico"	=>	1,
		"eventi_per_pagina"			=>	16,
		"contenuti_in_eventi"		=>	1,
		"permetti_upload_archivio"	=>	0,
		"elimina_archivio_dopo_upload"	=>	1,
		"input_ok_back_color"		=>	"#FFF",
		"attiva_menu_ecommerce"		=>	1,
		"attiva_standard_cms_menu"	=>	1,
		"url_inserisci_slide"		=>	"slide/form/insert/0",
		"default_backend_language"	=>	"it",
		"attiva_categorie_download"	=>	1,
		"attiva_pagina_produttore"	=>	0,
		"vista_promozioni_separata"	=>	0,
		"divisorio_filtri_url"		=>	"--",
		"attiva_filtri_caratteristiche"	=>	0,
		"attiva_filtri_successivi"	=>	0, // Non viene usato, da sviluppare
		"attiva_localizzazione_prodotto"	=>	0,
		"label_nazione_url"			=>	"nazione",
		"label_regione_url"			=>	"regione",
		"alias_fascia_prezzo"		=>	"fascia-prezzo",
		"attiva_formn_contatti"		=>	0,
		"attiva_immagine_in_documenti"	=>	1,
		"attiva_nazione_marchi"		=>	0,
		"attiva_campo_nuovo_in_pagine"	=>	0,
		"attiva_in_evidenza_marchi"	=>	0,
		"attiva_in_evidenza_nazioni"=>	0,
		"alias_stato_prodotto"		=>	"st", // ALIAS in URL per IN EVIDENZA
		"alias_stato_prodotto_nuovo"		=>	"pbl", // ALIAS in URL per NUOVO
		"alias_stato_prodotto_promo"		=>	"pr", // ALIAS in URL per IN OFFERTA
		"valore_tipo_promo"			=>	"In promozione",
		"alias_valore_tipo_promo"	=>	"in-promozione",
		"valore_tipo_nuovo"			=>	"Nuovo",
		"alias_valore_tipo_nuovo"	=>	"nuovo",
		"valore_tipo_in_evidenza"	=>	"In evidenza",
		"alias_valore_tipo_in_evidenza"	=>	"in-evidenza",
		"attiva_nuovo_marchi"		=>	0,
		"usa_descrizione_in_slide"	=>	0,
		"attiva_altre_lingue_documento"	=>	0,
		"mostra_icone"	=>	0,
		"mostra_slide"	=>	1,
		"abilita_traduzioni_documenti"	=>	1,
		"abilita_visibilita_pagine"		=>	0,
		"cerca_lingua_documento_da_nome_file"	=>	1,
		"lingua_default_documenti"	=>	"tutte",
		"estensioni_accettate_documenti"	=>	"pdf,png,jpg,jpeg",
		"nuova_modalita_caratteristiche"	=>	1,
		"attiva_tipologie_caratteristiche"	=>	1,
		"attiva_spedizione"			=>	1,
		"lingua_default_frontend"	=>	"it",
		"abilita_tutte_le_lingue_attive"	=>	0,
		"applicativo_traduzioni"	=>	"",
		"immagine_2_in_pagine"		=>	0,
		"piattaforma_di_demo"		=>	0,
		"configurazione_frontend_attiva"	=>	0,
		"immagine_2_in_team"		=>	0,
		"attiva_classi_sconto"		=>	1,
		"campi_impostazioni"		=>	"",
		"permetti_cambio_tema"		=>	0,
		"attiva_help_wizard"		=>	0,
		"url_elenco_prodotti"		=>	"prodotti",
		"attiva_cache_immagini"		=>	0,
		"permessi_cartella_cache_immagini"	=>	777,
		"url_elenco_clienti"		=>	"regusers",
		"permetti_acquisto_anonimo"	=>	1,
		"hook_ordine_confermato"	=>	"",
		"attiva_campo_classe_personalizzata_menu"	=>	0,
		"attiva_gestione_pagamenti"	=>	0,
		"hook_update_ordine"		=>	"",
		"codice_fiscale_aziendale"	=>	"",
		"partita_iva_aziendale"		=>	"",
		"hook_set_placeholder"		=>	"",
		"placeholder_prodotti_o_servizi"	=>	"",
		"responsabile_trattamento_dati"	=>	"",
		"email_responsabile_trattamento_dati"	=>	"",
		"recupera_dati_carrello_da_post"	=>	0,
		"salva_contatti_in_db"		=>	1,
		"attiva_sezione_contatti"	=>	1,
		"attiva_marketing"			=>	0,
		"mostra_codice_in_carrello"	=>	1,
		"carrello_monoprodotto"		=>	0,
		"abilita_log_piattaforma"	=>	0,
		"tempo_log_ore"				=>	24,
		"attiva_modali"				=>	0,
		"mostra_gestione_antispam"	=>	0,
		"dimensioni_upload_documenti"	=>	3000000,
		"attiva_accessibilita_categorie"	=>	0,
		"attiva_multi_categoria"	=>	0,
		"attiva_template_email"		=>	0,
		"attiva_eventi_retargeting"	=>	0,
		"token_schedulazione"		=>	"",
	);
	
	public static function setPlaceholders()
	{
		self::$placeholders = array(
			"INDIRIZZO"			=>	v("indirizzo_aziendale"),
			"NOME AZIENDA"		=>	Parametri::$nomeNegozio,
			"CODICE FISCALE"	=>	v("codice_fiscale_aziendale"),
			"PARTITA IVA"		=>	v("partita_iva_aziendale"),
			"INDIRIZZO SITO WEB"	=>	DOMAIN_NAME,
			"PRODOTTI/SERVIZI"	=>	v("placeholder_prodotti_o_servizi"),
			"EMAIL AZIENDALE"	=>	v("email_aziendale"),
			"NOMINATIVO RESPONSABILE TRATTAMENTO DATI"	=>	v("responsabile_trattamento_dati"),
			"EMAIL RESPONSABILE TRATTAMENTO DATI"		=>	v("email_responsabile_trattamento_dati"),
		);
		
		if (v("hook_set_placeholder") && function_exists(v("hook_set_placeholder")))
			self::$placeholders = callFunction(v("hook_set_placeholder"), self::$placeholders, v("hook_set_placeholder"));
		
		return self::$placeholders;
	}
	
	public function __construct() {
		$this->_tables='variabili';
		$this->_idFields='id_v';
		
		$this->_lang = 'It';
		
		parent::__construct();
	}
	
	public function opzioniSiNo()
	{
		return array(
			"1"	=>	"Sì",
			"0"	=>	"No",
		);
	}
	
	public function strutturaForm()
	{
		$formFields = array(
			'usa_marchi'	=>	array(
				'labelString'	=>	'Attiva marchi',
				'type'			=>	'Select',
				'options'	=>	$this->opzioniSiNo(),
				"reverse"	=>	"yes",
			),
			'traduzione_frontend'	=>	array(
				'labelString'	=>	'Permetti la modifica delle traduzioni dal frontend',
				'type'			=>	'Select',
				'options'	=>	$this->opzioniSiNo(),
				"reverse"	=>	"yes",
			),
			'ecommerce_online'	=>	array(
				'labelString'	=>	"Ecommerce online (permetti l'acquisto)",
				'type'			=>	'Select',
				'options'	=>	$this->opzioniSiNo(),
				"reverse"	=>	"yes",
			),
			'codice_gtm_analytics'	=>	array(
				'labelString'	=>	"Codice analytics/GTM",
				'type'			=>	'Textarea',
			),
			'codice_gtm_analytics_noscript'	=>	array(
				'labelString'	=>	"Codice analytics/GTM (noscript)",
				'type'			=>	'Textarea',
			),
			'codice_fbk'	=>	array(
				'labelString'	=>	"Codice pixel Facebook",
				'type'			=>	'Textarea',
			),
			'codice_fbk_noscript'	=>	array(
				'labelString'	=>	"Codice pixel Facebook (noscript)",
				'type'			=>	'Textarea',
			),
			'piattaforma_in_sviluppo'	=>	array(
				'labelString'	=>	'Ecommerce in sviluppo / blocca indicizzazione',
				'type'			=>	'Select',
				'options'	=>	$this->opzioniSiNo(),
				"reverse"	=>	"yes",
			),
		);
		
		return $formFields;
	}
	
	public static function setValore($variabile, $valore)
	{
		$v = new VariabiliModel();
		
		$idV = $v->clear()->where(array(
			"chiave"	=>	sanitizeDb($variabile)
		))->field("id_v");
		
		if ($idV)
		{
			$v->setValues(array(
				"valore"	=>	$valore,
			));
			
			return $v->update((int)$idV);
		}
		
		return true;
	}
	
	public static function migrazioni()
	{
		$var = new VariabiliModel();
		
		foreach (self::$variabili as $k => $v)
		{
			if (!isset(self::$valori[$k]))
			{
				$var->setValues(array(
					"chiave"	=>	$k,
					"valore"	=>	$v,
				),"sanitizeDb");
				
				$var->insert();
			}
		}
	}
	
	public static function valore($chiave)
	{
		if (!isset(self::$valori[$chiave]))
		{
			self::migrazioni();
			self::ottieniVariabili();
		}
		
		if (!isset(self::$valori[$chiave]))
			die("Attenzione, chiave $chiave inesistente!");
		
		return self::$valori[$chiave];
	}
	
	public static function ottieniVariabili()
	{
		$var = new VariabiliModel();
		
		$values = $var->clear()->toList("chiave", "valore")->send();
		
		self::$valori = $values;
	}
	
	public static function getNotifiche()
	{
		$notifiche = array();
		
		$files = scandir(ROOT."/DB/Migrazioni", SCANDIR_SORT_DESCENDING);
		$ultimaMigrazione = $files[0];
		$migrationNum = (int)basename($ultimaMigrazione, '.sql');
		
		if ($migrationNum > (int)v("db_version"))
			$notifiche[] = array(
				"testo"	=>	gtext("Attenzione, aggiorna il database!"),
				"link"	=>	Url::getRoot()."cron/migrazioni/".v("codice_cron"),
				"icona"	=>	"fa-warning",
				"class"	=>	"text-yellow",
			);
		
		return $notifiche;
	}
}
