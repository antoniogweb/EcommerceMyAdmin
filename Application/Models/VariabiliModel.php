<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2025  Antonio Gallo (info@laboratoriolibero.com)
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
	
	public static $usatiCookieTerzi = false;
	
	public static $strutturaFormCampiAggiuntivi = array();
	
	public static $variabiliGestibiliTramiteQueryString = array(
		"attiva_giacenza",
		"forza_giacenza_massima_in_feed",
	);
	
	public static $variabiliCodiciCookieTerzi = array(
		"codice_gtm" => "",
		"codice_gtm_analytics" => "",
		"codice_gtm_analytics_noscript" => "",
		"codice_fbk" => "",
		"codice_fbk_noscript" => "",
		"codice_js_ok_cookie" => "",
		"salva_satistiche_visualizzazione_pagina_su_file" => "",
	);
	
	public static $variabili = array(
		"usa_marchi"				=>	"1",
		"db_version"				=>	0,
		"contenuti_in_prodotti"		=>	1,
		"contenuti_in_categorie"	=>	0,
		"fasce_in_prodotti"			=>	0,
		"scaglioni_in_prodotti"		=>	1,
		"correlati_in_prodotti"		=>	1,
		"caratteristiche_in_prodotti"=>	1,
		"attiva_personalizzazioni"	=>	0,
		"ecommerce_attivo"			=>	1,
		"mostra_link_in_blog"		=>	0,
		"attiva_ruoli"				=>	0,
		"in_evidenza_blog"			=>	0,
		"contenuti_in_blog"			=>	0,
		"team_attivo"				=>	1,
		"immagini_in_referenze"		=>	0,
		"nome_cognome_anche_azienda"=>	0,
		"accessori_in_prodotti"		=>	1,
		"contenuti_in_pagine"		=>	1, // se sono visibili oppure no i contenuti nelle pagine
		"immagini_in_pagine"		=>	0, // se sono visibili oppure no le immagini nelle pagine
		"fasce_in_pagine"			=>	1,
		"download_attivi"			=>	1,
		"usa_tag"					=>	0, // se attivare i tag
		"tag_in_blog"				=>	0, // se attivare i tag nella sezione blog
		"tag_in_prodotti"			=>	1, // se attivare i tag nella sezione prodotti
		"shop_in_alias_marchio"		=>	1,
		"reg_expr_file"				=>	"/^[a-zA-Z0-9_\-]+\.(jpg|jpeg|gif|png)$/i",
		"referenze_attive"			=>	1,
		"blog_attivo"				=>	1,
		"shop_in_alias_tag"			=>	0,
		"menu_class_prefix"			=>	"",
		"template_attributo"		=>	"",
		"template_personalizzazione"=>	"",
		"usa_https"					=>	0,
		"codice_cron"				=>	"",
		"estrai_materiali"			=>	0,
		"mostra_seconda_immagine_categoria_prodotti"	=>	0,
		"mostra_seconda_immagine_tag"	=>	0,
		"mostra_colore_testo"		=>	0,
		"mostra_descrizione_in_prodotti"=>	1,
		"mostra_pulsanti_ordinamenti"	=>	0,
		"favicon_url"				=>	"",
		"cifre_decimali"			=>	2, // usate nel calcolo dei totali
		"cifre_decimali_visualizzate"	=>	8, // usate nella visualizzazione delle cifre in admin e nell'import
		"link_cms"					=>	"blog/main",
		"attiva_ip_location"		=>	0,
		"mostra_tipi_fasce"			=>	1,
		"attiva_tipi_azienda"		=>	0,
		"redirect_permessi"			=>	"checkout", // URL di redirect ammessi dopo login, divisi da ,
		"controlla_p_iva"			=>	0,
		"ecommerce_online"			=>	1,
		"traduzione_frontend"		=>	0,
		"lista_variabili_gestibili"	=>	"ecommerce_online,traduzione_frontend",
		"lista_variabili_funzionamento_ecommerce"	=>	"ecommerce_online,piattaforma_in_sviluppo,traduzione_frontend,giacenza_massima_mostrata",
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
		"linkedin_link"				=>	"",
		"pinterest_link"			=>	"",
		"indirizzo_aziendale"		=>	"",
		"telefono_aziendale"		=>	"",
		"telefono_aziendale_2"		=>	"",
		"email_aziendale"			=>	"",
		"piattaforma_in_sviluppo"	=>	1,
		"email_sviluppo"			=>	"",
		"insert_account_fields"		=>	"nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,username,accetto,tipo_cliente,nazione,pec,codice_destinatario,dprovincia,telefono_2",
		"account_attiva_conferma_password"	=>	1,
		"account_attiva_conferma_username"	=>	1,
		"insert_account_nominativo_obbligatorio"	=>	1,
		"insert_account_cf_obbligatorio"			=>	1,
		"insert_account_p_iva_obbligatorio"			=>	1,
		"insert_account_indirizzo_obbligatorio"	=>	1,
		"insert_account_citta_obbligatoria"		=>	1,
		"insert_account_telefono_obbligatorio"	=>	1,
		"insert_account_nazione_obbligatoria"	=>	1,
		"insert_account_provincia_obbligatoria"	=>	1,
		"insert_account_cap_obbligatorio"		=>	1,
		"insert_ordine_telefono_obbligatorio"	=>	1,
		"debug_get_variable"		=>	"",
		"debug_retargeting_get_variable"		=>	"",
		"insert_account_sdi_pec_obbligatorio"	=>	1,
		"conferma_registrazione"	=>	0,
		"ore_durata_link_conferma"	=>	24,
		"main_slide_order"			=>	"pages.id_order desc",
		"salva_conteggio_query"		=>	0,
		"abilita_blocco_acquisto_diretto"	=>	0,
		"tipo_cliente_default"		=>	"privato",
		"codice_gtm"				=>	"",
		"codice_gtm_analytics"		=>	"",
		"codice_gtm_analytics_noscript"	=>	"",
		"codice_fbk"				=>	"",
		"codice_verifica_fbk"		=>	"",
		"codice_fbk_noscript"		=>	"",
		"debug_js"					=>	0,
		"mostra_tipo_caratteristica"=>	0,
		"immagine_in_caratteristiche"	=>	0,
		"caratteristiche_in_tab_separate"	=>	0,
		"mostra_tendina_prodotto_principale"	=>	0,
		"usa_transactions"			=>	1,
		"abilita_menu_semplice"		=>	0,
		"current_menu_item_link"	=>	"",
		"mostra_testimonial"		=>	0,
		"campo_form_contatti"		=>	"nome,email,messaggio,accetto",
		"campo_form_contatti_obbligatori"		=>	"",
		"curl_curlopt_interface"	=>	"",
		"messenger_link"			=>	"",
		"fascia_contenuto_class"	=>	"",
		"numero_news_in_evidenza"	=>	4,
		"solo_utenti_privati"		=>	0,
		"abilita_rich_snippet"		=>	1,
		"abilita_codice_fiscale"	=>	1,
		"controlla_codice_fiscale"	=>	1, // se impostare il check sul codice fiscale al checkout
		"mostra_eventi"				=>	0,
		"mostra_servizi"			=>	0, // Attiva la sezione servizi
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
		"variabili_gestibili_da_fasce"	=>	"",
		"tag_blocco_testo"			=>	"div",
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
		"attiva_nazione_marchi"		=>	0,
		"attiva_campo_nuovo_in_pagine"	=>	0,
		"attiva_in_evidenza_marchi"	=>	0,
		"attiva_in_evidenza_nazioni"=>	0,
		"attiva_in_evidenza_slide"	=>	0,
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
		"mostra_icone"	=>	0,
		"mostra_slide"	=>	1,
		"abilita_visibilita_pagine"		=>	0,
		"nuova_modalita_caratteristiche"	=>	1,
		"attiva_tipologie_caratteristiche"	=>	1,
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
		"url_elenco_clienti"		=>	"regusers",
		"permetti_acquisto_anonimo"	=>	1,
		"attiva_campo_classe_personalizzata_menu"	=>	0,
		"attiva_gestione_pagamenti"	=>	0,
		"codice_fiscale_aziendale"	=>	"",
		"partita_iva_aziendale"		=>	"",
		"placeholder_prodotti_o_servizi"	=>	"",
		"responsabile_trattamento_dati"	=>	"",
		"email_responsabile_trattamento_dati"	=>	"",
		"salva_contatti_in_db"		=>	1,
		"attiva_sezione_contatti"	=>	1,
		"attiva_marketing"			=>	0,
		"mostra_codice_in_carrello"	=>	1,
		"carrello_monoprodotto"		=>	0,
		"abilita_log_piattaforma"	=>	0,
		"tempo_log_ore"				=>	240,
		"attiva_modali"				=>	0,
		"mostra_gestione_antispam"	=>	0,
		"estensioni_upload_immagini_testi"	=>	'png,jpg,jpeg,gif,svg', // estensioni ammesse nell'upload di immagini nella tabella testi
		"dimensioni_upload_contenuti"	=>	6000000, // dimensione massima degli upload nei contenuti
		"dimensioni_upload_file_generici"	=>	10000000, // dimensione massima degli upload nei file generici (nell'area di testo)
		"estensioni_upload_file_contenuti"	=>	"pdf", // estensioni ammesse nell'upload dei file dei contenuti
		"attiva_accessibilita_categorie"	=>	0,
		"attiva_multi_categoria"	=>	0,
		"attiva_template_email"		=>	0,
		"attiva_eventi_retargeting"	=>	0,
		"token_schedulazione"		=>	"",
		"token_recupera_carrello"	=>	"", // token per accedere alla pagina di recupero del carrello
		"attiva_note_acquisto_in_ordini"	=>	1,
		"genera_e_invia_password"	=>	0,
		"page_main_class"			=>	"top_page_main",
		"attiva_azioni_ajax"		=>	0,
		"checkout_solo_loggato"		=>	0, // costringe ad eseguire il login per poter andare al checkout
		"stile_form_login"			=>	"stile_1_pp_base",
		"email_debug_retargeting"	=>	"",
		"attiva_campo_test_in_pagine"	=>	0, // permette di avere dei prodotti di test non elencati (neanche nella sitemap)
		"attiva_menu_db"			=>	0, // menù in admin da db
		"url_redirect_dopo_login"	=>	"area-riservata", // url di redirect dopo il login nel frontend
		"mostra_impostazioni_smtp"	=>	1, // Se mostra o nasconde le impostazioni della posta in impostazioni, in admin
		"attiva_titolo_2_valori_caratteristiche"	=>	0, // descrizione aggiuntiva caratteristica
		"manda_mail_avvenuto_pagamento_al_cliente"	=>	1, // se mandare la mail di avvenuto pagamento al cliente, dopo l'ordine
		"attiva_elementi_tema"		=>	0, // se permettere in admin o frontend di cambiare lo stile dei vari elementi del tema
		"attiva_codice_js_pagina"	=>	1, // codice conversione JS della pagina (anche JS generico)
		"token_edit_frontend"		=>	"", // token per attivare l'edit frontend
		"mostra_errori_personalizzazione"	=>	1, // mostrare che manca personalizzazione oppure no
		"coupon_ajax"				=>	0, // se inserire il coupon con una richiesta POST ajax
		"attiva_gestione_integrazioni"	=>	0, // se mostra la gestione delle integrazioni
		"resoconto_ordine_top_carrello"	=>	0, // se mostrare, in mobile, il resoconto in alto al checkout
		"profondita_menu_desktop"	=>	2, // profondità menù desktop
		"profondita_menu_mobile"	=>	2, // profondità menù mobile
		"appiattisci_menu_semplice"	=>	0, // se impostato a 1, vengono eliminati i livelli del menù e viene messo tutto a profondità 1
		"attiva_feed_solo_se_con_token"	=>	0, // mostra feed google facebook solo se con token
		"token_feed_google_facebook"	=>	"", // token del feed
		"attiva_descrizione_2_in_prodotti"	=>	0, // attiva campo descrizione 2 nei prodotti
		"attiva_descrizione_3_in_prodotti"	=>	0, // attiva campo descrizione 3 nei prodotti
		"attiva_descrizione_4_in_prodotti"	=>	0, // attiva campo descrizione 4 nei prodotti
		"attiva_margine_in_prodotti"		=>	0, // attiva oppure no il campo margine nei prodotti e nelle categorie
		"scaglioni_margine_di_euro"		=>	10, // indica lo scaglione in euro del margine assoluto da mostrare nelle etichette del feed di google
		"mostra_pagina_intermedia_paypal"	=>	0, // mostra la pagina di atterraggio intermedia prima di andare su paypal
		"scaglioni_cpc_euro_centesimi"		=>	10, // indica lo scaglione in centesimi di euro del CPC da mostrare nelle etichette del feed di google
		"scaglioni_margine_di_guadagno"		=>	10, // indica lo scaglione in euro del guadagno assoluto previsto da mostrare nelle etichette del feed di google
		"rapporto_dollaro_euro"				=>	1.13, // proporzione euro dollaro
		"filtra_fasce_per_tema"=>	0, // se attivo, mostra solo le fasce del tema impostato (altrimenti mostra tutte le fasce indipendentemente dal tema)
		"codice_fiscale_obbligatorio_solo_se_fattura"	=>	0, // per privati, il CF è obbligatorio solo se il cliente spunta il campo "fattura"
		"pixel_nel_footer"			=>	1, // sposta il pixel di Facebook nel footer
		"pixel_set_time_out"			=>	3000, // secondi dopo i quali attivare il pixel
		"euro_iva_italiana_vendite_ue"	=>	10000, // totale euro massimo per vendite fuori dall'italia con IVA italiana
		"no_tag_descrizione_feed"	=>	0, // non usare i tag nel campo descrizione del feed
		"elimina_emoticons_da_feed"	=>	1, // togli le emoticons dal feed
		"attiva_reggroups_tipi"		=>	0, // attiva la possibilità di legare i gruppi ai tipi di contenuti in automatico
		"marchio_rich_snippet"		=>	"", // il marchio del negozio, da usare solo se è un produttore diretto e non usa i marchi
		"gruppi_inseriti_da_approvare_alla_registrazione"		=>	"", // alla registrazione, i seguenti gruppi temporanei verranno aggiunti, saranno da approvare (inserire gli id dei gruppi divisi da virgola)
		"checkbox_css_path"			=>	"admin/Frontend/Public/Css/skins/minimal/minimal.css",
		"prodotto_tutte_regioni_se_nessuna_regione"	=>	0, // nella ricerca di un prodotto per regione, se non ha alcuna regione/nazione allora compare in tutte le regioni/nazioni
		"codice_js_ok_cookie"	=>	"", // codice JS da scrivere nel footer solo dopo aver accettato i cookie ti tracciamento
		"salva_satistiche_visualizzazione_pagina"	=>	0, // salva le statistiche di visualizzazione della singola pagina
		"salva_satistiche_visualizzazione_pagina_su_file"	=>	"", // salva le statistiche di visualizzazione della singola pagina o della categoria su FILE
		"salva_ip_visualizzazione"	=>	0, // se impostato su 1, salva anche l'IP dell'utente che sta guardando la pagina
		"pannello_statistiche_attivo"	=>	0, // mostra sezione statistiche
		"mostra_gestione_newsletter"	=>	1, // attiva la sezione gestione newsletter in admin
		"classe_ext_cookies"		=>	"segnalazione_cookies_ext", // classe box esterno cookie principale
		"classe_ext_cookies_conf"	=>	"segnalazione_cookies_ext uk-background-secondary uk-light segnalazione_cookies_ext_pag_cookies", // classe box esterno cookie preferenze
		"cookies_preferenze_button"	=>	"cookie_personalizza uk-margin-top uk-width-1-1 uk-width-2-5@s uk-button uk-button-default", // classe pulsante preferenze cookies
		"cookies_confirm_button"	=>	"uk-margin-top uk-width-1-1 uk-width-2-5@s ok_cookies cookie_accetta uk-button uk-button-primary", // classe pulsante ok cookie
		"cookies_save_pref"			=>	"uk-margin-top uk-button uk-button-default uk-width-1-1 uk-width-2-5@s", // classe pulsante cookie in set preferenze
		"informativa_privacy_in_pagina_cookie"	=>	1, // se 1, il testo della pagina privacy verrà mostrayo all'interno della pagina dei cookie
		"durata_carrello_wishlist_coupon"	=>	31536000, // durata dei cookie usati per carrello, wishlist e copupon
		"durata_impostazioni_cookie"	=>	15552000, // durata dei cookie usati per le preferenze sui cookie
		"durata_statistiche_cookie"	=>	31536000, // durata dei cookie usati per le statistiche di visualizzazione delle pagine
		"permetti_generazione_pdf_pagine_frontend"	=>	1, // permetti la generazione PDF delle pagine nel frontend
		"permetti_generazione_pdf_pagine_backend"	=>	1, // permetti la generazione PDF delle pagine nel backend
		"permetti_generazione_json_pagine_backend"	=>	1, // permetti la generazione JSON delle pagine nel backend
		"var_query_string_id_rif"	=>	"id_rif", // id a cui si riferisce la pagina in questione (ex id prodotto per i feedback)
		"var_query_string_id_comb"	=>	"id_comb", // id a cui si riferisce la combinazione in questione (ex id combinazione per i feedback)
		"attiva_prodotti_piu_venduti"	=>	1, // se mostrare o no la sezione dei prodotti più venduti
		"permetti_eliminazione_account"	=>	1, // se attivo, gli utenti possono eliminare l'account in autonomia
		"attiva_modifica_massiva_codici"	=>	0, // permetti la modifica massiva dei codici (solo singola pagina)
		"mostra_soci"	=>	0, // se mostrare la sezione "soci"
		"mostra_progetti"	=>	0, // se mostrare la sezione "progetti"
		"mostra_alimenti"	=>	0, // se mostrare la sezione "alimenti"
		"mostra_ricette"	=>	0, // se mostrare la sezione "ricette"
		"mostra_storia"	=>	0, // se mostrare la sezione "storia"
		"mostra_approfondimenti"	=>	0, // se mostrare la sezione "approfondimenti"
		"mostra_partner"	=>	0, // se mostrare la sezione "partner"
		"mostra_menu_cucina"	=>	0, // se mostrare la sezione "menù cucina"
		## immagini ##
		"rielabora_width"	=>	3000, // larghezza massima entro cui viene rielaborata l'immagine dopo l'upload
		"rielabora_height"	=>	3000, // altezza massima entro cui viene rielaborata l'immagine dopo l'upload
		## SEDI ##
		"mostra_sedi"	=>	0, // se mostrare la sezione "sedi"
		"attiva_categorie_sedi"	=>	0, // se attivare o disattivare le categorie delle sedi
		## GALLERY ##
		"mostra_gallery"			=>	0, // Attiva la sezione gallery
		"mostra_immagini_in_gallery"			=>	0, // Se attivare la scheda immagini nella gallery
		"attiva_video_in_gallery"			=>	0, // Se attivare il campo video in gallery
		"attiva_categoria_in_gallery"		=>	0, // Se attivare la categoria nella gallery
		## MODULO CONTATTI ##
// 		"redirect_pagina_dopo_invio_se_prodotto"	=>	0, // se attivo, l'utente viene reindirizzato alla pagina del prodotto dopo l'invio
		"testo_errori_form"			=>	"Si prega di controllare i campi evidenziati", // testo quando ci sono errori nel form contatti
		"oggetto_form_contatti"		=>	"form richiesta informazioni", // oggetto della mail del form contatti
		"oggetto_form_newsletter"	=>	"form iscrizione a newsletter", // oggetto della mail del form newsletter
		"oggetto_mail_conferma_contatto"	=>	"conferma la tua mail", // oggetto della mail che chiede di confermare il proprio indirizzo email
		"fragment_form_contatti"	=>	"contatti-form", // all'invio del form contatti verrà aggiunto un fragment # con quel valore
		"fragment_form_newsletter"	=>	"newsletter-form", // all'invio del form newsletter verrà aggiunto un fragment # con quel valore
		"contatti_ajax_submit"	=>	0, // se impostato a 1, l'invio del form contatti sarà in modalità AJAX
		"newsletter_ajax_submit"	=>	0, // se impostato a 1, l'invio del form newsletter sarà in modalità AJAX
		"invia_subito_mail_contatto"=>	1, // se inviare subito il contatto
		"invia_mail_contatto_a_piattaforma"	=>	1, // se inviare la mail di contatto anche alla piattaforma o solo ai referenti di nazione (se non ci sono referenti manda la mail alla piattaforma)
		## FEEDBACK ##
		"abilita_feedback"			=>	0, // se i feedback sono abilitati o meno
		"permetti_aggiunta_feedback"	=>	0, // permette oppure no l'aggiunta dei feedback da parte dei clienti
		"feedback_solo_se_loggato"	=>	0, // può aggiungere un feedback solo chi è loggato
		"feedback_permetti_di_editare_nome_se_loggato"	=>	1, // permette all'utente che lascia la recensione di editare il nome se loggato (altrimenti usa il nome con cui è registrato)
		"feedback_visualizza_in_area_riservata"	=>	1, // se attivo, l'utente può vedere tutti i propri feedback nell'area riservata
		"feedback_max_per_prodotto"	=>	1, // numero massimo di feedback per prodotto per utente (ha effetto solo se feedback_solo_se_loggato = 1)
		"feedback_ajax_submit"	=>	0, // se impostato a 1, l'invio del form feedback sarà in modalità AJAX
		## PORTALE ##
		"permetti_agli_utenti_di_aggiungere_pagine"	=>	0, // se impostato a 1, gli utenti potranno aggiungere pagine
		## AREA RISERVATA ##
		"attiva_area_riservata"	=>	1, // se impostato a 1, gli utenti potranno creare un account, eseguire il login, modificare i propri dati
		"attiva_gestione_immagine_utente"	=>	0, // se impostato ad 1, permette la modifica dell'immagine dell'utente in autonomia (dall'area riservata)
		"nome_cartella_immagine_utente"	=>	"utenti", // nome della cartella dove salvare le immagini di profilo degli utenti
		"permetti_registrazione"	=>	1, // se impostato su 0, non permette la registrazione
		"permetti_modifica_account"	=>	1, // se impostato su 0, l'utente non registrato non può modificare i dati dell'account (si usa quando esiste un gestionale collegato e i dato vengono importati dal gestionale)
		## CONTATTI ##
		"attiva_verifica_contatti"	=>	0, // manda oppure no la mail di conferma della mail del contatto
		"tempo_conferma_uid_contatto"	=>	86400, // tempo in secondi per confermare il contatto
		"tempo_durata_uid_contatto"	=>	15552000, // durata in secondi del cookie del contatto
		"fonti_contatti_da_mostrare_admin"	=>	"NEWSLETTER,FORM_CONTATTO,NEWSLETTER_DA_ORDINE,NEWSLETTER_DA_REGISTRAZIONE", // elenco delle fonti contatti da mostrare nella sezione contatti generica dell'admin
		## PROMO ##
		"considera_promo_in_margine_euro"	=>	0, // se togliere i soldi dello sconto nel margine calcolato per il feed di google
		"attiva_promo_sconto_assoluto"	=>	0, // se impostato su 1, permette di impostare uno sconto assoluto
		"attiva_gift_card"		=>	0, // se impostato a 1, attiva i prodotti Gift card
		"numero_massimo_gift_card"	=>	8, // numero massimo di gift card in un carrello
		"permetti_di_disattivare_promo_al_carrello"	=>	1, // se sì, attiva la possibilità di disattivare la promo applicata al carrello
		"sconti_combinazioni_automatiche"	=>	0, // se impostato ad 1, va ad impostare in automatico gli sconti sulla tabella delle combinazioni
		"gestisci_sconti_combinazioni_separatamente"	=>	0, // se impostato ad 1, permette di impostare un prezzo scontatto per ogni combinazione
		"estrai_in_promozione_home"	=>	0,
		"estrai_in_evidenza_home"	=>	1, // se estrarre i prodotti in evidenza sempre
		"permetti_promozione_assoluta_prodotto"	=>	0, // se impostato su 1, permette sconti assoluti al prodotto
		"non_conteggiare_ordini_annullati"	=>	0, // se impostato a 1, non conteggiare gli ordini annullati per il conteggio delle promo, sia come numero che come totale spedo (pert le assolute)
		"attiva_filtro_marchi_su_promo"	=>	0, // se impostato su 1, attiva il filtro marchi nelle promo in percentuale
		"gift_card_validita_nazionale"	=>	0, // se impostato su 1, le GIFT CARD avranno validità solo nella nazione di acquisto o della lista associata
		## CARRELLO ##
		"mostra_piu_meno_modifica_quantita"	=>	1, // se mostra icone + o - o se input libero di tipo number
		"mostra_pulsante_modifica_se_ha_combinazioni"	=>	1, // in carrello, permette di andare al prodotto con la combinazione già selezionata
		## STRUTTURA URL E COMBINAZIONI ##
		"estensione_url_categorie"	=>	".html", // cosa aggiungere come estensione nell'URL delle categorie
		"aggiungi_marchio_in_url_prodotto"		=>	1, // se impostato a 1 il marchio apparirà nell'URL del prodotto
		"mostra_categorie_in_url_prodotto"		=>	1, // se impostato a 1  l'albero delle categorie verrà mostrato nell'URL prima del prodotto
		"mantieni_alias_sezione_in_url_prodotti"	=>	1, // se impostato a 1 mantiene l'alias della sezione negli URL del prodotto (prodotti, shop, ...)
		"usa_codice_combinazione_in_url_prodotto"	=>	0, // se impostato a 1 aggiunge il codice della combinazione nell'URL del prodotto
		"usa_alias_combinazione_in_url_prodotto"	=>	0, // se impostato a 1 aggiunge il l'alias della combinazione nell'URL del prodotto
		"token_aggiorna_alias_combinazioni"		=>	"", // token per andare a rigenerare tutti gli alias delle combinazioni
		"aggiorna_pagina_al_cambio_combinazione_in_prodotto"	=>	0, // se impostato su 1, quando nel dettaglio prodotto si cambia la combinazione, l'utente viene rediretto nell'URL della nuova variante
		"immagine_in_varianti"		=>	0, // se settato a 1, mostra la colonna immagini nell'elenco delle combinazionipagina della nuova combinazione
		"immagini_separate_per_variante"		=>	0, // se settato a 1, possibilità di caricare più immagini per ogni variante
		"combinazioni_in_prodotti"	=>	1, // attiva le varianti
		"aggiorna_combinazioni_automaticamente"	=>	0, // se impostato ad 1, aggiorna le combinazioni non appena vengono cambiate le varianti
		"permetti_di_selezionare_estensione_url_pagine"	=>	0, // se impostato a 1, permette la scelta tra l'estensione .html e /
		"numero_massimo_varianti_per_prodotto"	=>	3, // numero massimo di varianti per prodotto
		"tabelle_con_possibile_alias_duplicato"	=>	"", // comma separated list of tables that can have duplicate alias
		"cerca_la_pagina_dal_codice"	=>	0, // se impostato su 1, il sistema non cerca l'ALIAS esatto ma cerca il codice nell'URL e cerca la pagina per il codice
		"carattere_divisione_parole_permalink"	=>	"-", // carattere usato per creare il permalink
		"categoria_in_permalink_pagina"	=>	0, // se impostato ad 1, per le nuove pagine viene aggiunta la categoria nel permalink
		"marchio_prima_della_categoria_in_url"	=>	1, // se importato ad 1 il marchio viene posizionato prima dell categoria in URL (altrimenti dopo)
		"alias_pagina_duplicata"		=>	"questa-pagina-e-duplicata", // alias impostato nelle pagine duplicate. Se viene trovata questo testo all'update della pagina, l'ALIAS viene ricreato
		## PAGINA DETTAGLIO PRODOTTO ##
		"estrai_sempre_correlati"	=>	1, // se impostato ad 1, estrae sempre i correlati, anche se non è la pagina di un prodotto
		"aggiuni_a_correlati_prodotti_stessa_categoria"	=>	0, // se impostato su 1, aggiunge ai correlati manuali anche i prodotti della stessa categoria
		"numero_massimo_prodotti_correlati_visti_da_altri_visitatori"	=>	0, // se maggiore di 0, aggiunge ai correlati manuali anche i prodotti visti da altri visitatori
		"numero_massimo_correlati_stessa_categoria"	=>	10, // se maggiore di 0, indica il numero massimo di correlati ad un prodotto della stessa categoria
		"fragmento_dettaglio_prodotto"		=>	"prodotto_container", // l'ID del div che contiene il dettaglio del prodotto
		"attiva_tendina_caricamento"		=>	0, // Se mostrare la tendina di caricamento
		"numero_massimo_comprati_assieme"	=>	0, // se maggiore di 0, indica il numero massimo di spesso comprati assieme. Se il tema lo predispone verranno mostrati nel dettaglio del prodotto
		"estrai_le_caratteristiche"	=>	1, // se impostato su 1, estrae le caratteristiche nella pagina del prodotto
		"estrai_i_documenti"		=>	1, // se impostato su 1, estrai i documenti nella pagina del prodotto
		"estrai_filtri_su_dettaglio_pagina"	=>	1, // se impostato su 1, ricalcola i filtri che servono l'archivio anche sul settaglio prodotto
		## GOOGLE E FACEBOOK ##
		"usa_sku_come_id_item"	=>	0, // se impostato a 1, utilizza il codice, altrimenti utilizza l'ID
		## NAZIONI ##
		"nazione_default"			=>	"IT", // Codice ISO nazione di default
		"abilita_solo_nazione_navigazione"	=>	1, // permetti al checkout o come nazioni permesse, solo la nazione di navigazione o quella di default
		"attiva_nazione_nell_url"	=>	0, // se impostato a 1 nell'URL verrà aggiunto il codice della nazione
		"imposta_la_nazione_di_default_a_quella_nell_url"	=>	0, // se impostayto a 1, al checkout e al carrello imposta come nazione di default quella indicata nell'URL
		"mostra_prezzi_con_aliquota_estera"	=>	0, // mostra i prezzi con l'IVA derlla nazione che si sta visualizzando
		## VENDITE ESTERO ##
		"scorpora_iva_prezzo_estero"	=>	1, // se impostato su 1, scorpora l'IVA nelle vendite fuori la nazione di default (solo per prodotti)
		"scorpora_iva_prezzo_estero_azienda"	=>	1, // se impostato su 1, scorpora l'IVA nelle vendite fuori la nazione di default (solo per prodotti) per le aziende anche se scorpora_iva_prezzo_estero = 0
		"scorpora_iva_prezzo_estero_spedizione_pagamenti"	=>	1, // se impostato su 1, scorpora l'IVA nelle vendite fuori la nazione di default o per IVA diversa da default anche per spedizioni e pagamenti
		"permetti_pagamento_contrassegno_fuori_nazione_default"	=>	0, // se impostato su 1, permette il pagamento con contrassegno fuori dalla nazione di default
		"forza_commercio_indiretto"	=>	1, // se impostata a 1, considera sempre l'acquisto come commercio INDIRETTO (beni fisici)
		"forza_aliquota_iva_b2c"	=>	0, // se impostato a 1, nel calcolo dell'aliquota IVA considera sempre come se fosse un B2C
		## FASCE PREZZO ##
		"mostra_fasce_prezzo"		=>	0, // se impostato su 1 attiva la gestione delle fasce di prezzo in admin e attiva il filtro per fascia prezzo nel frontend
		"filtro_prezzo_slider"		=>	0, // se impostato su 1 attiva il filtro per range di prezzo
		## CACHE DB ##
		"numero_massimo_file_cache"	=>	50000, // numero massimo di file in cache
		"attiva_cache_prodotti"		=>	1, // cache dei prodotti in admin
		"attiva_cache_immagini"		=>	0, // cache delle immagini
		"permessi_cartella_cache_immagini"	=>	777, // permessi della cartella di cache delle immagini
		"query_cache_durata_massima"	=>	60, // tempo di durata massima della cache
		"query_cache_pulisci_ogni_x_minuti"	=>	70, // minuti dopo i quali pulisce la cache
		"query_cache_usa_periodi_random"=>	0, // se suddivide il periodo di cache in sottoperiodi e ne sceglie uno random
		"query_cache_minuti_tra_periodi"=>	5, // numero di minuti tra un periodo di cache e l'altro
		## CACHE HTML ##
		"numero_massimo_file_cache_html"	=>	10000, // numero massimo di file in cache
		## CACHE METODI ##
		"numero_massimo_file_cache_metodi"	=>	10000, // numero massimo di file in cache
		## GIACENZA / MAGAZZINO ##
		"attiva_giacenza"			=>	0,	// se considerare la giacenza per la messa nel carrello
		"attiva_campo_giacenza"		=>	0,	// se permette comunque di gestire la giacenza (anche se attiva_giacenza = 0)
		"giacenza_massima_mostrata"	=>	100, // massima giacenza mostrata in frontend
		"scala_giacenza_ad_ordine"	=>	1, // se deve scalare la giacenza di un prodotto quando questo viene ordinato (GIFT card escluse)
		"mostra_link_storico_movimentazioni"	=>	0, // se impostato su 1, mostra il link per vedere lo storico delle movimentazioni
		"mostra_filtri_varianti_in_magazzino"	=>	1, // mostra o nascondi i filtri delle varianti nel magazzino
		"mostra_filtro_ricerca_libera_in_magazzino"	=>	0, // filtro ricerca libera
		"testo_disponibilita_immediata"	=>	"", // se impostato, testo che appare nel carrello quando la disponibilità è immediata
		"testo_disponibilita_non_immediata"	=>	"", // se impostato, testo che appare nel carrello quando la disponibilità non è immediata
		## RICERCA ##
		"ricerca_termini_and_or"	=>	" AND", // se deve cercare i termini della frase di ricerca in AND o in OR
		## VARIANTI ##
		"classe_variante_radio"		=>	"",
		"attiva_variante_colore"	=>	0, // se attiva oppure no la variante di tipo COLORE
		"mostra_solo_varianti_articolo"	=>	0, // se impostato su 1, nella pagina del prodotto elenca solo le varianti del prodotto
		"permetti_modifica_attributi_combinazioni"	=>	0, // se impostato su 1, permette di modificare il valore degli attributi per ogni combinazione
		"permetti_acquisto_da_categoria_se_ha_una_combinazione"	=>	0, // se impostato su 1, l'ecommerce permette di acquistare direttamente dalla categoria se il prodotto non ha personalizzazioni e ha solo 1 categoria attiva
		"campo_attributi_di_default"	=>	"", // viene usato come testo del campo attributi della tabella cart se il prodotto non ha attributi
		"slega_varianti_quando_copi_prodotto"	=>	0, // se impostato su 1, alla copia del prodotto le varianti vengono copiate
		"primo_attributo_selezionato"	=>	0, // se deve impostare di default il primo attributo
		"mostra_nome_variante_in_tendina"	=>	1, // se impostato su 1, mostra il nome della variante nella tendina di selezione della variante
		"mostra_prezzo_su_tendina_combinazione"	=>	0, // se impostato su 1, mostra il prezzo nella tendina di selezione della variante
		## GOOGLE ##
		"campo_send_to_google_ads"	=>	"", // è il campo send_to del codice di conversione Google Ads
		"codice_account_merchant"	=>	"", // è il codice dell'account Merchant collegato a Google Ads
		"lista_variabili_opzioni_google"	=>	"codice_gtm_analytics,codice_gtm,codice_gtm_analytics_noscript,codice_account_merchant,campo_send_to_google_ads,codice_fbk,codice_fbk_noscript,codice_verifica_fbk,identificatore_feed_default",
		"attiva_strumenti_merchant_google"	=>	0, // attiva campi per il feed google (e facebook)
		"url_codici_categorie_google"	=>	"https://www.google.com/basepages/producttype/taxonomy-with-ids.it-IT.txt", // url codici categorie google (per importazione)
		"versione_google_analytics"	=>	3, // versione di Google Analytics
		"mostra_campo_stampa_gtin_in_prodotto"	=>	0, // se impostato ad 1, viene mostrata la tendina per forzare la disabilitazione del GTIN nel feed Google o Facebook
		## ETICHETTE FEED ##
		"identificatore_feed_default"	=>	"no", // per il feed di google (gtin, mpm)
		"categorie_google_tendina"	=>	1, // se le categorie di google mostrarle come tendina o come campo di testo
		"aggiungi_dettagli_prodotto_al_feed"=>	0, // solo per il feed Google, se attivo aggiunge le caratteristiche del prodotto al feed
		"aggiungi_dettagli_spedizione_al_feed"=>	0, // solo per il feed Google, se attivo aggiunge le spese di spedizione al feed
		"numero_parole_feed_iniziali_prodotto"	=>	2, // quante parole usare nell'etichetta personalizzate delle iniziali prodotto
		"forza_giacenza_massima_in_feed"	=>	0, // se impostato su 1, imposta la disponibilità al valore massimo tra le varianti, se il feed non è diviso per varianti (quindi se ha un prodotto canonical di riferimento che rappresenta tutte le varianti nel feed)
		## LOGIN ESTERNI ##
		"abilita_login_tramite_app"	=>	0, // se impostato a 1 permette il login tramite le app attive
		"token_eliminazione_account_da_app"	=>	"", // verrà utilizzato come accesso per l'eliminazione dell'utente da app esterna (Facebook, ...)
		"path_instagram_media_json_file"	=>	"", // path assoluto dove salvare il file json con i media scaricati da instagram
		## UTENTI ##
		"elimina_account_ad_ordine_se_parcheggiato"	=>	0, // se impostato ad 1, all'ordine il sistema controlla che non sia un account bloccante e in caso lo cancella
		"permetti_sempre_eliminazione_account_backend"=>	0, // se impostato a 1, utilizza deleteAccount se non può eliminare il cliente
		"elimina_record_utente_ad_autoeliminazione"	=>	1, // se impostato a 1, elimina il record. Altrimenti va a sovrascirverlo con dati random
		"variabile_token_eliminazione"	=>	"token_del", // nome della variaibile che contiene il token dell'eliminazione
		"attiva_clienti"		=>	1, // se i clienti sono attivi o no
		"sistema_maiuscole_clienti"	=>	0, // se impostato  a 1, Nome e Cognome verranno forzati con la prima lettera maiuscola mentre codice fiscale tutto in maiuscolo
		"attiva_clienti_nazioni"	=>	0, // attiva la tab per gestire i clienti nella nazione
		"aggiorna_sempre_i_dati_del_cliente_al_checkout"	=>	0, // se impostato su 1, il sistema va sempre ad aggiornare i dati del cliente con i dati del checkout
		"utilizza_ricerca_ajax_su_select_2_clienti"		=>	0, // se impostato su 1, la tendina dei cliente carica i dati tramite AJAX
		"attiva_regione_su_cliente"	=>	0, // se impostato ad 1, attiva il campo "id_regione" nella scheda cliente (lato admin)
		"permetti_di_loggarti_come_utente"	=>	0, // se impostato su 1, dal pannello admin, per ogni cliente ci sarà un pulsante per navigare il sito come se foste loggato come quel cliente
		"token_login_come_utente"	=>	"", // token per forzare il login come un determinato cliente
		## LISTE REGALO ##
		"attiva_liste_regalo"	=>	0, // se impostato a 0, permetti la creazione e la gestione di liste regalo
		"nome_cookie_id_lista"	=>	"id_lista_regalo", // nome dell cookie che conterrà l'ID della lista regalo
		"tempo_durata_cookie_id_lista"	=>	31536000, // durata in secondi del cookie contenente l'ID della lista
		"alias_pagina_lista"	=>	"lista-regalo", // alias usato in URL per la pagina delle liste
		"numero_massimo_tentativi_invio_link"	=>	3, // numero massimo di tentativi di invio link per ogni elemento della tabella liste_regalo_link
		## ORDINI ##
		"permetti_ordini_offline"	=>	0, // se impostato ad 1 permette la creazione di ordini dal backend
		"url_elenco_ordini"			=>	"ordini/main", // controller/action della sezione ordini
		"permetti_modifica_cliente_in_ordine"	=>	0, // se impostato ad 1, permette di modificare il cliente nell'ordine
		"attiva_gestione_stati_ordine"	=>	0, // se impostato a 1 si attiva la possibilità di gestire gli stati dell'ordine
		"salva_ip"					=>	0, // se impostato su 1, salva l'IP dell'utente
		"numero_massimo_ordini_giornalieri_stesso_ip"		=>	0, // se impostato su 0, non esiste limite
		"attiva_righe_generiche_in_ordine_offline"	=>	0, // se impostato ad 1 permette di aggiungere le righe generiche prese dalla tabella righe_tipologie (deve essere anche impostato un prodotto genrico)
		"crea_sincronizza_cliente_in_ordini_offline"	=>	1, // se impostato su 1, alla creazione di un ordine offline il cliente viene creato in automatico se non è stato selezionato
		"disattiva_costo_spedizione_ordini_offline"	=>	0, // se impostato a 1, il costo della spedizione viene sempre messo a 0 euro per ordini OFFLINE
		"disattiva_costo_pagamento_ordini_offline"	=>	0, // se impostato a 1, il costo del pagamento viene sempre messo a 0 euro per ordini OFFLINE
		"imposta_allo_stato_se_tutte_righe_sono_evase"	=> "", // se impostato al codice di uno stato (ex completed), imposta a quello stato se tutte le righe sono state segnate come evase
		"imposta_allo_stato_se_non_tutte_righe_sono_evase"	=>	"", // se impostato al codice di uno stato (ex pending), imposta a quello stato se non tutte le righe sono state segnate come evase
		"attiva_da_consegna_in_ordine"	=>	0, // se impostato su 1, solo in admin attiva il campo data_consegna e il rispettivo filtro
		"check_accesso_admin_token_ordine_frontend_da"	=>	0, // per tutti gli ordini maggiorni di check_accesso_admin_token_ordine_frontend_da, controlla anche admin_token
		"attiva_gestione_commessi"		=>	0, // se impostato su 1, attiva la gestione dei commessi nell'ordine
		"function_pdf_ordine"			=>	"", // funzione per generare il PDF dell'ordine. Se lasciato vuoto, utilizza le funzioni standard dell'ecommerce
		"oggetto_ordine_ricevuto"	=>	"Ordine N° [ID_ORDINE]",
		"oggetto_ordine_pagato"	=>	"Conferma pagamento ordine N° [ID_ORDINE]",
		"oggetto_ordine_spedito"	=>	"Ordine N° [ID_ORDINE] spedito e chiuso",
		"oggetto_ordine_annullato"	=>	"Annullamento ordine N° [ID_ORDINE]",
		"oggetto_pdf_ordine"		=>	"Ordine [ID_ORDINE] - stampa PDF", // Oggetto mail del PDF ordine offline
		"filename_pdf_ordine"		=>	"Ordine_[ID_ORDINE]", // Nome del file del PDF dell'ordine allegato alla mail
		"funzione_sanitize_spedizione_in_ordine"	=>	"sanitizeHtmlLight", // forza htmlspecialchars sui dati dell'indirizzo utente nel checkout ordine con ENT_QUOTES (sanitizeHtmlLight) o ENT_COMPAT (sanitizeHtmlLightCompat)
		"mostra_sezione_righe_ordine"	=>	0, // se impostato su 1, mostra la sezione con l'elenco delle righe ordine
		"mostra_sempre_stato_closed"	=>	0, // se impostato su 1, mostra lo stato closed anche se attiva_spedizione = 0
		"permetti_al_cliente_di_annullare_ordine"	=>	0, // se impostato su 1 il cliente può annullare l'ordine in autonomia dalla pagina dell'ordine
		"mostra_data_annullamento_se_presente"	=>	0, // se impostato a 1, mostra la data annullamento nel dettaglio dell'ordine
		"permetti_annullare_data_pagamento_e_annullamento"	=>	0, // se impostato a 1, permette di annullare le date di pagamento e annullamento nello stesso giorno
		"mostra_date_pagamento_annullamento_in_elenco"	=>	0, // se impostato a 1, mostra le date di pagamento e annullamento nell'elenco ordini
		## CHECKOUT E RESOCONTO ORDINE ##
		"classi_titoli_checkout"	=>	"uk-margin-bottom uk-text-emphasis uk-text-large", // classi usate nei titoli delle varie sezioni al checkout
		"classi_icona_checkout"		=>	"", // classi usate nelle icone al checkout
		"classi_titoli_checkout_spedizione"	=>	"uk-margin-bottom uk-text-emphasis uk-text-large", // classi usate nei titoli della sezione spedizioni al checkout
		"classi_titoli_resoconto_ordine"	=>	"uk-heading-bullet", // classi usate nei titoli delle varie sezioni del resoconto ordine
		"attiva_coupon_checkout"	=>	1, // se mostrare il form di inserimento coupon al checkout
		"mostra_doppio_pulsante_acquista_mobile"	=>	1, // se mostrare il doppio pulsante di acquista in mobile
		"pagina_di_autenticazione"	=>	0, // attiva la pagina di autenticazione
		"mostra_modalita_spedizione_in_resoconto"	=>	1, // se mostrare la modalità di spedizione nel resoconto dell'ordine
		"nascondi_ordini_pending_in_admin"	=>	0, // se impostato ad 1 gli ordini in stato pending saranno nascosti di default dalla lista degli ordini
		"stati_ordine_da_nascondere_in_admin"	=>	"pending", // stati ordine da nascondere dei default in admin (solo se nascondi_ordini_pending_in_admin = 1)
		"stati_ordine_editabile_ed_eliminabile"	=>	"pending", // stati nei quali le righe dell'ordine sono editabili e l'ordine può essere eliminato
		## SPEDIZIONE ##
		"attiva_spedizione_area_riservata"	=>	1, // se mostrare o nascondere i link della spedizione in area riservata
		"attiva_spedizione"			=>	1, // se mostrare oppure no la spedizione in carrello
		"soglia_spedizione_gratuita_attiva_in_tutte_le_nazioni"	=>	1, // se impostato ad 1, la soglia oltre alla quale la spedizione è gratuita vale in tutto il mondo, altrimenti vale solo nella nazione di default (variabile nazione_default)
		"soglia_spedizioni_gratuite_diversa_per_ogni_nazione"	=>	0, // se impostata a 1, la soglia sopra la quale la spedizione è gratuita non viene più impostata sotto Preferenze > Impostazioni ma nel dettaglio della singola nazione
		"mostra_solo_province_attive"	=>	0, // se impostato su 1, mostra solo le province attive
		## CORRIERI ##
		"scegli_il_corriere_dalla_categoria_dei_prodotti"	=>	0, // se impostato ad 1, il corriere verrà scelto in funzione della categoria del prodotto (se viene trovata un'associazione)
		"attiva_campo_ritiro_in_sede_su_corrieri"	=>	0, // se impostato ad 1, permette di selezionare se un corriere è un ritiro in sede
		"lega_lo_stato_ordine_a_corriere"	=>	0, // se impostato ad 1, sarà possibile impostare lo stato a cui impostare l'ordine dopo il pagamento per ogni corriere 
		"attiva_gestione_spedizionieri"	=>	0, // se impostato ad 1, permette la gestione degli spedizionieri (GLS, BRT) e di poterli selezionare nell'ordine
		"attiva_gestione_spedizioni"	=>	0, // se impostato ad 1, permette la gestione delle spedizioni e degli spedizionieri dal backend
		"url_webservice_gls"			=>	"https://labelservice.gls-italy.com/ilswebservice.asmx", // usato per inviare la spedizione GLS server to server (SOAP)
		"url_tracking_gls"				=>	"https://infoweb.gls-italy.com", // usato per richiedere lo stato della spedizione GLS server to server (REST)
		"url_rest_api_brt"				=>	"https://api.brt.it/rest/v1", // usato per inviare la spedizione BRT server to server (REST)
		"url_tracking_brt"				=>	"https://api.brt.it/rest/v1/tracking/parcelID", // usato per richiedere lo stato della spedizione BRT server to server (REST)
		"minuti_attesa_bordero_brt"		=>	5,
		## IMAGES ##
		"qualita_immagini_jpeg_default"	=>	75, // qualità di default compressione immagini jpeg
		"converti_immagini_in_jpeg"	=>	0, // se impostato ad 1, forza la conversione di tutte le immagini a JPEG al caricamento
		## JAVASCRIPT ##
		"usa_defear"	=>	0, // se impostato a 1, usa defear sui JS nell'header
		"usa_versione_random"	=>	0, // se impostato a 1, metti ?v=rand(1,10000) al caricamento (da usare solo in sviluppo)
		## DDOS ##
		"svuota_ip_ogni_x_ore"		=>	30, // numero di ore dopo le quali svuota gli IP
		"time_ultima_eliminazione_ip"	=>	0, // unix time stamp dell'ultima volta he ha svuotato gli IP
		"attiva_check_ip"			=>	0, // se impostato a 1, limita l'accesso ad una stessa "chiave" oltre un certo limite temporale
		"limite_ip_chiave_contemporanee"	=>	10, // massimo numero di richieste contemporanee dallo stesso IP alla stessa chiave
		"limite_ip_chiave_minuto"	=>	50, // massimo numero di richieste nell'ultimo minuto dallo stesso IP alla stessa chiave
		"limite_ip_chiave_orario"	=>	200, // massimo numero di richieste nell'ultima ora dallo stesso IP alla stessa chiave
		## MAIL ##
		"max_numero_email_ora"		=>	0, // massimo numero di email inviabili ogni ora (0 = no limiti)
		"max_numero_email_giorno"	=>	0, // massimo numero di email inviabili ogni giorno (0 = no limiti)
		## PULSANTI ##
		"classe_pulsanti_submit"	=>	"uk-button uk-button-secondary", // classe dei pulsanti submit
		"classe_pulsanti_carrello"	=>	"uk-button uk-button-default", // classe dei pulsanti carrello (compreso aggiorna carrello)
		## SLIDE ##
		"attiva_tipo_slide"			=>	0, // attiva la tendina "tipo slide"
		"immagine_2_in_slide"		=>	0, // mostra la seconda immagine nella slide
		"immagine_3_in_slide"		=>	0, // mostra la terza immagine nella slide
		## CATEGORIE ##
		"fasce_in_categorie"		=>	0, // mostra il tab per la gestione delle fasce in categorie
		"attiva_mostra_in_menu"		=>	0, // se impostato a 1, permette di gestire il campo mostra_in_menu nelle categorie
		"attiva_categorie_in_prodotto"	=>	0, // se impostato ad 1, attiva la scheda con le categorie secondarie nel prodotto
		"mostra_categorie_figlie_in_griglia_prodotti"	=>	1, // se la navigazione è per sottoblocchi, quindi se una categoria ha delle sottocategorie, mostra le sottocategorie. L'alternativa è una griglia di prodotti esplosi
		"aggiorna_colonna_numero_acquisti_prodotti_ad_ordine_concluso"	=>	0, // se impostato su 1, quando viene aggiornato l'ordine, viene ricalcolata la colonna numero_acquisti_pagina per ogni pagina presente tra le righe dell'ordine
		"attiva_ricerca_documento"	=>	0, // se impostato su 1 permette di cercare anche i documenti all'interno di una categoria
		"estrai_categorie_figlie"	=>	1, // estrae le categorie figlie della categoria
		"estrai_fasce_in_categoria_prodotti"	=>	1, // se estrarre le fasce nelle categorie prodotti
		"attiva_campo_redirect"		=>	0, // se impostato ad 1, attiva un campo redirect per le categorie. La categoria sarà un link all'URL specificato nel campo redirect
		## PAGINE ##
		"attiva_campo_redirect_pagine"		=>	0, // se impostato ad 1, attiva un campo redirect per le pagine. La pagina sarà un link all'URL specificato nel campo redirect
		"attiva_campo_css"			=>	0, // se impostato ad 1 e se previsto dal tema, permette di integrare un CSS personalizzato per ogni pagina
		## CARRELLO ##
		"cart_sticky_top_offeset"	=>	100, // offset dello sticky del cart in desktop
		"recupera_dati_carrello_da_post"	=>	0, // se impostato a 1, salva i dati inviati in post nel carrello (solo se l'utente ha approvato la privacy)
		"classe_css_dimensione_testo_colonne_carrello"	=>	"", // classe da applicare sulle colonne del carrello per la dimensione
		"svuota_file_cookie_carrello_dopo_x_minuti"	=>	0, // se maggiore di 0 (in minuti) salva i file dei cookie in una cartella ed eliminali dopo i minuti impostati
		## ORDINE ##
		"svuota_file_cookie_carrello_in_automatico"	=>	1, // se impostato su 1 (e svuota_file_cookie_carrello_dopo_x_minuti > 0), controlla se eliminare i file dei cookie del carrello in automatico ad ogni refresh (consigliato lasciare 1 per siti con poche visite). Se impostato su 0 bisognerà attivare il comando per eliminare i file in modo schedulato (php carrello.php --azione="elimna-vecchi-file-cookie-carrello")
		"mail_ordine_dopo_pagamento"	=>	0, // manda la mail dell'ordine al cliente solo dopo che è avventuo il pagamento (solo paypal e carta di credito)
		"mail_ordine_dopo_pagamento_anche_per_utente_ospite"	=>	0, // manda la mail dell'ordine al cliente solo dopo che è avventuo il pagamento (solo paypal e carta di credito) anche nel caso di utente ospite (mail_ordine_dopo_pagamento deve essere impostato a 1 o non ha alcun effetto)
		"mail_ordine_dopo_pagamento_negozio"	=>	0, // manda la mail dell'ordine al negozio solo dopo che è avventuo il pagamento (solo paypal e carta di credito)
		"mail_credenziali_dopo_pagamento"	=>	0, // manda la mail con le credenziali solo dopo il pagamento dell'ordine
		"mail_aggiuntive_invio_ordine_negozio"	=>	"", //elenco di email AGGIUNTIVE, divise da virgola, a cui mandare la mail di ordine avvenuto (si tratta della mail che arriva al negozio). L'ordine verrà comunque mandato anche alla mail indicata nelle impostazioni dell'ecommerce.
		"stati_a_cui_permettere_scarido_pdf_ordine"	=>	"", // elenco stati, divisi da virgola, ai quali il cliente può scaricare il PDF dell'ordine
		"attiva_gestione_stati_pending"	=>	0, // attiva la gestione degli stati pending
		## PAGAMENTI ##
		"check_ipn_al_ritorno_carta"	=>	0, // se impostata su 1, fa il check ipn al ritorno sul sito (controlla che non sia già stato fatto). Solo per pagamento con carta di credito
		"setta_lingua_e_nazione__da_ordine_in_pagina_ringraziamento"	=>	0, // se impostato su 1, la pagina di atterraggio dell'ordine verranno settati la lingua e la nazione dell'ordine (sovrascrivendo lingua e nazione dell'URL)
		## PAGINAZIONE ##
		"prodotti_per_pagina"		=>	999999, // FRONTEND
		"news_per_pagina"			=>	16, // FRONTEND
		"eventi_per_pagina"			=>	16, // FRONTEND
		"numero_per_pagina_magazzino"	=>	50, // ADMIN
		"numero_per_pagina_pages"	=>	30, // ADMIN
		"attributi_link_pagina_2_in_poi"	=>	'', // attributi nei link alle pagine dalla 2 in poi nella griglia elementi
		## OPCACHE ##
		"attiva_interfaccia_opcache"	=>	0, // se attivare o meno l'interfaccia opcache
		## GESTIONALI ##
		"attiva_collegamento_gestionali"	=>	0, // se attivare o meno l'interfaccia di gestione e il collegamento con i gestionali
		"mostra_codice_gestionale"	=>	0, // se impostato a 1 e se attiva_collegamento_gestionali = 1, mostra e permette di modificare il codice gestionale per le anagrafiche direttamente nell'admin dell'ecommerce (anche per la spedizione). Inoltre salva il codice del gestionale anche nell'ordine (sia per la fatturazione che per la spedizione)
		## FATTURE ##
		"fatture_attive"			=>	1, // attiva la sezione per la generazione della fattura
		"check_fatture"				=>	0, // fai i controlli sulle fatture
		## MENU ##
		"attiva_gestione_menu"		=>	1, // se attivare o meno la gestione del menù
		"has_child_class"			=>	"menu-item-has-children", // classe dell'elemento <li> del menù se ha figli
		"has_child_link_class"		=>	"", // classe dell'elemento <a> del menù se ha figli
		"has_child_link_attributes"	=>	"", // attributi dell'elemento <a> del menù se ha figli
		## OPZIONI ##
		"attiva_gestione_opzioni"	=>	0, // attiva la sezione per la gestione delle opzioni
		"codici_opzioni_gestibili"	=>	"STATI_ELEMENTI:stati elementi", // elenco dei codici opzioni gestibili da pannello (dividere con ;)
		## FEED ##
		"attiva_gestione_feed"	=>	0, // attiva la sezione per la gestione dei feed xml
		"usa_codice_articolo_su_mpn_google_facebook"	=>	0, // se impostato ad 1, utilizza il codice articolo nel campo MPN del feed Google e Facebook
		## MOTORI DI RICERCA ##
		"attiva_gestione_motori_ricerca"	=> 0, // attiva la sezione per la gestione dei motori di ricerca
		"salva_ricerche"		=>	1, // se impostato su 1, salva le ricerche fatte
		## PIXEL ##
		"attiva_gestione_pixel"	=> 0, // attiva la sezione per la gestione dei pixel
		## TRANSAZIONI ##
		"codice_valuta"			=> 'EUR', // codice della valuta delle transazioni
		## HOME ##
		"usa_fasce_in_home"			=>	0, // se attivare o meno la gestione delle fasce in home
		"usa_meta_pagina_home"		=>	0, // se attivo, utilizza le keywords e la meta description dalla pagina indicata come home
		"numero_in_promo_home"		=>	20, // numero dei prodotti in promo nella home
		"numero_in_evidenza"		=>	4, // numero di elementi nella fascia " prodotti in evidenza"
		"random_in_evidenza"		=>	1, // se impostato su 1, i prodotti in evidenza saranno mostrati in modo randomico
		## BASE CONTROLLER ##
		"estrai_elenco_marchi"		=>	1, // estrai i marchi
		"estrai_elenco_categorie_prodotti_base_controller"	=>	1, // se deve estrarre sempre l'elenco delle categorie dal base controller
		"estrai_categorie_blog"		=>	1, // estrai le categorie del blog
		## SEO ##
		"numero_caratteri_meta_automatico"	=>	0, // se maggiore di zero, quando inserisce la descrizione della pagina nel campo meta_description, taglia oltre quel numero di caratteri
		"includi_dati_per_social_categoria"	=>	0, // includi i dati per facebook e twitter anche nelle pagine delle categorie
		"attiva_tag_hreflang"		=>	0, // se aggiungere alla pagina i tag hreflang
		"funzione_su_title_categoria"	=>	"strtolower",
		## PRODOTTI ##
		"controlla_che_il_codice_prodotti_sia_unico"	=>	0, // se impostato a 1, non permette da pannello admin di aggiungere un prodotto avente lo stesso codice di un altro prodotto
		"attiva_prodotti_digitali"	=>	0, // se impostato ad 1 attiva il campo prodotto_digitale nelle pagine
		"attiva_crediti"			=>	0, // se impostato ad 1 attiva i campi prodotto_crediti e numero_crediti nelle pagine
		"moltiplicatore_credito"	=>	1, // il numero di EURO di 1 CREDITO
		"mesi_durata_crediti"		=>	12, // numero di mesi prima della scadenza dei crediti
		"immagine_2_in_prodotto"	=>	0, // se impostata su 1, attiva la seconda immagine nel prodotto
		"immagine_3_in_prodotto"	=>	0, // se impostata su 1, attiva la terza immagine nel prodotto
		## BREADCRUMB ##
		"divisone_breadcrum"		=>	" » ",
		"togli_link_categoria_prodotti_in_breadcrumb_in_dettaglio"	=>	0, // se impostato a 1, toglie il link alla categoria prodotti nel breadcrumb del dettaglio prodotto
		"link_marchio_in_breadcrumb"	=>	0, // se impostato a 1, aggiunge il link al marchio nella breadcrumb
		"classe_link_breadcrumb"	=>	"breadcrumb_item", // classe dei vari link nel breadcrumb
		## SITEMAP ##
		"permetti_gestione_sitemap"	=>	0, // se impostato a 1, permette la gestione manuale della sitemap
		"mostra_sitemap_in_file_robots"	=>	1, // se impostato ad 1, inserisci il link della sitemap nel file di robots
		## CAPTCHA ##
		"campo_captcha_form"		=>	"cognome",
		"disattiva_antispam_checkout"	=>	0, // se impostato su 1,non ci sarà l'antispam al checkout
		## AGENTI ##
		"attiva_agenti"	=>	0, // se impostato ad 1, attiva la gestione degli agenti
		"manda_mail_ordine_ad_agenti"	=>	1, // se impostato su 1, manda una mail di resoconto anche all'agente
		"mail_ordine_dopo_pagamento_agente"	=>	0, // manda la mail dell'ordine all'agente solo dopo che è avventuo il pagamento (solo paypal e carta di credito)
		"numero_massimo_tentativi_invio_codice_coupon"	=>	3, // numero massimo di tentativi di invio del codice coupon ad un cliente
		"oggetto_ordine_ricevuto_agente"	=>	"Ordine N° [ID_ORDINE] tramite il tuo codice coupon [CODICE_COUPON]", // oggetto della mail di ordine ricevuto all'agente
		## PREZZI ##
		"prezzi_ivati_in_carrello"	=>	0,
		"prezzi_ivati_in_prodotti"	=>	0,
		"attiva_prezzo_fisso"		=>	0, // se impostato ad 1, utilizza anche il prezzo fisso
		"attiva_prezzi_ivati_in_carrello_per_utente_e_ordine"	=>	0, // se impostato su 1, la variabile prezzi_ivati_in_carrello può essere sovrascritta per ogni utente loggato o ordine eseguito dall'utente
		### GRUPPI UTENTI ##
		"attiva_gruppi_utenti"		=>	1, // se impostato a 1, mostra i gruppi nell'elenco dei clienti
		"attiva_gruppi"				=>	0, // se impostato ad 1, permette di gestire i gruppi dei clienti
		"attiva_gruppi_contenuti"	=>	0, // se impostato a 1, permette di impostare i gruppi nei contenuti e nelle fasce
		"attiva_gruppi_documenti"	=>	0, // se impostato a 1, permette di impostare i gruppi nei documenti
		### NEWSLETTER ##
		"campo_form_newsletter"		=>	"email,accetto", // campi del form newsletter
		"campo_form_newsletter_obbligatori"		=>	"", // campi obbligatori del form newsletter
		"permetti_di_collegare_gruppi_utenti_a_newsletter"	=>	0, // Se impostato ad 1, permette la gestione del campo "Sincronizza con la newsletter" nella gestione dei gruppi clienti
		### TICKET ##
		"attiva_gestiobe_ticket"	=>	0, // se impostato a 1, attiva la gestione dei ticket di aassistenza
		"numero_massimo_prodotti_ticket"	=>	2, // numero massimo di prodotti nel ticket
		"email_ticket_negozio"		=>	"", // la mail a cui viene inviata la notifica di nuovo ticket
		"numero_massimo_ticket_aperti"	=>	2, // numero massimo di ticket allo stato diverso da chiuso
		"numero_massimo_messaggi_consecutivi_per_ticket"	=>	2, // numero massimo di messaggi che un cliente può inviare prima di ricevere risposta dal negozio
		"dimensioni_upload_immagine_ticket"	=>	5000000, // dimensione massima degli upload nei ticket
		"dimensioni_upload_video_ticket"	=>	10000000, // dimensione massima degli upload dei video nei ticket
		"permetti_il_caricamento_di_video_nei_ticket"	=>	1, // 1: permetti, 0: non permettere
		"ticket_video_extensions"	=>	"mp4,mov",
		"ticket_video_mime_types"	=>	"video/mp4,video/quicktime",
		"ticket_max_immagini"		=>	5, // numero massimo di immagini in un ticket
		"ticket_max_video"			=>	1, // numero massimo di video in un ticket
		"ticket_upload_memory_limit"=>	'512M', // memory limit of the upload action
		"numero_massimo_caratteri_ticket"	=>	300, // numero massimo di caratteri nel ticket lato utente (anche nei messaggi)
		### COOKIE ##
		"stile_popup_cookie"		=>	"cookie_stile_css",
		"stile_check_cookie"		=>	"accetta",
		"attiva_blocco_cookie_terzi"=>	0,
		"attiva_x_chiudi_banner_cookie"=>	1, // se impostato su 1, mostra la X per chiudere il banner dei cookie (attiverà solo i cookie terzi)
		"var_query_string_no_cookie"		=>	"", // se messo nell'URL, non fa apparire il popup dei cookies, neanche se mai approvati
		"traccia_sorgente_utente"	=>	0, // se maggiore di 0, traccia la sorgente dell'utente in un cookie (ex Google Ads, Facebook, altro) e salvala nell'ordine. Il valore indica il tempo, in secondi, della vita del cookie di tracciamento della sorgente
		### EDITOR VISUALE ##
		"permetti_di_aggiungere_blocchi_da_frontend"	=>	0, // se impostato ad 1, permette di aggiungere nuovi tag dal frontend
		"wrap_tag_in_editor_visuale"	=>	'<div>|</div>', // tag di apertura e chiusura quando aggiungo o tolto un elemento in modalità visuale
		### FAQ ##
		"mostra_faq"				=>	0, // se attivare o meno la sezione mostra_faq
		"attiva_gestione_faq_in_evidenza"	=>	1, // se attivato, permette di gestire le FAQ in evidenza
		### TEMA ##
		"theme_folder"				=>	"", // tema frontend
		### TRADUZIONI ##
		"attiva_gestione_traduttori"	=>	0, // se impostato a 1, permette di configurare i traduttori e attiva la possibiulità di usare le traduzioni automatiche
		"considera_traduzione_sempre_esistente"	=>	0, // se impostato a 1, il sistema considera la traduzione di un elemento in contenuti_tradotti sempre esistente e non passa per la tabella originale o usa inner join anziché left join (è più veloce)
		"traduci_ultime_x_pagine_se_modificate"	=>	0, // se maggiore di 0, indica il numero delle ultime pagine che devono essere tradotte se modificate. Se lasciato a zero, le pagine sono tradotte solo se non sono mai state tradotte. 
		"traduci_sempre_le_pagine_di_queste_categorie"	=>	"127", // traduci le pagine in queste categorie anche se non sono attive (lista di ID di categorie divise da ,)
		### HOOK ##
		"hook_ordine_confermato"	=>	"", // se diversa da blank, funzione che viene chiamata dopo la conferma dell'ordine. Gli viene passato l'ID dell'ordine come unico parametro
		"hook_update_ordine"		=>	"", // se diversa da blank, funzione che viene chiamata dopo la modifica dell'ordine. Gli viene passato l'ID dell'ordine come unico parametro
		"hook_set_placeholder"		=>	"", // se diversa da blank, funzione che viene chiamata sui placeholder. Come unico argomento gli viene passato l'array di tutti i placeholder (VariabiliModel::$placeholders)
		"hook_after_login_admin"	=>	"", // se diversa da blank, funzione che viene chiamata dopo il login in admin. Gli viene passato un riferimento al controller come unico parametro
		"hook_add_to_cart"			=>	"", // se diverso da blank, funzione che viene chiamata dopo l'aggiunta di un prodotto al carrello. Gli viene passato l'ID della riga del carrello come unico parametro
		"hook_delete_cart"			=>	"", // se diverso da blank, funzione che viene chiamata dopo l'eliminazione di una riga del carrello. Gli viene passato l'ID della riga del carrello come unico parametro
		"hook_utente_annulla_ordine"	=>	"", // se diverso da blank, funzione che viene chiamata dopo che l'utente ha annullato l'ordine. Gli viene passato l'ID dell'ordine come unico parametro
		"hook_after_login_utente"	=>	"", // se diverso da blank, funzione che viene chiamata dopo che l'utente ha eseguito correttamente il login. Gli viene passto l'ID dell'utente come unico parametro
		"hook_after_creazione_spedizione"	=>	"", // se diverso da blank, funzione che viene chiamata dopo che è stata creata una spedizione con il modulo spedizioni. Gli viene passto l'ID della spedizione come unico parametro
		### GDPR ##
		"filtra_html_in_cerca_di_servizi_da_disattivare"	=>	0, // se impostato su 1, filtra l'HTML e disabilita i servizi che attivano cookie terzi (Gmaps, Youtube, etc) inserendo in automatico un messaggio di popup
		### PASSWORD ##
		// "password_regular_expression"	=>	"/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^*-]).{8,}$/", // Espressione regolare per validare la password sia per utenti frontend che utenti admin
		"attiva_controllo_robustezza_password"				=>	0, // se impostato ad 1, attiva che la password asoddisfi certi criteri
		"password_regular_expression_caratteri_maiuscoli"	=>	"(?=.*?[A-Z])", // Espressione regolare per i caratteri maiuscoli
		"password_regular_expression_caratteri_minuscoli"	=>	"(?=.*?[a-z])", // Espressione regolare per i caratteri minuscoli
		"password_regular_expression_caratteri_numerici"	=>	"(?=.*?[0-9])", // Espressione regolare per i caratteri numerici
		"password_regular_expression_caratteri_speciali"	=>	"#?!@$%^*-", // Elenco di caratteri speciali nella password (deve essere presente almeno uno di tali caratteri)
		"password_regular_expression_numero_caratteri"		=>	8, // Numero minimo di caratteri nella password
		### LINGUE ##
		"lingue_abilitate_frontend"	=>	"it",
		"token_gestisci_lingue_da_admin"	=>	"", // token per poter gestire le lingue da admin (oltre a dover essere loggati)
		### DASHBOARD ##
		"nome_negozio_dashboard"	=>	"", // nome del negozio in alto a sinistra nella dashboard. Se lasciato vuoto prende il nome usato per il tag <title> del sito
		### AI ##
		"attiva_richieste_ai"		=>	0, // se impostato su 1, attiva l'integrazione con l'AI generativa
		"istruzioni_ruolo_system_richieste_ai"	=>	"Usa principalmente il testo delimitato da virgolette triple (contesto) oppure lo storico di questa chat per rispondere alle domande.", // testo per indirizzare le risposte dell'AI
		"default_primo_messaggio_ai"=>	"", // se impostato, viene proposto come primo messaggio all'AI
		### CRON ##
		"attiva_cron_web"			=>	0, // se impostato a 1, prmette di chiamare operazioni di CRON tramite call a URL
		"token_comandi_cron_web"	=>	"", // token di sicurezza per chiamare comandi di CRON tramite call a URL (viene inizializzato in automatico se vuoto)
		### AUTENTICAZIONE DUE FATTORI ##
		"attiva_autenticazione_due_fattori_admin"	=>	0, // se è attiva l'autenticazione a due fattori nell'admin
		"autenticazione_due_fattori_admin_durata_cookie"	=>	2592000, // durata del cookie dell'autenticazione a due fattori (default 30 giorni)
		"autenticazione_due_fattori_numero_cifre_admin"	=>	6, // numero delle cifre del codice di verifica inviato nell'autenticazione a due fattori lato admin
		"autenticazione_due_fattori_durata_verifica_admin"	=>	60, // tempo in secondi dopo il quale viene cancellata una sessione a due fattori non verificata
		"autenticazione_due_fattori_numero_massimo_tentativi_admin"	=>	3, // numero massimo di tentativi nell'inserimento del codice a 2 fattori
		"autenticazione_due_fattori_numero_massimo_invii_codice_admin"	=>	3, // numero massimo di volte che il codice a 2 fattori può essere inviato
		"attiva_campo_email_admin"	=>	0, // se impostato su 1, attiva il campo "email" nella scheda degli utenti admin
		### PAGAMENTI ##
		"pagamenti_permessi"		=>	"bonifico,paypal",
		"campo_codice_transazione_nexi"	=>	"codice_transazione", // il campo dell'ordine da usare come codice di transazione nei pagamenti Nexi
		### IVA ##
		"ripartisci_iva_spese_accessorie_proporzionalmente_ai_prodotti"	=>	0, // se impostato su 1, calcola l'IVA della spedizione ripartendo sulla base dei totali e delle aliquote dei prodotti del carrello
		### DOCUMENTI ##
		"documenti_in_prodotti"		=>	1, // se impostato su 1, mostra la scheda documenti nei prodotti
		"mostra_tipi_documento"		=>	1, // se impostato su 1, attiva la sezione "tipi documento"
		"riconoscimento_tipo_documento_automatico"	=>	1, // se impostato su 1, permette l'upload di file ZIP per l'elaborazione o di molti documenti
		"attiva_altre_lingue_documento"	=>	0, // se impostato su 1, permette l'inclusione o l'esclusione di altre lingue nel documento
		"abilita_traduzioni_documenti"	=>	1, // se impostato su 1, abilita le traduzioni dei documenti
		"attiva_immagine_in_documenti"	=>	1, // se impostato su 1, attiva il campo immagine nei documenti
		"cerca_lingua_documento_da_nome_file"	=>	1, // se impostato su 1, cerca la lingua del documento dal nome del file
		"lingua_default_documenti"	=>	"tutte", // lingua di default dei documenti
		"estensioni_accettate_documenti"	=>	"pdf,png,jpg,jpeg", // estensioni accettate per i documenti
		"dimensioni_upload_documenti"	=>	3000000, // dimensione massima degli upload nei documenti
		"attiva_link_documenti"		=>	0, // se impostato su 1, attiva la possibilità di impostare un link ad un documento (ex in slide, etc)
		"documenti_in_clienti"		=>	0, // se impostato su 1, attiva la possibilità di caricare documenti legati ai clienti
		"attiva_biblioteca_documenti"	=>	0, // se mostrare o nascondere i link della propria biblioteca in area riservata
		"attiva_sezione_download_documenti"	=>	0, // se impostato su 1, mostra la voce di menù che manda alle statistiche di scaricamento
		### MAIL LOG ##
		"email_log_errori"			=>	"",	// // Indirizzi email (divisi da virgola) a cui inviare un avviso se la verifica IPN (o affini) del pagamento non va a buon fine (con errori segnalati dal gateway di pagamento riportati nel corpo della mail)
		"email_log_pagamento_da_analizzare"	=>	"", // Indirizzi email (divisi da virgola) a cui inviare un avviso via mail se l'ecommerce riceve un pagamento su un ordine che non si trova in uno stato pending (ex un pagamento su un ordine annullato)
		### CALENDARIO CHIUSURE ##
		"attiva_calendario_chiusure"	=>	0, // se impostato, attiva il calendario delle chiusure
		### FASCE ##
		"attiva_gestione_fasce_frontend"	=>	0, // permetti la gestione delle fasce da frontend
		"attiva_gestione_fasce_frontend_prodotti"	=>	0, // se impostato su 1, mostra il link per la gestione delle fasce nei prodotti
		###
		"attiva_tag_in_testi"	=>	0, // se impostato a 0 sarà possibile selezionare il tag contenitore dell'elemento
		"attiva_redirect"		=>	0, // se impostato a 1, permette di gestire i redirect
		"default_ordinamento_prodotti"	=>	"tutti", // ordinamento die default dei prodotti (valori ammessi: tutti -> come admin, az -> alfabetico crescente, za -> alfabetico decrescente, crescente -> prezzo crescente, decrescente -> prezzo decrescente, piuvenduto -> dal più venduto al meno venduto)
		"attiva_filtri_caratteristiche_separati_per_categoria"	=>	0, // se impostato a 1, sarà possibile aggiungere filtri di caratteristiche distini per ogni categoria
		"carica_tutti_i_model"	=>	1, // carica subito tutti i model
		"attiva_gruppi_admin"	=>	0, // if impostato a 1 permette di creare gruppi di utenti e di decidere l'accesso a determinati controller per ogni gruppo di utenti
		"cartella_backend"		=>	"admin", // la cartella con il backoffice e i file del CMS
		"usa_sotto_query_in_elenco"	=>	0, // se fare un'unica query in join o tante piccole sotto query per i prodotti nella griglia
		"ip_sito"				=>	'', // se impostato, l'IP del server del sito
	);
	
	public static $daInizializzare = array(
		"token_schedulazione",
		"debug_get_variable",
		"debug_retargeting_get_variable",
		"var_query_string_no_cookie",
		"token_edit_frontend",
		"token_feed_google_facebook",
		"token_aggiorna_alias_combinazioni",
		"token_eliminazione_account_da_app",
		"token_recupera_carrello",
		"token_gestisci_lingue_da_admin",
		"token_comandi_cron_web",
		"token_login_come_utente",
	);
	
	public static function inizializza($variabili = array())
	{
		$daInizializzare = self::$daInizializzare;
		
		if (!empty($variabili))
			$daInizializzare = array_merge(self::$daInizializzare, $variabili);
		
		foreach ($daInizializzare as $var)
		{
			if (!trim(v($var)))
				VariabiliModel::setValore($var, md5(randString(20).uniqid(mt_rand(),true)));
		}
	}
	
	public static function checkToken($tokenName)
	{
		if (v($tokenName) && isset($_GET[v($tokenName)]))
			return true;
		
		return false;
	}
	
	public static function checkCookieTerzeParti()
	{
		foreach (self::$variabiliCodiciCookieTerzi as $var => $default)
		{
			if (isset(VariabiliModel::$valori[$var]) && trim(VariabiliModel::$valori[$var]))
				self::$usatiCookieTerzi = true;
		}
		
		if (v("attiva_blocco_cookie_terzi") && !isset($_COOKIE["ok_cookie_terzi"]))
		{
			foreach (self::$variabiliCodiciCookieTerzi as $var => $default)
			{
				VariabiliModel::$valori[$var] = $default;
			}
		}
	}
	
	public static function noCookieAlert()
	{
		if (v("var_query_string_no_cookie"))
			$_GET[v("var_query_string_no_cookie")] = "";
	}
	
	public static function getNoCookiAlertQueryString($char = "?")
	{
		if (v("var_query_string_no_cookie"))
			return $char.v("var_query_string_no_cookie")."=Y";
		
		return "";
	}
	
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
			"TELEFONO AZIENDALE"	=>	v("telefono_aziendale"),
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
			'codice_gtm'	=>	array(
				'labelString'	=>	"Google Tag Manager",
				'type'			=>	'Textarea',
				'wrap'		=>	array(
					null,
					null,
					"<div class='form_notice'>".gtext("Inizia con il seguente codice Google")."<br />&lt;!-- Google Tag Manager --&gt;</div>"
				),
			),
			'codice_gtm_analytics_noscript'	=>	array(
				'labelString'	=>	"Google Tag Manager (noscript)",
				'type'			=>	'Textarea',
				'wrap'		=>	array(
					null,
					null,
					"<div class='form_notice'>".gtext("Inizia con il seguente codice Google")."<br />&lt;!-- Google Tag Manager (noscript) --&gt;</div>"
				),
			),
			'codice_gtm_analytics'	=>	array(
				'labelString'	=>	"Global site tag (gtag.js)",
				'type'			=>	'Textarea',
				'wrap'		=>	array(
					null,
					null,
					"<div class='form_notice'>".gtext("Inizia con il seguente codice Google")."<br />&lt;!-- Google tag (gtag.js) - XXX --&gt;</div>"
				),
			),
			'codice_fbk'	=>	array(
				'labelString'	=>	"Codice pixel Facebook",
				'type'			=>	'Textarea',
			),
			'codice_verifica_fbk'	=>	array(
				'labelString'	=>	"Meta tag verifica Facebook / Google / Altri",
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
			'identificatore_feed_default'	=>	array(
				'labelString'	=>	'Valore globale del campo "Esiste l\'identificatore?" (feed Google / Facebook)',
				'type'			=>	'Select',
				'options'	=>	self::$yesNo,
				"reverse"	=>	"yes",
			),
			'codice_account_merchant'	=>	array(
				'wrap'		=>	array(
					null,
					null,
					"<div class='form_notice'>".gtext("Il codice dell'account Merchant di Google, presente nel pannello Merchant")."</div>",
				),
			),
			'campo_send_to_google_ads'	=>	array(
				'wrap'		=>	array(
					null,
					null,
					"<div class='form_notice'>".gtext("Il valore del campo send_to ottenuto dal codice di conversione di Google Ads")."</div>",
				),
			),
			'codice_js_ok_cookie'	=>	array(
				'labelString'	=>	'Altri codici JS di tracciamento',
				'type'			=>	'Textarea',
				'wrap'		=>	array(
					null,
					null,
					"<div class='form_notice'>".gtext("Verranno attivati solo se il cliente approva tutti i cookie.")."</div>",
				),
			),
			'versione_google_analytics'	=>	array(
				'labelString'	=>	gtext('Versione di Google Analytics usata'),
				'type'			=>	'Select',
				'options'	=>	array(
					"3"	=>	"Universal Analytics",
					"4"	=>	"Google Analytics 4",
				),
				"reverse"	=>	"yes",
			),
		);
		
		$formFields = $formFields + self::$strutturaFormCampiAggiuntivi;
		
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
		Factory_Timer::getInstance()->startTime("VARIABILI","VARIABILI");
		
		$var = new VariabiliModel();
		
		$values = $var->clear()->toList("chiave", "valore")->send();
		
		self::$valori = $values;
		
		Factory_Timer::getInstance()->endTime("VARIABILI","VARIABILI");
	}
	
	public static function verificaCondizioni($condizioni)
	{
		if (!is_array($condizioni))
			parse_str($condizioni, $conds);
		else
			$conds = $condizioni;
		
		if (is_array($conds))
		{
			foreach ($conds as $k => $v)
			{
				if ((string)v($k) !== (string)$v)
					return false;
			}
		}
		
		return true;
	}
	
	public static function getVariabileMatches($matches)
	{
		$chiave = $matches[1];
		
		return v($chiave);
	}
	
	// $queryString: query string di variabili: ex var1=1&var=3&...
	public static function impostaVariabiliDaQueryString($queryString)
	{
		parse_str($queryString, $variabili);
		
		foreach ($variabili as $k => $v)
		{
			if (is_string($k) && in_array($k, self::$variabiliGestibiliTramiteQueryString))
				self::$valori[$k] = $v;
		}
	}
	
	public static function combinazioniLinkVeri()
	{
		return ((v("usa_codice_combinazione_in_url_prodotto") || v("usa_alias_combinazione_in_url_prodotto")) && v("aggiorna_pagina_al_cambio_combinazione_in_prodotto")) ? true : false;
	}
	
	public static function confermaUtenteRichiesta()
	{
		return (v("conferma_registrazione") || v("gruppi_inseriti_da_approvare_alla_registrazione")) ? true : false;
	}
	
	public static function paginaAutenticazione()
	{
		return (v("pagina_di_autenticazione") && !User::$logged) ? "autenticazione" : "checkout";
	}
	
	public static function checkNumeroMailInviate()
	{
		return (v("max_numero_email_ora") || v("max_numero_email_giorno")) ? true : false;
	}
	
	public static function movimenta()
	{
		return v("scala_giacenza_ad_ordine") ? true : false;
	}
	
	public static function getUrlAjaxClienti()
	{
		return v("utilizza_ricerca_ajax_su_select_2_clienti") ? "/regusers/main?esporta_json&formato_json=select2" : "";
	}
	
	public static function mostraAvvisiGiacenzaCarrello()
	{
		return (v("testo_disponibilita_immediata") && v("testo_disponibilita_non_immediata")) ? true : false;
	}

	public static function setPasswordRegularExpression()
	{
		return "/^".v("password_regular_expression_caratteri_maiuscoli").v("password_regular_expression_caratteri_minuscoli").v("password_regular_expression_caratteri_numerici")."(?=.*?[".v("password_regular_expression_caratteri_speciali")."]).{".v("password_regular_expression_numero_caratteri").",}$/";
	}
	
	public static function classeHelpWizardPassword()
	{
		return v("attiva_controllo_robustezza_password") ? "help_wizard_password" : "";
		
	}
	
	public static function attivaCodiceGestionale()
	{
		if (v("attiva_collegamento_gestionali") && v("mostra_codice_gestionale"))
			return true;
		
		return false;
	}
	
	public static function getMailAvvisoPagamentoOrdineNonPending()
	{
		return v("email_log_pagamento_da_analizzare") ? "email_log_pagamento_da_analizzare" : "email_log_errori";
	}
}
