<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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

class PagesModel extends GenericModel {

	public $lId = 0;
	public $titleFieldName = "title";
	public $aliaseFieldName = "alias";
	public $campoTitolo = "title";
	
	public $hModelName = "CategoriesModel";
	public $hModel = null; //hierarchical model

	public $checkAll = true;
	
	public $fileTypeAllowed = array('jpg','jpeg','gif','png', 'svg'); // File extensions
	public $rielaboraImmagine = true;
	
	public $cartellaImmaginiContenuti = "images/contents";
	public $fileNameRandom = false;
	
	public $documentiModelAssociato = "DocumentiModel";
	public $contattiModelAssociato = "ContattiModel";
	
	public static $uploadFile = true;
	
	public static $currentRecord = null;
	
	public static $arrayImmagini = null;
	
	public static $pagineConFeedback = null;
	
	public static $tipiPaginaId = array();
	
	public static $currentIdPage = null;
	public static $currentTipoPagina = "";
	
	public static $campiAggiuntivi = array();
	public static $campiAggiuntiviMeta = array(
		"traduzione"	=>	array(),
	);
	
	public static $pagesStruct = array();
	
	public static $testoLabelSocial = "Lato frontend verrà mostrato se il tema lo prevede";
	
	public static $IdCombinazione = 0;
	public static $bckIdCombinazione = 0;
	
	public static $arrayIdCombinazioni = array();
	
	public static $modelliDaDuplicare = array(
		"ImmaginiModel",
		"LayerModel",
		"ScaglioniModel",
		"ContenutiModel",
		"DocumentiModel",
		"PageslinkModel",
		"CorrelatiModel",
		"PagespersonalizzazioniModel",
		"PagestagModel",
		"PagespagesModel",
		"PagescarvalModel",
		"PagespersonalizzazioniModel",
		"PagesattributiModel",
		"CombinazioniModel",
		"PagesregioniModel",
		"PageslingueModel",
		"PagescategoriesModel",
	);
	
	public static $tipiPaginaAddizionali = array();
	
	public static $aggiornaPrezziCombinazioniQuandoSalvi = true;
	
	public static $tipiPagina = array(
		"GRAZIE"		=>	"Pagina ringraziamento form richiesta informazioni",
		"GRAZIE_NEWSLETTER"	=>	"Pagina ringraziamento iscrizione a newsletter",
		"COOKIE"		=>	"Pagina cookie",
		"CONDIZIONI"	=>	"Condizioni Generali Di Vendita",
		"ACCOUNT_ELIMINATO"	=>	"Account eliminato",
		"PRIVACY"		=>	"Pagina privacy",
		"AZIENDA"		=>	"Pagina azienda",
		"CONTATTI"		=>	"Pagina contatti",
		"RESI"			=>	"Pagina resi",
		"FAQ"			=>	"Pagina FAQ",
		"INFO_LEGALI"	=>	"Pagina informazioni Legali",
		"SPEDIZIONI"	=>	"Pagina info spedizioni",
		"FILOSOFIA"		=>	"Pagina filosofia",
		"B2B"			=>	"Pagina info B2B",
		"HOME"			=>	"Home page",
		"MARCHI"		=>	"Pagina elenco marchi",
		"PACCO_REGALO"	=>	"Pagina descrizione pacchi regalo",
		"PAGAMENTI"		=>	"Pagina informazioni pagamenti",
		"GARANZIA"		=>	"Pagina informazioni garanzia",
		"ASSISTENZA"	=>	"Pagina informazioni assistenza",
		"GUIDA_ACQUISTO"=>	"Pagina Guida all'acquisto",
	);
	
	public function __construct() {
		$this->_tables='pages';
		$this->_idFields='id_page';
		
		$objectReflection = new ReflectionClass($this->hModelName);
		$this->hModel = $objectReflection->newInstanceArgs();
		
// 		$this->hModel = new $this->hModelName();
		
		$this->_where = array(
			"id_c"			=>	"categories",
			"-id_marchio"	=>	"marchi",
		);
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'pages.id_order';
		$this->_lang = 'It';
		
		$this->addValuesCondition("both",'checkIsStrings|Y,N',"attivo,in_evidenza,in_promozione");
		
		$this->setConditions();
		
		$this->salvaDataModifica = true;
		
		$this->uploadFields = array();
		
		if (v("abilita_feedback"))
		{
			self::$tipiPagina["FORM_FEEDBACK"] = "Pagina inserimento feedback cliente";
			self::$tipiPagina["CONDIZIONI_FEEDBACK"] = "Pagina condizioni inserimento feedback";
			self::$tipiPagina["GRAZIE_FEEDBACK"] = "Pagina ringraziamento feedback";
		}
		
		if (v("attiva_verifica_contatti"))
			self::$tipiPagina["CONF_CONT_SCADUTO"] = "Pagina informativa link conferma contatto scaduto";
		
		if (v("attiva_liste_regalo"))
		{
			self::$tipiPagina["LISTA_REGALO"] = "Pagina pubblica lista regalo";
			self::$tipiPagina["LISTA_REGALO_NE"] = "Pagina lista regalo non esistente o scaduta";
		}
		
		foreach (self::$tipiPaginaAddizionali as $tipo => $label)
		{
			self::$tipiPagina[$tipo] = $label;
		}
		
		parent::__construct();
	}
	
	protected function setConditions()
	{
		$this->addStrongCondition("both",'checkNotEmpty',"title");
	}
	
	public function relations() {
		return array(
			'feedback' => array("HAS_MANY", 'FeedbackModel', 'id_page', null, "RESTRICT", "L'elemento ha dei feedback collegati e non può essere eliminato"),
			'righe' => array("HAS_MANY", 'RigheModel', 'id_page', null, "RESTRICT", "L'elemento ha degli ordini collegati e non può essere eliminato"),
			'retargeting' => array("HAS_MANY", 'EventiretargetingModel', 'id_page', null, "RESTRICT", "L'elemento ha degli eventi remarketing collegati, eliminare prima tali eventi"),
			'regali' => array("HAS_MANY", 'ListeregalopagesModel', 'id_page', null, "RESTRICT", "L'elemento è inserito in alcune liste regalo e non può essere eliminato"),
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_page', null, "CASCADE"),
			'contenuti' => array("HAS_MANY", 'ContenutiModel', 'id_page', null, "CASCADE"),
			'documenti' => array("HAS_MANY", 'DocumentiModel', 'id_page', null, "CASCADE"),
			'personalizzazioni' => array("HAS_MANY", 'PagespersonalizzazioniModel', 'id_page', null, "CASCADE"),
			'combinazioni' => array("HAS_MANY", 'CombinazioniModel', 'id_page', null, "CASCADE"),
			'caratteristiche' => array("HAS_MANY", 'PagescarvalModel', 'id_page', null, "CASCADE"),
			'tag' => array("HAS_MANY", 'PagestagModel', 'id_page', null, "CASCADE"),
			'categories' => array("HAS_MANY", 'PagescategoriesModel', 'id_page', null, "CASCADE"),
			'link' => array("HAS_MANY", 'PageslinkModel', 'id_page', null, "CASCADE"),
			'regioni' => array("HAS_MANY", 'PagesregioniModel', 'id_page', null, "CASCADE"),
			'lingue' => array("HAS_MANY", 'PageslingueModel', 'id_page', null, "CASCADE"),
			'sitemap' => array("HAS_MANY", 'SitemapModel', 'id_page', null, "CASCADE"),
			'stats' => array("HAS_MANY", 'PagesstatsModel', 'id_page', null, "CASCADE"),
			'redirect' => array("HAS_MANY", 'RedirectModel', 'id_page', null, "CASCADE"),
			'marchio' => array("BELONGS_TO", 'MarchiModel', 'id_marchio',null,"CASCADE"),
		);
    }
    
	public function setFilters()
	{
		$this->_popupItemNames = array(
			'attivo'	=>	'attivo',
			'id_c'	=>	'id_c',
			'in_evidenza'	=>	'in_evidenza',
// 			'in_promozione'	=>	'in_promozione',
		);
		
		$this->_popupLabels = array(
			'attivo'	=>	'PUBBLICATO?',
			'id_c'	=>	'CATEGORIA',
			'in_evidenza'	=>	'IN EVIDENZA?',
// 			'in_promozione'	=>	'IN PROMOZIONE?',
		);

		$this->_popupFunctions = array(
			'attivo'=>	'getYesNo',
			'id_c'	=>	'getCatNameForFilters',
			'in_evidenza'	=>	'getYesNo',
// 			'in_promozione'	=>	'getYesNo',
		);
		
		$this->_popupOrderBy = array(
			'id_c'	=>	'lft asc',
		);
		
		if (isset($this->hModel->section))
		{
			$this->_popupWhere = array(
				'id_c'	=>	$this->hModel->getChildrenFilterWhere(),
			);
		}
	}
	
	public function setFormStruct($id = 0)
	{
		$haCombinazioni = $this->hasCombinations((int)$id, false);
		
		if (v("categorie_google_tendina"))
			$strutturaCategorieGoogle = array(
				'type'		=>	'Select',
				'entryClass'	=>	'form_input_text',
				'options'	=>	$this->getOpzioniCategoriaGoogle($id),
				'reverse' => 'yes',
				'wrap'		=>	$this->getWrapCategorieGoogle(),
				'entryAttributes'	=>	array(
					"select2"	=>	"/opzioni/main?codice=CATEGORIE_GOOGLE&esporta_json&formato_json=select2",
				),
			);
		else
			$strutturaCategorieGoogle = array(
				'wrap'		=>	$this->getWrapCategorieGoogle(),
			);
			
		$wrapCombinazioni = array();
		
		$testoLasciareVuotoSeNonPresente = "Lasciare vuoto se non presente o non conosciuto";
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'title'		=>	array(
					'labelString'=>	'Titolo',
					'entryClass'	=>	'form_input_text help_titolo',
				),
				'meta_title'		=>	array(
					'labelString'=>	'Meta title della pagina',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se lasciato vuoto userà il titolo della pagina")."</div>"
					),
				),
				'video'		=>	array(
					'labelString'=>	'Video',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Incollare il codice di condivisione di Youtube, Vimeo o altri portali")."</div>"
					),
				),
				'data_news'		=>	array(
					'labelString'=>	'Data scrittura',
				),
				'coordinate'		=>	array(
					'labelString'=>	'Coordinate',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Llatitudine e longitudine divise da virgola.")."</div>"
					),
				),
				'alias'		=>	array(
					'labelString'=>	'Alias (per URL)',
					'entryClass'	=>	'form_input_text help_alias',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Indica l'URL che avrà la pagina/prodotto nel sito. Se lasciato vuoto, viene generato in automatico dal titolo")."</div>"
					),
				),
				'id_c'		=>	array(
					'type'		=>	'Select',
					'entryClass'	=>	'form_input_text help_categoria',
					'labelString'=>	'Categoria',
					'options'	=>	$this->buildCategorySelect(),
					'reverse' => 'yes',
					
				),
				'id_iva'		=>	array(
					'type'		=>	'Select',
					'entryClass'	=>	'form_input_text help_iva',
					'labelString'=>	'Aliquota Iva',
					'options'	=>	$this->selectIva(),
					'reverse' => 'yes',
					
				),
				'id_marchio'	=>	array(
					'type'		=>	'Select',
					'entryClass'	=>	'form_input_text help_marchio',
					'labelString'=>	gtext('famiglia',false,"ucfirst"),
					'options'	=>	$this->selectMarchi(),
					'reverse' => 'yes',
					
				),
				'price'		=>	array(
					'labelString'=>	'Prezzo Iva esclusa (€)',
					'wrap' =>	$wrapCombinazioni,
				),
				'price_ivato'	=>	array(
					'labelString'=>	'Prezzo Iva inclusa (€)',
					'entryClass'	=>	'form_input_text help_prezzo',
					'wrap' =>	$wrapCombinazioni,
				),
				'codice'		=>	array(
					'labelString'=>	'Codice prodotto',
					'entryClass'	=>	'form_input_text help_codice',
					'wrap' =>	$wrapCombinazioni,
				),
				'attivo'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Pubblicato?',
					'entryClass'	=>	'form_input_text help_attivo',
					'options'	=>	array('sì'=>'Y','no'=>'N'),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se compare nel sito")."</div>"
					),
				),
				'test'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Prodotto di test?',
					'entryClass'	=>	'form_input_text help_test',
					'options'	=>	self::$attivoSiNo,
					'reverse' => 'yes',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se è settato come prodotto di test, non sarà indicizzato né presente nella categoria di riferimento, ma potrà comunque essere visualizzato conoscendo l'URL diretto")."</div>"
					),
				),
				'in_evidenza'	=>	array(
					'type'		=>	'Select',
					'entryClass'	=>	'form_input_text help_evidenza',
					'labelString'=>	'In evidenza?',
					'options'	=>	array('no'=>'N','sì'=>'Y'),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Verrà evidenziato nel sito (in home, nei menù, etc), in funzione del tema")."</div>"
					),
				),
				'nuovo'	=>	array(
					'type'		=>	'Select',
					'entryClass'	=>	'form_input_text help_nuovo',
					'labelString'=>	'Prodotto marcato come nuovo?',
					'options'	=>	array('no'=>'N','sì'=>'Y'),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se viene indicato come nuovo nel sito, in funzione del tema")."</div>"
					),
				),
				'peso'		=>	array(
					'labelString'=>	'Peso (kg)',
					'entryClass'	=>	'form_input_text help_peso',
					'wrap' =>	$wrapCombinazioni,
				),
				'in_promozione'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'In promozione?',
					'options'	=>	array('no'=>'N','sì'=>'Y'),
					'className'	=>	'in_promozione form-control',
					'entryClass'	=>	'form_input_text form_input_text_promozione',
				),
				'prezzo_promozione'		=>	array(
					'labelString'=>	'Percentuale sconto (in %)',
					'entryClass'	=>	'class_promozione form_input_text',
					'className'	=>	'input_corto form-control',
				),
				'dal'		=>	array(
					'labelString'=>	'In promozione dal',
					'className'	=>	'data_field input_corto form-control',
					'entryClass'	=>	'class_promozione form_input_text',
				),
				'al'		=>	array(
					'labelString'=>	'In promozione fino al',
					'className'	=>	'data_field input_corto form-control',
					'entryClass'	=>	'class_promozione form_input_text',
				),
				'description'		=>	array(
					'type'		 =>	'Textarea',
					'entryClass'	=>	'form_textarea help_descrizione',
					'labelString'=>	'Descrizione',
					'className'		=>	'dettagli',
				),
				'descrizione_2'		=>	array(
					'type'		 =>	'Textarea',
					'entryClass'	=>	'form_textarea help_descrizione_2',
					'className'		=>	'dettagli',
				),
				'descrizione_3'		=>	array(
					'type'		 =>	'Textarea',
					'entryClass'	=>	'form_textarea help_descrizione_3',
					'className'		=>	'dettagli',
				),
				'descrizione_4'		=>	array(
					'type'		 =>	'Textarea',
					'entryClass'	=>	'form_textarea help_descrizione_4',
					'className'		=>	'dettagli',
				),
				'descrizione_breve'		=>	array(
					'type'		 =>	'Textarea',
					'labelString'=>	'Descrizione breve',
					'className'		=>	'dettagli',
				),
				'dettagli'		=>	array(
					'type'		 =>	'Textarea',
					'labelString'=>	'Dettagli',
				),
				'id_page'	=>	array(
					'type'		=>	'Hidden'
				),
				'use_editor'	=>	array(
					'labelString'=>	'Editor visuale',
				),
				'immagine'	=>	array(
					'type'		=>	'Hidden'
				),
				'immagine_2'	=>	array(
					'type'		=>	'Hidden'
				),
				'immagine_3'	=>	array(
					'type'		=>	'Hidden'
				),
				'immagine_fondo'	=>	array(
					'type'		=>	'Hidden'
				),
				'codice_nazione'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Nazione',
					'options'	=>	$this->selectNazione(),
					'reverse' => 'yes',
				),
				'id_regione'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Regione',
					'options'	=>	$this->selectRegione(),
					'reverse' => 'yes',
				),
				'giacenza'	=>	array(
					'labelString'=>	'Giacenza (disponibilità a magazzino)',
					'entryClass'	=>	'form_input_text help_giacenza',
					'wrap' =>	$wrapCombinazioni,
				),
				'tipo_pagina'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Tipo pagina',
					'options'	=>	$this->selectTipiPagina(),
					'reverse' => 'yes',
				),
				'acquisto_diretto'		=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>Se settato su no, non è permesso l'acquisto diretto, ma il prodotto è comunque visibile nel frontend.<br />Se predisposto, fa comparire un form di richiesta informazioni.</div>"
					),
				),
				'acquistabile'		=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>Se settato su no, il prodotto non è acquistabile e viene nascosto nel frontend.<br />Può solo essere aggiunto come accessorio.</div>"
					),
				),
				'aggiungi_sempre_come_accessorio'		=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>Se settato su sì, il prodotto viene sempre aggiunto come accessorio alla creazione di qualsiasi nuovo prodotto.</div>"
					),
				),
				'id_p'		=>	array(
					'type'		=>	'Select',
					'entryClass'	=>	'form_input_text help_principale',
					'labelString'=>	'Prodotto principale',
					'options'	=>	$this->selectProdotti($id),
					'reverse' => 'yes',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se è il prodotto canonical")."</div>"
					),
				),
				'priorita_sitemap'	=>	array(
					'labelString'=>	'Priorità sitemap',
				),
				'allineamento'	=>	array(
					'type'		=>	'Select',
					'options'	=>	array('LX'=>'Sinistra','DX'=>'Destra','CE'=>'Centro'),
					'reverse' => 'yes',
					
				),
				'ora_inizio_evento'	=>	array(
					"className"		=>	"form-control clockpicker",
				),
				'ora_fine_evento'	=>	array(
					"className"		=>	"form-control clockpicker",
				),
				'testo_link'	=>	array(
					'labelString'	=>	'Testo pulsante',
				),
				'url'	=>	array(
					'labelString'	=>	'Link libero',
				),
				'template_modale'		=>	array(
					'type'		=>	'Select',
					'entryClass'	=>	'form_input_text help_principale',
					'labelString'=>	'Tema grafico del popup',
					'options'	=>	Tema::getSelectElementi("Elementi/Modali"),
					'reverse' => 'yes',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Indica il tema grafico che avrà il popup. È possibile scegliere tra quelli definiti nel tema.")."</div>"
					),
				),
				'giorni_durata_modale'		=>	array(
					'type'		=>	'Select',
					'entryClass'	=>	'form_input_text help_principale',
					'labelString'=>	'Giorni di durata del popup',
					'options'	=>	array(
						-1	=>	gtext("Mostra ogni volta che il cliente torna in home page"),
						0	=>	gtext("Mostra nuovamente ogni volta che il cliente chiude il browser"),
						1	=>	gtext("Mostra nuovamente dopo 1 giorno"),
						2	=>	gtext("Mostra nuovamente dopo 2 giorni"),
						3	=>	gtext("Mostra nuovamente dopo 3 giorni"),
						4	=>	gtext("Mostra nuovamente dopo 4 giorni"),
						5	=>	gtext("Mostra nuovamente dopo 5 giorni"),
						6	=>	gtext("Mostra nuovamente dopo 6 giorni"),
						7	=>	gtext("Mostra nuovamente dopo 1 settimana"),
						14	=>	gtext("Mostra nuovamente dopo 2 settimane"),
						21	=>	gtext("Mostra nuovamente dopo 3 settimane"),
						28	=>	gtext("Mostra nuovamente dopo 4 settimane"),
						30	=>	gtext("Mostra nuovamente dopo 30 giorni"),
						60	=>	gtext("Mostra nuovamente dopo 60 giorni"),
						120	=>	gtext("Mostra nuovamente dopo 120 giorni"),
						180	=>	gtext("Mostra nuovamente dopo 6 mesi"),
						360	=>	gtext("Mostra nuovamente dopo 1 anno"),
					),
					'reverse' => 'yes',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Indica il tempo dopo il quale il popup verrà nuovamente mostrato.")."</div>"
					),
				),
				'go_to'	=>	array(
					'labelString'	=>	"Esegui lo scroll all'identificatore",
				),
				'apri_dopo_secondi'		=>	array(
					'labelString'=>	'Mostra dopo secondi',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Indica il tema dopo il quale il popup verrà aperto")."</div>"
					),
				),
				'codice_js'	=>	array(
					'labelString'	=>	'Codice di conversione Google',
				),
				'codice_categoria_prodotto_google'	=>	$strutturaCategorieGoogle,
				'gtin'	=>	array(
					'labelString'	=>	'Codice internazione GTIN',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext($testoLasciareVuotoSeNonPresente)."</div>"
					),
				),
				'mpn'	=>	array(
					'labelString'	=>	'Codice MPN (Manufacturer Part Number)',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Codice del prodottore.")." ".gtext($testoLasciareVuotoSeNonPresente)."</div>"
					),
				),
				'identifier_exists'		=>	array(
					'type'		=>	'Select',
					'entryClass'	=>	'form_input_text help_identifier_exists',
					'labelString'=>	'Campo "Esiste l\'identificatore?" (feed Google)',
					'options'	=>	array(""=>"--") + self::$yesNo,
					'reverse' => 'yes',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Indicate no solo se né i codici gtin e mpn non esitono. Ex: prodotto artigianale, prodotto unico, etc.")." ".gtext("Altrimenti se i suddetti codici esistono ma non li conoscete, mettete comunque sì (e cercate tali codici).")." ".gtext("Se lasciato vuoto verrà usato il valore globale.")."</div>"
					),
				),
				'margine'		=>	array(
					'labelString'=>	'Margine (%)',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Margine in % del prodotto.")." ".gtext("Se lasciato a 0, prenderà il margine della prima categoria di appartenenza avente un margine maggiore di 0.")."</div>"
					),
				),
				'id_ruolo'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectRuoli(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'labelString'=>	'Ruolo',
				),
				'link_pagina_facebook'		=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext(self::$testoLabelSocial)."</div>"
					),
				),
				'link_pagina_twitter'		=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext(self::$testoLabelSocial)."</div>"
					),
				),
				'link_pagina_youtube'		=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext(self::$testoLabelSocial)."</div>"
					),
				),
				'link_pagina_instagram'		=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext(self::$testoLabelSocial)."</div>"
					),
				),
				'link_pagina_linkedin'		=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext(self::$testoLabelSocial)."</div>"
					),
				),
				'indirizzi_to_form_contatti'		=>	array(
					'labelString'=>	'Indirizzi email a cui verrà inviata la mail del form contatti',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Specificare un indirizzo email per riga.")."<br /><b>".gtext("Se lasciato vuoto, la mail verrà inviata all'indirizzo specificato nelle impostazioni.")."</b></div>"
					),
				),
				'tipo_estensione_url'		=>	array(
					'type'		=>	'Select',
					'entryClass'	=>	'form_input_text help_estensione',
					'labelString'=>	'Parte finale URL',
					'options'	=>	array(
						"1"	=>	".html",
						"2"	=>	"/",
					),
					'reverse' => 'yes',
					
				),
			),
		);
		
		$this->formStruct["entries"] = $this->formStruct["entries"] + $this->getLinkEntries();
		
		if ($this->formStructAggiuntivoEntries)
			$this->formStruct["entries"] = $this->formStruct["entries"] + $this->formStructAggiuntivoEntries;
		
		// Override la struttura del form
		$this->overrideFormStruct();
	}
	
	public function selectProdotti($id, $withEmpty = true)
	{
		$clean['id'] = (int)$id;
		
		$cm = new CategoriesModel();
		
		$res = $this->clear()->where(array(
				"ne" => array("id_page" => $clean['id']),
				"principale"=>"Y",
			))
			->addWhereAttivo()
			->addWhereCategoria((int)$cm->getShopCategoryId())
			->orderBy("id_order")
			->toList("id_page", "title")
			->send();
		
		if ($withEmpty)
			return array(0=>"--") + $res;
		
		return $res;
	}
	
	// Select di tuttu le pagine di una certa sezione
	public function selectPagineSezione($sezione, $withEmpty = false, $soloAttivi = true)
	{
		$res = $this->clear()
			->addWhereCategoria((int)CategoriesModel::getIdCategoriaDaSezione($sezione))
			->orderBy("pages.id_order")
			->toList("id_page", "title");
		
		if ($soloAttivi)
			$this->aWhere(array(
				"attivo" => "Y",
			));
			
		$res = $this->send();
		
		if ($withEmpty)
			return array(0=>"--") + $res;
		
		return $res;
	}
	
	public function selectTipiPagina()
	{
		return array(""=>"--") + self::$tipiPagina;
	}
	
	public function getIva($idPage)
	{
		$iva = $this->clear()->select("iva.valore")->where(array("id_page"=>(int)$idPage))->inner("iva")->on("pages.id_iva = iva.id_iva")->send();
		
		if (count($iva) > 0)
			return $iva[0]["iva"]["valore"];
		
		return 0;
	}
	
	//controlla che la pagina $id possa essere gestita dal model
	public function modificaPaginaPermessa($id)
	{
		$clean["id"] = (int)$id;
		
		if (isset($this->hModel) and isset($this->hModel->section))
		{
			//ottengo la section legata a questo model
			$section = $this->hModel->section;
			
			$page = $this->clear()->selectId($clean["id"]);
			
			if (count($page) > 0)
			{
				$pages = $this->clear()->where(array("codice_alfa" => $page["codice_alfa"]))->toList("id_page")->send();
				
				if (strcmp($section,$this->hModel->rootSectionName) !== 0)
				{
					foreach ($pages as $id_p)
					{
						//ottengo i genitori
						$parents = $this->parents((int)$id_p, false, true);
						
						//tolgo il genitore root
						array_shift($parents);
						
						foreach ($parents as $par)
						{
							if (strcmp($par["categories"]["section"],$section) === 0)
							{
								return true;
							}
						}
					}
				}
				else
				{
					foreach ($pages as $id_p)
					{
						//ottengo i genitori
						$parents = $this->parents((int)$id_p, false, true);
						
						//tolgo il genitore root
						array_shift($parents);
						
						foreach ($parents as $par)
						{
							if (strcmp($par["categories"]["section"],"") !== 0)
							{
								return false;
							}
						}
					}
				
					return true;
				}
			}
			
			return false;
		}
		
		return true;
	}
	
	public function pUpdate($id = null, $where = null)
	{
		$clean["id"] = (int)$id;
		
		return parent::update($clean["id"], $where);
	}
	
	public function buildCategorySelect()
	{
		return $this->hModel->buildSelect();
	}
	
	public function aggiornaStatoProdottiInPromozione()
	{
		Cache_Db::$skipReadingCache = true;
		
		$res = $this->clear()->where(array("in_promozione"=>"Y"))->sWhere(array("al < ?",array(date("Y-m-d"))))->send();
		
		if (v("usa_transactions"))
			$this->db->beginTransaction();
		
		foreach ($res as $r)
		{
			if (!$this->inPromozione($r["pages"]["id_page"],$r))
			{
				$this->values = array(
					"in_promozione"	=>	"N",
// 					"prezzo_promozione"	=>	$r["pages"]["price"],
				);
				
				$this->sanitize();
				$this->pUpdate($r["pages"]["id_page"]);
				
				$this->aggiornaPrezziCombinazioni($r["pages"]["id_page"]);
			}
		}
		
		if (v("usa_transactions"))
			$this->db->commit();
		
		Cache_Db::$skipReadingCache = false;
	}
	
	// Imposta l'alias della pagina controllando che non ci sia un duplicato
	public function setAlias($id)
	{
		if (isset($this->values[$this->aliaseFieldName]) && strcmp($this->values[$this->aliaseFieldName],"") === 0)
			$this->values[$this->aliaseFieldName] = sanitizeDb(encodeUrl($this->values[$this->titleFieldName]));
		
		$this->checkAliasAll($id);
	}
	
	protected function setCampoCerca($id)
	{
		if (!v("mostra_filtro_ricerca_libera_in_magazzino"))
			return;
		
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
		{
			$c = new CategoriesModel();
			
			$parents = $c->parents($record["id_c"], false, false, null, "title");
			array_shift($parents);
			
			if (count($parents) > 0)
				array_shift($parents);
			
			$stringSearchArray = array(
				htmlentitydecode($record["title"])
			);
			
			foreach ($parents as $p)
			{
				$stringSearchArray[] = htmlentitydecode($p["categories"]["title"]);
			}
			
			if (isset($record["id_marchio"]) && $record["id_marchio"])
			{
				$m = new MarchiModel();
				$stringSearchArray[] = htmlentitydecode($m->whereId($record["id_marchio"])->field("titolo"));
			}
			
			$this->sValues(array(
				"campo_cerca"	=>	implode(" ", $stringSearchArray),
			));
			
			$this->pUpdate((int)$id);
		}
	}
	
	public function update($id = null, $where = null)
	{
		$clean["id"] = (int)$id;
		
		$r = false;
		
		if (!self::$uploadFile || $this->upload("update"))
		{
			$record = $this->selectId($clean["id"]);
			
			if (count($record) > 0)
			{
				if ($this->checkAll)
				{
					$this->setAlias($id);
					
					//controllo che non esista già una pagina secondaria con la stessa categoria
					if (v("attiva_multi_categoria"))
					{
						$secondaria = $this->clear()->where(array("codice_alfa" => $record["codice_alfa"],"-id_c"=>$this->values["id_c"]))->record();
						$secondarie = $this->clear()->where(array("codice_alfa" => $record["codice_alfa"]))->toList("id_page")->send();
					}
				}
				
				// Salva informazioni meta della pagina
				$this->salvaMeta($record["meta_modificato"]);
				
				// Imposta il prezzo non ivato
				$this->setPriceNonIvato();
				
				$r = parent::update($clean["id"]);
				
				//aggiorno i permessi della pagina
				if ($r)
					$this->updatePageAccessibility($clean["id"]);
				
				if ($this->checkAll)
				{
					//e in caso la cancello
					if ($r && v("attiva_multi_categoria") && isset($secondaria) && count($secondaria) > 0)
					{
						$this->values = array(
							"id_c" => $record["id_c"],
						);
						$this->pUpdate($secondaria["id_page"]);
						
						$this->updatePageAccessibility($secondaria["id_page"]);
						
						foreach ($secondarie as $id_sec)
						{
							$this->updatePageAccessibility($id_sec);
						}
					}
				}
				
				if ($r)
				{
					// Controllo che esista il contenuto in lingua
					$this->controllaLingua($id);
					
					// Controllo che esista la combinazione
					$this->controllaCombinazioni($id);
					
					// Controllo i prezzi scontati delle combinazioni
					$this->aggiornaPrezziCombinazioni($id);
					
					$this->sincronizza($clean["id"]);
					
					// Check sitemap
					$this->controllaElementoInSitemap($clean["id"]);
					
					// Imposta il campo per la ricerca libera
					$this->setCampoCerca($clean["id"]);
				}
			}
		}
		
		return $r;
	}
	
	public function controllaCombinazioni($id)
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		Params::$automaticConversionToDbFormat = false;
		
		if (!$this->isProdotto($id))
			return;
		
		$c = new CombinazioniModel();
		$pa = new PagesattributiModel();
		
		$numeroVarianti = $pa->clear()->where(array(
			"id_page"	=>	(int)$id,
		))->rowNumber();
		
		if ((int)$numeroVarianti === 0)
		{
			$pagina = $this->selectId($id);
			
			if (!empty($pagina))
			{
				$combinazione = $c->clear()->where(array(
					"id_page"	=>	(int)$id
				))->record();
				
				$c->setValues(array(
					"id_page"	=>	$id,
					"price"		=>	$pagina["price"],
					"price_scontato"=>	$pagina["price"],
					"price_ivato"	=>	$pagina["price_ivato"],
					"price_scontato_ivato"	=>	$pagina["price_ivato"],
					"codice"	=>	$pagina["codice"],
					"peso"		=>	$pagina["peso"],
					"giacenza"	=>	$pagina["giacenza"],
					"immagine"	=>	getFirstImage($id),
					"canonical"	=>	1,
				));
				
				if (empty($combinazione))
					$c->insert();
				else
				{
					$c->aggiornaGiacenzaPaginaQuandoSalvi = false;
					$c->update($combinazione["id_c"]);
				}
			}
		}
		
		Params::$setValuesConditionsFromDbTableStruct = true;
		Params::$automaticConversionToDbFormat = true;
	}
	
	// Aggiorna i prezzi scontati delle combinazioni
	public function aggiornaPrezziCombinazioni($id)
	{
		if (!v("sconti_combinazioni_automatiche"))
			return;
		
		Params::$setValuesConditionsFromDbTableStruct = false;
		Params::$automaticConversionToDbFormat = false;
		
		if (!$this->isProdotto($id))
			return;
		
		$cModel = new CombinazioniModel();
		$clModel = new CombinazionilistiniModel();
		$pa = new PagesattributiModel();
		
		if ($this->inPromozione($id))
		{
			$pagina = $this->selectId($id);
			
			$combinazioni = $cModel->clear()->where(array(
				"id_page"	=>	(int)$id,
			))->send(false);
			
			$numeroVarianti = $pa->clear()->where(array(
				"id_page"	=>	(int)$id,
			))->rowNumber();
			
			if (!v("gestisci_sconti_combinazioni_separatamente") || ((int)$numeroVarianti === 0 && self::$aggiornaPrezziCombinazioniQuandoSalvi))
			{
				$combinazioniListini = $clModel->clear()->sWhere(array("id_c in (select id_c from combinazioni where id_page = ?)",array((int)$id)))->send(false);
				
				if (v("usa_transactions"))
					$this->db->beginTransaction();
				
				foreach ($combinazioni as $c)
				{
					$cModel->sValues(array(
						"price_scontato"		=>	self::getPrezzoScontato($pagina, $c["price"]),
						"price_scontato_ivato"	=>	self::getPrezzoScontato($pagina, $c["price_ivato"]),
					));
					
					$cModel->pUpdate($c["id_c"]);
				}
				
				foreach ($combinazioniListini as $c)
				{
					$clModel->sValues(array(
						"price_scontato"		=>	self::getPrezzoScontato($pagina, $c["price"]),
						"price_scontato_ivato"	=>	self::getPrezzoScontato($pagina, $c["price_ivato"]),
					));
					
					$clModel->pUpdate($c["id_combinazione_listino"]);
				}
				
				if (v("usa_transactions"))
					$this->db->commit();
			}
		}
		else
		{
			$this->query(array("update combinazioni set price_scontato = price, price_scontato_ivato = price_ivato where id_page = ?", array((int)$id)));
			
			$idcS = $cModel->clear()->where(array(
				"id_page"	=>	(int)$id,
			))->toList("id_c")->send();
			
			if (count($idcS) > 0)
				$this->query(array("update combinazioni_listini set price_scontato = price, price_scontato_ivato = price_ivato where id_c in (".$this->placeholdersFromArray($idcS).")",$idcS));
		}
		
		Params::$setValuesConditionsFromDbTableStruct = true;
		Params::$automaticConversionToDbFormat = true;
	}
	
	public static function getPrezzoScontato($pagina, $prezzoPieno)
	{
		$sconto = self::getPercSconto($pagina);
		
		return ($prezzoPieno - ($prezzoPieno * $sconto/100));
	}
	
	// Restituisce la percentuale di sconto
	public static function getPercSconto($page, $idC = 0, $forzaPrincipale = false)
	{
		if (v("gestisci_sconti_combinazioni_separatamente") && $idC)
		{
			if (!User::$nazione || $forzaPrincipale)
			{
				$combinazione = CombinazioniModel::g()->selectId((int)$idC);
				
				if (!empty($combinazione) && $combinazione["price"] > 0)
					return (($combinazione["price"] - $combinazione["price_scontato"]) / $combinazione["price"]) * 100;
			}
			else
			{
				$combListino = CombinazionilistiniModel::g()->clear()->where(array(
					"id_c"		=>	(int)$idC,
					"nazione"	=>	sanitizeAll(User::$nazione),
				))->record();
				
				if (!empty($combListino))
					return (($combListino["price"] - $combListino["price_scontato"]) / $combListino["price"]) * 100;
				else
					return self::getPercSconto($page, $idC, true);
			}
		}
		else
		{
			if ($page["tipo_sconto"] == "PERCENTUALE")
				return $page["prezzo_promozione"];
			else if ($page["price"] > 0)
				return (($page["price"] - $page["prezzo_promozione_ass"]) / $page["price"]) * 100;
		}
		
		return 0;
	}
	
	// Controllo che la lingua esista
	public function controllaLingua($id)
	{
		$sezione = $this->section((int)$id, true)."_detail";
		
		$this->controllaLinguaGeneric($id, "id_page", $sezione);
	}
	
	public function updatePageAccessibility($id_page)
	{
		if (!v("attiva_accessibilita_categorie"))
			return;
		
		$clean["id_page"] = (int)$id_page;
		
		$gruppi = $this->accessibility($id_page);
		
		$access = count($gruppi) > 0 ? "(".implode("),(",$this->accessibility($id_page)).")" : "--free--";
		
		$this->values = array("gruppi" => $access);
		$this->sanitize();
		$this->pUpdate((int)$id_page);
	}
	
	public function pInsert()
	{
		parent::insert();
		
		$this->lId = $this->lastId();
	}
	
	public function setPriceNonIvato()
	{
		if (v("prezzi_ivati_in_prodotti") && (isset($this->values["price_ivato"]) || isset($this->values["prezzo_promozione_ass_ivato"])) && isset($this->values["id_iva"]))
		{
			$i = new IvaModel();
			$aliquota = $i->selectId($this->values["id_iva"]);
			
			if (!empty($aliquota))
			{
				if (isset($this->values["price_ivato"]))
					$this->values["price"] = number_format(setPrice($this->values["price_ivato"]) / (1 + ($aliquota["valore"] / 100)), v("cifre_decimali"),".","");
				
				if (isset($this->values["prezzo_promozione_ass_ivato"]))
					$this->values["prezzo_promozione_ass"] = number_format(setPrice($this->values["prezzo_promozione_ass_ivato"]) / (1 + ($aliquota["valore"] / 100)), v("cifre_decimali"),".","");
			}
		}
		else
			$this->settaCifreDecimali();
	}
	
	public function insert()
	{
		$r = false;
		
		if (!self::$uploadFile || $this->upload("insert"))
		{
			$this->setAlias(0);
			
			if (!isset($this->values["codice_alfa"]))
			{
				$this->values["codice_alfa"] = md5(randString(22).microtime().uniqid(mt_rand(),true));
			}
			
			// Salva informazioni meta della pagina
			$this->salvaMeta();
			
			// Imposta il prezzo non ivato
			$this->setPriceNonIvato();
			
			$r = parent::insert();
			
			$this->lId = $this->lastId();
			
			//aggiorno i permessi della pagina
			if ($r)
			{
				// Controllo che esista il contenuto in lingua
				$this->controllaLingua($this->lId);
				
				// Controllo che esista la combinazione
				$this->controllaCombinazioni($this->lId);
				
				// Controllo i prezzi scontati delle combinazioni
				$this->aggiornaPrezziCombinazioni($this->lId);
				
				$this->updatePageAccessibility($this->lId);
				
				// Aggiungi tutti i prodotti sempre come accessori
				$this->aggiungiAccesori($this->lId);
				
				// Check sitemap
				$this->controllaElementoInSitemap($this->lId);
				
				// Imposta il campo per la ricerca libera
				$this->setCampoCerca($this->lId);
			}
		}
		
		return $r;
	}
	
	public function checkDataPerSitemap($id)
	{
		return $this->clear()->select("distinct pages.codice_alfa,pages.id_page,categories.id_c,pages.data_ultima_modifica,pages.priorita_sitemap")
			->inner("categories")->on("categories.id_c = pages.id_c")
			->where(array(
				"pages.id_page"	=>	(int)$id,
				"pages.add_in_sitemap"=>	"Y",
// 				"categories.add_in_sitemap_children"	=>	"Y",
			))
			->addWhereAttivo()
			->addWhereAttivoCategoria()
			->addWhereCategoriaInstallata()
			->addWhereOkSitemap()
			->first();
	}
	
	public function aggiungiAllaSitemap($pagina)
	{
		if (v("permetti_gestione_sitemap"))
		{
			$sm = new SitemapModel();
			
			$sm->setValues(array(
				"id_page"	=>	$pagina["pages"]["id_page"],
				"id_c"		=>	$pagina["categories"]["id_c"],
				"ultima_modifica"	=>	$pagina["pages"]["data_ultima_modifica"],
				"priorita"	=>	$pagina["pages"]["priorita_sitemap"],
			));
			
			try
			{
				$sm->insert();
			}
			catch (Exception $e)
			{
				
			}
		}
	}
	
// 	public function isActiveAlias($alias, $lingua = null)
// 	{
// 		if (strcmp($alias,"") === 0)
// 			return 0;
// 		
// 		$clean["alias"] = sanitizeAll($alias);
// 		
// 		$res = $this->clear()->select("id_page")->where(array(
// 			$this->aliaseFieldName=>$clean['alias'],
// 			"pages.temp"		=>	0,
// 			"pages.cestino"		=>	0,
// 		));
// 		
// 		if (!User::$adminLogged)
// 			$this->aWhere(array(
// 				"attivo"=>"Y",
// 			));
// 		
// 		$res = $this->send();
// 		
// 		if (count($res) > 0)
// 		{
// 			return true;
// 		}
// 		else
// 		{
// 			// Cerco la traduzione
// 			$ct = new ContenutitradottiModel();
// 			
// 			$res = $ct->clear()->select("pages.id_page")->inner(array("page"))->where(array("alias"=>$clean['alias'],"pages.attivo"=>"Y"));
// 			
// 			if ($lingua)
// 			{
// 				$ct->aWhere(array(
// 					"lingua"	=>	sanitizeAll($lingua),
// 				));
// 			}
// 			
// 			$res = $ct->send();
// 			
// 			if (count($res) > 0)
// 			{
// 				return true;
// 			}
// 		}
// 		
// 		return false;
// 	}
	
	public function getIdCombinazioneCanonical($idPage)
	{
		if (isset(self::$arrayIdCombinazioni[$idPage]))
			return self::$arrayIdCombinazioni[$idPage];
		
		$c = new CombinazioniModel();
		
		$orderBy = VariabiliModel::combinazioniLinkVeri() ? "canonical desc,id_order" : "price";
		
		self::$arrayIdCombinazioni[$idPage] = (int)$c->clear()->select("combinazioni.id_c")->where(array(
			"id_page"	=>	(int)$idPage,
		))->orderBy($orderBy)->limit(1)->field("id_c");
		
		return self::$arrayIdCombinazioni[$idPage];
	}
	
	public function getIdFromAlias($alias, $lingua = null)
	{
		$clean['alias'] = sanitizeAll($alias);
		
		if (VariabiliModel::combinazioniLinkVeri())
		{
			$res = $this->clear()->select("pages.id_page, combinazioni.id_c")->inner(array("combinazioni"))->where(array(
				"pages.temp"		=>	0,
				"pages.cestino"		=>	0,
			))->limit(1);
			
			$tableAlias = "pages";
			
			if ($lingua && $lingua != LingueModel::getPrincipaleFrontend())
			{
				$this->inner("contenuti_tradotti")->on(array("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = ?",array(sanitizeDb($lingua))));
				$tableAlias = "contenuti_tradotti";
			}
			
			if (v("usa_alias_combinazione_in_url_prodotto") && $lingua)
				$this->inner("combinazioni_alias")->on(array("combinazioni_alias.id_c = combinazioni.id_c and combinazioni_alias.lingua = ?",array(sanitizeDb($lingua))));
			
			if (!User::$adminLogged)
				$this->aWhere(array(
					"pages.attivo"=>"Y",
				));
			
			$bindedValues = array();
			
			if (v("usa_alias_combinazione_in_url_prodotto"))
			{
				$sWhere = "(
					concat($tableAlias.alias,'-',combinazioni_alias.alias_attributi,'-',combinazioni.codice) = ? OR 
					concat($tableAlias.alias,'-',combinazioni_alias.alias_attributi) = ? OR 
					concat($tableAlias.alias,'-',combinazioni.codice) = ? OR 
					$tableAlias.alias = ?
				)";
				
				$bindedValues = array($clean['alias'], $clean['alias'], $clean['alias'], $clean['alias']);
			}
			else
			{
				$sWhere = "(
					concat($tableAlias.alias,'-',combinazioni.codice) = ? OR 
					$tableAlias.alias = ?
				)";
				
				$bindedValues = array($clean['alias'], $clean['alias']);
			}
			
			$this->sWhere(array($sWhere, $bindedValues));
			
			$res = $this->toList("pages.id_page", "combinazioni.id_c")->send();
			
			if (count($res) > 0)
			{
				self::$IdCombinazione = reset($res);
				
				return array_keys($res);
			}
		}
// 		else
// 		{
			$res = $this->clear()->select("pages.id_page")->where(array(
				"alias"				=>	$clean['alias'],
				"pages.temp"		=>	0,
				"pages.cestino"		=>	0,
			))->toList("pages.id_page");
			
			if (!User::$adminLogged)
				$this->aWhere(array(
					"attivo"=>"Y",
				));
			
			$res = $this->send();
			
			if (count($res) > 0)
			{
				self::$IdCombinazione = $this->getIdCombinazioneCanonical($res[0]);
				
				return $res;
			}
			else
			{
				// Cerco la traduzione
				$ct = new ContenutitradottiModel();
				
				$res = $ct->clear()->select("pages.id_page")
					->inner(array("page"))
					->where(array(
						"alias"=>$clean['alias'],
						"pages.temp"		=>	0,
						"pages.cestino"		=>	0,
					))
					->toList("pages.id_page");
				
				if (!User::$adminLogged)
					$this->aWhere(array(
						"pages.attivo"=>"Y",
					));
				
				if ($lingua)
				{
					$ct->aWhere(array(
						"lingua"	=>	sanitizeAll($lingua),
					));
				}
				
				$res = $ct->send();
				
				if (count($res) > 0)
				{
					self::$IdCombinazione = $this->getIdCombinazioneCanonical($res[0]);
					
					return $res;
				}
			}
// 		}
		
		return array();
	}
	
	public function isActive($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$field = $this->clear()->selectId($clean["id_page"]);
		
		if (count($field) > 0 and $field["attivo"] === "Y")
		{
			return true;
		}
		return false;
	}
	
	public function recordExists($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$res = $this->clear()->where(array("id_page"=>$clean["id_page"]))->send();
		
		if (count($res) > 0)
		{
			return true;
		}
		return false;
	}
	
	//restituisce titolo più alias
	public function getTitle($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$field = $this->clear()->selectId($clean["id_page"]);
		
		if (count($field) > 0)
		{
			return $field[$this->titleFieldName]." <br /><span style='font-size:10px;font-style:italic;'>(alias: ".$field[$this->aliaseFieldName].")</span><br />codice: <b>".$field["codice"]."</b><br />prezzo: <b>".setPriceReverse($field["price"])."€</b>";
		}
		return '';
	}
	
	//restituisce solo titolo
	public function getSimpleTitle($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$field = $this->clear()->selectId($clean["id_page"]);
		
		if (count($field) > 0)
		{
			return $field[$this->titleFieldName];
		}
		return '';
	}
	
	public function getAlias($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$field = $this->clear()->selectId($clean["id_page"]);
		
		if (count($field) > 0)
		{
			return $field["alias"];
		}
		return '';
	}
	
	//get the parents
	public function parents($id, $onlyIds = true, $onlyParents = true, $lingua = false, $fields = null)
	{
		$clean["id"] = (int)$id;
		
		$this->clear()->where(array($this->_idFields=>$clean["id"]));
		
		if ($fields)
			$this->select($fields);
		
		if ($lingua)
		{
			$f = $fields ? $fields : $this->_tables.".*,contenuti_tradotti.*";
			
			$this->addJoinTraduzione(null, "contenuti_tradotti", false)->select($f);
		}
		
		$res = $this->send();
		
		if (count($res) > 0)
		{
			$clean['id_c'] = $res[0][$this->_tables]["id_c"];
			$c = new CategoriesModel();
			
			$parents = $c->parents($clean['id_c'],$onlyIds,false, $lingua, $fields);
			
			if ($onlyParents)
			{
				return $parents;
			}
			else
			{
				if ($onlyIds)
				{
					$parents[] = $res[0][$this->_tables][$this->_idFields];
				}
				else
				{
					$parents[] = $res[0];
				}
				
				return $parents;
			}
		}
		
		return array();
	}
	
	public function getCategory($id)
	{
		$clean["id"] = (int)$id;
		
		$res = $this->clear()->where(array("id_page"=>$clean["id"]))->toList("id_c")->send();

		if (count($res) > 0)
		{
			return $res[0];
		}
		return 0;
	}
	
	public static function getEstensioneUrl($tipo)
	{
		return ($tipo == 1) ? ".html" : "/";
	}
	
	//get the URL of a content
	public function getUrlAlias($id, $lingua = null, $idC = 0)
	{
		$c = new CombinazioniModel();
		
		$lingua = isset($lingua) ? $lingua : Params::$lang;
		
		$clean["id"] = (int)$id;
		
		$urlArray = array();
		
		$isProdotto = isProdotto($clean["id"]);
		
		$estensionePagina = ".html";
		
		if (v("mostra_categorie_in_url_prodotto") || !$isProdotto)
		{
			$parents = $this->parents($clean["id"], false, false, $lingua);
			
			//remove the root node
			array_shift($parents);
			
			$indiceNode = 0;
			
			foreach ($parents as $node)
			{
				if (isset($node["categories"][$this->aliaseFieldName]))
				{
					if (isset($node["contenuti_tradotti"][$this->aliaseFieldName]) && $node["contenuti_tradotti"][$this->aliaseFieldName])
						$urlArray[] = $node["contenuti_tradotti"][$this->aliaseFieldName];
					else
						$urlArray[] = $node["categories"][$this->aliaseFieldName];
				}
				else
				{
					if (isset($node["contenuti_tradotti"][$this->aliaseFieldName]) && $node["contenuti_tradotti"][$this->aliaseFieldName])
						$urlArray[] = $node["contenuti_tradotti"][$this->aliaseFieldName].$c->getAlias($clean["id"], $lingua, $idC);
					else
						$urlArray[] = $node[$this->_tables][$this->aliaseFieldName].$c->getAlias($clean["id"], $lingua, $idC);
					
					if ((int)$indiceNode === (count($parents)-1) && isset($node[$this->_tables]["tipo_estensione_url"]) && v("permetti_di_selezionare_estensione_url_pagine"))
						$estensionePagina = self::getEstensioneUrl($node[$this->_tables]["tipo_estensione_url"]);
				}
				
				$indiceNode++;
			}
		}
		else
		{
			$page = self::getPageDetails($clean["id"], $lingua);
			
			if (!empty($page))
				$urlArray[] = field($page, "alias").$c->getAlias($clean["id"], $lingua, $idC);
		}
		
		$ext = Parametri::$useHtmlExtension ? $estensionePagina : null;
		
		// Appendo il marchio se presente
		if (v("usa_marchi") && v("aggiungi_marchio_in_url_prodotto"))
		{
			$m = new MarchiModel();
			
			$res = $m->clear()->addJoinTraduzione($lingua)->inner("pages")->on("pages.id_marchio = marchi.id_marchio")->where(array(
				"pages.id_page"	=>	$this->getPrincipale((int)$id),
			))->first();
			
			if (count($res) > 0 && mfield($res,"alias"))
				array_unshift($urlArray, mfield($res,"alias"));
		}
		
		return implode("/",$urlArray).$ext;
	}
	
	public function categoriesS($id)
	{
		$clean["id"] = $this->getPrincipale((int)$id);
		
		$record = $this->selectId($clean['id']);
		
		if (count($record) > 0)
		{
			$where = array(
				"pages.codice_alfa"=>$record["codice_alfa"],
			);
			
			$res = CategoriesModel::g(false)->clear()->select("categories.title")
				->left("pages")->on("pages.id_c = categories.id_c")
				->where($where)
				->orderBy("categories.lft")
				->toList("categories.title")
				->send();
			
			$html = "<i style='font-size:12px;'><b>".implode("<br />", $res)."</b></i>";
			
			if (v("attiva_categorie_in_prodotto"))
			{
				$res = PagescategoriesModel::g()->clear()
					->select("categories.title")
					->inner(array("categoria"))->where(array(
						"pages_categories.id_page"	=>	(int)$clean["id"],
					))->toList("categories.title")->send();
				
				if (count($res) > 0)
					$html .= "<br /><i style='font-size:12px;'>".implode("<br />", $res)."</i>";
// 				$ids = PagescategoriesModel::g()->select("id_c")->where(array(
// 					"id_page"	=>	(int)$clean["id"],
// 				))->toList("id_c")->send();
// 				
// 				if (count($ids) > 0)
// 					$where = array(
// 						"OR"	=>	array(
// 							"pages.codice_alfa"=>$record["codice_alfa"],
// 							"in"	=>	array(
// 								"categories.id_c"	=>	$ids,
// 							),
// 						)
// 					);
			}
			
			return $html;
		}
		
		return "";
	}
	
	public function pDel($id = null, $whereClause = null)
	{
		return parent::del($id, $whereClause);
	}
	
	public function del($id = null, $whereClause = null)
	{
		$clean['id'] = $this->getPrincipale((int)$id);
		
		if ($this->checkOnDeleteIntegrity($clean['id'], $whereClause))
		{
			$record = $this->selectId($clean['id']);
			
			if (count($record) > 0)
			{
				//cancello le immagini relative al prodotto
				$im = new ImmaginiModel();
				$res = $im->select()->where(array('id_page'=>$clean['id']))->toList('id_immagine','immagine')->send();
				foreach ($res as $id_imm => $fileName)
				{
		// 			$im->files->removeFile($fileName);
					$im->del($id_imm);
				}
				
				//cancello i prodotti correlati
				$c = new CorrelatiModel();
				$c->del(null,"id_page=".$clean['id']);
				$c->del(null,"id_corr=".$clean['id']);
				
				//cancello il prodotto nel carrello
				$cart = new CartModel();
				$cart->del(null, "id_page=".$clean['id']);
				
				//cancello gli attributi
				$attr = new PagesattributiModel();
				$attr->del(null,"id_page='".$clean["id"]."'");
				
				//cancello gli scaglioni
				$pcv = new ScaglioniModel();
				$pcv->del(null,"id_page='".$clean["id"]."'");
				
				//cancello gli scaglioni
				$pcv = new LayerModel();
				$pcv->del(null,"id_page='".$clean["id"]."'");
				
				//cancello le pagine correlate
				$c = new PagespagesModel();
				$c->del(null,"id_page=".$clean['id']);
				$c->del(null,"id_corr=".$clean['id']);
				
				CombinazioniModel::$ricreaCombinazioneQuandoElimini = false;
				
	// 			parent::del($clean['id']);
				parent::del(null, "codice_alfa = '".$record["codice_alfa"]."'");
			}
		}
	}
	
	public function getInputOrdinamento($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		if (isset(self::$currentRecord))
		{
			$res = self::$currentRecord;
		}
		else
		{
			$res = $this->clear()->select()->where(array('id_page'=>$clean['id_page']))->send();
		}
	
		$id_order = 0;
		$id_p = 0;
	
		if (count($res) > 0)
		{
			$id_order = $res[0]["pages"]["id_order"];
			$id_p = $res[0]["pages"]["id_page"];
		}
	
		return "<input class='input_ordinamento' style='width:35px;' rel='$id_p' type='text' name='id_order' value='$id_order'>";
	}
	
	public function getPubblicatoCheckbox($id_page)
	{
		$clean['id_page'] = (int)$id_page;

		$res = $this->clear()->select()->where(array('id_page'=>$clean['id_page']))->send();
		
		if (count($res) > 0)
		{
			self::$currentRecord = $res;
			return Html_Form::checkbox('attivo',$res[0]['pages']['attivo'],'Y','attivo_checkbox',$res[0]['pages']['id_page']).'<span class="loading_gif_del"><img src="'.Url::getFileRoot()."Public/Img/Icons/loading4.gif".'" /></span>';
		}
		self::$currentRecord = null;
		return "";
	}
	
	public function getInEvidenzaCheckbox($id_page)
	{
		$clean['id_page'] = (int)$id_page;

		if (isset(self::$currentRecord))
		{
			$res = self::$currentRecord;
		}
		else
		{
			$res = $this->clear()->select()->where(array('id_page'=>$clean['id_page']))->send();
		}

		if (count($res) > 0)
		{
			return Html_Form::checkbox('attivo',$res[0]['pages']['in_evidenza'],'Y','in_evidenza_checkbox',$res[0]['pages']['id_page']).'<span class="loading_gif_del"><img src="'.Url::getFileRoot()."Public/Img/Icons/loading4.gif".'" /></span>';
		}
		return "";
	}
	
	public function getThumb($id_page)
	{
		$im = new ImmaginiModel();
		
		$clean['id_page'] = (int)$id_page;
		
		$principale = $this->getPrincipale($clean['id_page']);
		
		$fileName = $im->getFirstImage($principale);
		
		if (Files_Upload::isJpeg(Files_Upload::sFileExtension($fileName)))
			return "<img src='".Url::getFileRoot()."thumb/immagineinlistaprodotti/".$principale."/$fileName' />";
		else
			return "<img src='".Domain::$publicUrl."/images/contents/$fileName' />";
	}
	
	public function inPromozioneText($id_page)
	{
		$clean['id_page'] = (int)$id_page;
		
		$res = $this->clear()->select()->where(array('id_page'=>$clean['id_page']))->send();
		
		if (count($res) > 0)
		{
			if (strcmp($res[0]["pages"]["in_promozione"],"Y") === 0)
			{
				$dal = getTimeStampComplete($res[0]["pages"]["dal"]);
				$al = getTimeStampComplete($res[0]["pages"]["al"]) + 86400;
				
				$now = time();
				
				if ($now >= $dal and $now <= $al)
				{
					return "<span class='text text-success'><b>In corso</b></span><br />(".smartDate($res[0]["pages"]["dal"])." / ".smartDate($res[0]["pages"]["al"]).")";
				}
				if ($now < $dal)
				{
					return "<b>Non ancora partita</b><br />(".smartDate($res[0]["pages"]["dal"])." / ".smartDate($res[0]["pages"]["al"]).")";
				}
				else
				{
					return "promozione scaduta";
				}
			}
		}
		return "no";
	}
	
	public function inPromozione($id_page, $page = null)
	{
		$clean['id_page'] = (int)$id_page;
		
		if (isset($page))
		{
			$record = $page["pages"];
// 			$res[0] = $page;
		}
		else
		{
			$record = $this->selectId($id_page);
// 			$res = $this->clear()->select("dal,al,in_promozione")->where(array('id_page'=>$clean['id_page']))->send();
		}
		
// 		if (count($res) > 0)
		if (!empty($record))
		{
			if (strcmp($record["in_promozione"],"Y") === 0)
			{
				$dal = getTimeStampComplete($record["dal"]);
				$al = getTimeStampComplete($record["al"]) + 86400;
				
				$now = time();
				
				if ($now >= $dal and $now <= $al)
				{
					return true;
				}
			}
		}
		return false;
	}
	
	public function inPromozioneTot($id_page, $page = null)
	{
		$promo = $this->inPromozione($id_page, $page);
		
		$page = isset($page) ? $page["pages"] : $this->selectId($id_page);
	
		$classe = in_array($page["id_c"], User::$categorieInClasseSconto) ? true : false;
		
		return ($promo || $classe);
	}
	
	//controlla che sia l'id principale e in caso stoppa l'esecuzione e dai errore
	public function checkPrincipale($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$record = $this->clear()->selectId($clean["id_page"]);
		
		if (count($record) > 0 and strcmp($record["principale"],"Y") === 0)
		{
			
		}
		else
		{
			die("non permesso");
		}
		
	}
	
	public function principale($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$record = $this->selectId($clean["id_page"]);
		
		if (count($record) > 0)
		{
			if (strcmp($record["principale"],"Y") === 0)
			{
				return true;
			}
		}
		
		return false;
	}
	
	public function getPrincipale($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$record = $this->selectId($clean["id_page"]);
		
		if (count($record) > 0)
		{
			if (strcmp($record["principale"],"Y") === 0)
			{
				return $clean["id_page"];
			}
			else
			{
				$res = $this->clear()->where(array("codice_alfa"=>$record["codice_alfa"],"principale"=>"Y"))->toList("id_page")->send();
				
				if (count($res) > 0)
				{
					return $res[0];
				}
				else
				{
					$res = $this->clear()->inner("immagini")->using("id_page")->where(array("codice_alfa"=>$record["codice_alfa"]))->orderBy("pages.id_page")->toList("pages.id_page")->send();
					
					return $res[0];
				}
			}
		}
		
		return 0;
	}
	
	public function checkDates()
	{
		foreach ($this->values as $key => $value)
		{
			if (strcmp(nullToBlank($value),"0000-00-00") === 0)
			{
				$this->delFields($key);
			}
		}
	}
	
	public function sincronizza($id_page)
	{
		if (!v("attiva_multi_categoria"))
			return;
		
		Params::$setValuesConditionsFromDbTableStruct = false;
		
		$clean["id_page"] = (int)$id_page;
		
		$record = $this->selectId($clean["id_page"]);
		
		if (count($record) > 0)
		{
// 			if (strcmp($record["principale"],"Y") === 0)
// 			{
				$this->values = $record;
				
				$this->delFields("principale");
				$this->delFields("id_c");
				
				$this->checkDates();
				
				$this->delFields("gruppi");
				$this->delFields("id_page");
				$this->delFields("id_order");
				
				$this->sanitize();
				
				$this->pUpdate(null, "codice_alfa = '".$record["codice_alfa"]."'");
// 			}
		}
	}
	
	public function incategoria($id_page, $id_c)
	{
		$clean["id_page"] = (int)$id_page;
		$clean["id_c"] = (int)$id_c;
		
		$record = $this->selectId($clean["id_page"]);
		
		if (count($record) > 0)
		{
			$this->values = $record;
			
			$this->values["principale"] = "N";
			$this->values["id_c"] = $clean["id_c"];

			$this->checkDates();
			
			$this->delFields("gruppi");
			$this->delFields("id_page");
			$this->delFields("id_order");
			
			$this->pInsert();
			
			$this->updatePageAccessibility($this->lId);
		}
		
		return false;
	}
	
	public function hasCombinations($id_page, $personalizzazioni = true)
	{
		$clean['id_page'] = (int)$id_page;
		
		$c = new CombinazioniModel();
		
		$res = $c->clear()->where(array(
			"id_page"=>$clean['id_page'],
		))->sWhere("(col_1 != 0 OR col_2 != 0 OR col_3 != 0 OR col_4 != 0 OR col_5 != 0 OR col_6 != 0 OR col_7 != 0 OR col_8 != 0)")->rowNumber();
		
		if ($res > 0)
			return true;
		
		if ($personalizzazioni)
		{
			$pp = new PagespersonalizzazioniModel();
			
			return $pp->clear()->where(array(
				"id_page"	=>	$clean['id_page']
			))->rowNumber();
		}
		
		return false;
	}

	//controlla l'accesso alla pagina e restituisce vero o falso
	public function check($id_page)
	{
		if (!v("attiva_accessibilita_categorie"))
			return true;
		
		$clean['id_page'] = (int)$id_page;
		
		$parents = $this->parents($clean['id_page']);
		
		$lId = $parents[(count($parents)-1)];
		
		$c = new CategoriesModel();

		return $c->check($lId);
	}
	
	public function accessibility($id_page)
	{
		$clean['id_page'] = (int)$id_page;
		
		$parents = $this->parents($clean['id_page']);
		
		//elimino la categoria root
		array_shift($parents);
		
		$gc = new ReggroupscategoriesModel();
		
		$gruppi = array();
		
		foreach ($parents as $idP)
		{
			$gr = $gc->clear()->select("reggroups.name")->inner("reggroups")->using("id_group")->where(array("id_c"=>(int)$idP))->toList("reggroups.name")->send();
			
			if (count($gr) > 0)
			{
				$gruppi = $gr;
			}
// 			$gruppi = $gr;
		}
		
		return array_unique($gruppi);
	}
	
	public function getAccessibilityWhere()
	{
		if (!v("attiva_accessibilita_categorie"))
			return array();
		
		$temp = array();
			
		$count = 1;
		$temp["gruppi"] = "--free--";
		
		foreach (User::$groups as $gr)
		{
			$sign = str_repeat("-",$count);
			$temp[$sign."gruppi"] = "like '%($gr)%'";
			
			$count++;
		}
		
		$where = array(
		
			"OR" => $temp,
		
		);
		
		return $where;
	}
	
	public function isProdotto($id_page)
	{
		$clean['id_page'] = (int)$id_page;
		
		$section = $this->section($clean['id_page'], true);
		
		if (strcmp($section,Parametri::$nomeSezioneProdotti) === 0)
		{
			return true;
		}
		
		return false;
	}
	
	//get the section
	public function section($id_page, $firstElement = false)
	{
		$clean['id_page'] = (int)$id_page;
		
		$parents = $this->parents($clean['id_page'], false);
		
		//elimino la categoria root
		array_shift($parents);
		
		$section = "";
		
		foreach ($parents as $p)
		{
			if (strcmp($p["categories"]["section"],"") !== 0)
			{
				$section = $p["categories"]["section"];
			}
			
			if ($firstElement)
			{
				return $section;
			}
		}
		
		return $section;
	}
	
	public function prezzoMinimoDisplay($id_page)
	{
		$prezzoMinimo = $this->prezzoMinimo($id_page);
		
		if (v("prezzi_ivati_in_prodotti"))
		{
			$iva = $this->getIva($id_page);
			
			$prezzoMinimo = $prezzoMinimo + ($prezzoMinimo * (float)$iva / 100);
		}
		
		return setPriceReverse($prezzoMinimo);
	}
	
	public function prezzoMinimo($id_page, $forzaPrincipale = false)
	{
		$clean['id_page'] = (int)$id_page;
		
		$c = new CombinazioniModel();
		
		if (!User::$nazione || $forzaPrincipale)
		{
			// Listino principale
			if (VariabiliModel::combinazioniLinkVeri())
			{
				$c->clear()->select("price as PREZZO_MINIMO")->where(array(
					"id_page"	=>	$clean['id_page'],
				))->orderBy("canonical desc,id_order")->limit(1);
				
				if (self::$IdCombinazione)
					$c->aWhere(array(
						"id_c"	=>	(int)self::$IdCombinazione,
					));
				
				$res = $c->send();
				
				if (count($res) > 0)
					return $res[0]["combinazioni"]["PREZZO_MINIMO"];
			}
			else
			{
				$c->clear()->select("min(price) as PREZZO_MINIMO")->where(array(
					"id_page"	=>	$clean['id_page'],
				));
				
				if (self::$IdCombinazione)
					$c->aWhere(array(
						"id_c"	=>	(int)self::$IdCombinazione,
					));
				
				$res = $c->send();
				
				if (count($res) > 0)
					return $res[0]["aggregate"]["PREZZO_MINIMO"];
			}
		}
		else
		{
			// Listino nazione
			if (VariabiliModel::combinazioniLinkVeri())
			{
				$c->clear()->select("combinazioni_listini.price as PREZZO_MINIMO")->inner(array("listini"))->where(array(
					"id_page"	=>	$clean['id_page'],
					"combinazioni_listini.nazione"	=>	sanitizeAll(User::$nazione),
				))->orderBy("combinazioni.canonical desc,combinazioni.id_order")->limit(1);
				
				if (self::$IdCombinazione)
					$c->aWhere(array(
						"id_c"	=>	(int)self::$IdCombinazione,
					));
				
				$res = $c->send();
				
				if (count($res) > 0 && isset($res[0]["combinazioni_listini"]["PREZZO_MINIMO"]) && $res[0]["combinazioni_listini"]["PREZZO_MINIMO"])
					return $res[0]["combinazioni_listini"]["PREZZO_MINIMO"];
				else
					return $this->prezzoMinimo($clean['id_page'], true);
			}
			else
			{
				$c->clear()->select("min(combinazioni_listini.price) as PREZZO_MINIMO")->inner(array("listini"))->where(array(
					"id_page"	=>	$clean['id_page'],
					"combinazioni_listini.nazione"	=>	sanitizeAll(User::$nazione),
				));
				
				if (self::$IdCombinazione)
					$c->aWhere(array(
						"combinazioni.id_c"	=>	(int)self::$IdCombinazione,
					));
				
				$res = $c->send();
				
				if (count($res) > 0 && isset($res[0]["aggregate"]["PREZZO_MINIMO"]) && $res[0]["aggregate"]["PREZZO_MINIMO"])
					return $res[0]["aggregate"]["PREZZO_MINIMO"];
				else
					return $this->prezzoMinimo($clean['id_page'], true);
			}
		}
		
		return 0;
	}
	
	public static function pesoMinimo($id_page)
	{
		$clean['id_page'] = (int)$id_page;
		
		// Listino principale
		$c = new CombinazioniModel();
		
		$res = $c->clear()->select("min(peso) as PESO_MINIMO")->where(array(
			"id_page"	=>	$clean['id_page'],
		))->send();
		
		if (count($res) > 0)
			return $res[0]["aggregate"]["PESO_MINIMO"];
		
		return 0;
	}
	
	public function linklingua($record, $lingua)
	{
		return $this->linklinguaGeneric($record["pages"]["id_page"], $lingua, "id_page");
	}
	
	public function getDocumenti($id, $lingua = null, $sWhere = "")
	{
		if (!isset($lingua))
			$lingua = Params::$lang;
		
		$d = new DocumentiModel();
		
		$d->clear()->addJoinTraduzione()->select("distinct documenti.id_doc,documenti.*,tipi_documento.*,contenuti_tradotti.*")->left(array("tipo"))->where(array(
			"id_page"	=>	(int)$id,
		));
		
		$aWhere = array(
			"OR"	=>	array(
				"lingua" => "tutte",
				" lingua" => sanitizeDb($lingua),
			),
		);
		
		if (v("attiva_gruppi_documenti"))
			$d->addAccessoGruppiWhereClase();
		
		if (v("attiva_altre_lingue_documento"))
		{
			$d->left(array("lingue"));
			
			// Includi
			$aWhere["OR"]["AND"] = array(
				"documenti_lingue.lingua"	=>	sanitizeDb($lingua),
				"documenti_lingue.includi"	=>	1,
			);
			
			// Escludi
// 			$d->sWhere("documenti.id_doc not in (select documenti_lingue.id_doc from documenti_lingue inner join documenti on documenti_lingue.id_doc = documenti.id_doc where documenti.id_page = ".(int)$id." and documenti_lingue.id_doc is not null and documenti_lingue.lingua = '".sanitizeDb($lingua)."' and documenti_lingue.includi = 0)");
			
			$d->sWhere(array(
				"documenti.id_doc not in (select documenti_lingue.id_doc from documenti_lingue inner join documenti on documenti_lingue.id_doc = documenti.id_doc where documenti.id_page = ".(int)$id." and documenti_lingue.id_doc is not null and documenti_lingue.lingua = ? and documenti_lingue.includi = 0)",
				array(
					sanitizeDb($lingua)
				)
			));
		}
		
		$d->aWhere($aWhere);
		
		if ($sWhere)
			$d->sWhere($sWhere);
		
		$res = $d->orderBy("documenti.id_order")->send();
		
// 		echo $d->getQuery();die();
		
		return $res;
	}
	
	public static function getAttributoDaCol($idPage, $col)
	{
		$pa = new PagesattributiModel();
		
		$res = $pa->clear()->select("attributi.*")->inner(array("attributo"))->where(array(
			"id_page"	=>	(int)$idPage,
			"colonna"	=>	sanitizeAll($col),
		))->send();
		
		if (count($res) > 0)
			return $res[0]["attributi"];
		
		return array();
	}
	
	public static function isAttributoTipo($idPage, $col, $tipo)
	{
		$attributo = self::getAttributoDaCol($idPage, $col);
		
		if (!empty($attributo) && $attributo["tipo"] == $tipo)
			return true;
		
		return false;
	}
	
	public static function isRadioAttributo($idPage, $col)
	{
		return self::isAttributoTipo($idPage, $col, "RADIO");
	}
	
	public function selectCaratteristiche($id_page)
	{
		$clean['id'] = (int)$id_page;
		
		$orderByCaratteristiche = v("caratteristiche_in_tab_separate") ? "tipologie_caratteristiche.id_order, pages_caratteristiche_valori.id_order" : "pages_caratteristiche_valori.id_order" ;
		
		$pcv = new PagescarvalModel();
		
		return $pcv->clear()->select("caratteristiche_valori.*,caratteristiche.*,caratteristiche_tradotte.*,caratteristiche_valori_tradotte.*,tipologie_caratteristiche.*,tipologie_caratteristiche_tradotte.*")
			->inner("caratteristiche_valori")->on("caratteristiche_valori.id_cv = pages_caratteristiche_valori.id_cv")
			->inner("caratteristiche")->on("caratteristiche.id_car = caratteristiche_valori.id_car")
			->left("tipologie_caratteristiche")->on("tipologie_caratteristiche.id_tipologia_caratteristica = caratteristiche.id_tipologia_caratteristica")
			->addJoinTraduzione(null, "caratteristiche_tradotte", false, (new CaratteristicheModel()))
			->addJoinTraduzione(null, "caratteristiche_valori_tradotte", false, (new CaratteristichevaloriModel()))
			->addJoinTraduzione(null, "tipologie_caratteristiche_tradotte", false, (new TipologiecaratteristicheModel()))
			->orderBy("pages_caratteristiche_valori.id_order")
			->where(array(
				"pages_caratteristiche_valori.id_page"=>$clean['id']
			))
			->orderBy($orderByCaratteristiche)
			->send();
	}
	
	public function selectAttributi($id_page)
	{
		$clean['id'] = (int)$id_page;
		
		$lingua = Params::$lang;
		
		$pa = new PagesattributiModel();
		$cm = new CombinazioniModel();
		
		//estraggo gli attributi e i loro valori per creare le select per l'utente
		$colonne = $pa->getNomiColonne($clean['id']);
		
		$lista_valori_attributi = array();
		
		foreach ($colonne as $c => $name)
		{
			$temp = array();
			
			$resValoriAttributi = $cm->clear()
								->select("combinazioni.$c,attributi_valori.titolo,attributi_valori.immagine,attributi_valori.colore,contenuti_tradotti.titolo,attributi.tipo")
								->inner("attributi_valori")->on("attributi_valori.id_av = combinazioni.$c")
								->inner("attributi")->on("attributi.id_a = attributi_valori.id_a")
								->addJoinTraduzione(null, "contenuti_tradotti", false, (new AttributivaloriModel()))
								->where(array("id_page"=>$clean['id']))
								->orderBy("attributi_valori.id_order")
								->groupBy("combinazioni.$c,attributi_valori.id_av")
								->send();
			
			$arrayCombValori = array();
			
			$tipo = "TENDINA";
			
			if (count($resValoriAttributi) > 0)
			{
				$tipo = $resValoriAttributi[0]["attributi"]["tipo"];
				
				$temp = array();
				
				if ($tipo == "TENDINA" || $tipo == "IMMAGINE" || $tipo == "COLORE")
				{
					if (!v("primo_attributo_selezionato"))
						$temp = array("0" => $name);
				}
			}
			
			foreach ($resValoriAttributi as $rva)
			{
				if ($tipo == "RADIO")
					$arrayCombValori[$rva["combinazioni"][$c]] = "<span class='variante_radio_valore ".v("classe_variante_radio")."'>".avfield($rva, "titolo")."</span>";
				else if ($tipo == "IMMAGINE")
					$arrayCombValori[$rva["combinazioni"][$c]] = $rva["attributi_valori"]["immagine"];
				else if ($tipo == "COLORE")
					$arrayCombValori[$rva["combinazioni"][$c]] = $rva["attributi_valori"]["colore"];
				else
					$arrayCombValori[$rva["combinazioni"][$c]] = $name.": ".avfield($rva, "titolo");
			}
			
			$lista_valori_attributi[$c] = $temp + $arrayCombValori;
		}
		
		return array($colonne, $lista_valori_attributi);
	}
	
	public function selectPersonalizzazioni($id_page)
	{
		$pers = new PersonalizzazioniModel();
		
		return $pers->clear()->inner(array("pages"))->addJoinTraduzione()->where(array(
			"pages_personalizzazioni.id_page"	=>	(int)$id_page,
		))->send();
	}
	
	public function giacenzaPrincipale($id_page)
	{
		if (v("attiva_gift_card") && ProdottiModel::isGiftCart((int)$id_page))
			return 99999;
		
		$c = new CombinazioniModel();
		$principale = $c->combinazionePrincipale((int)$id_page);
		
		if (!empty($principale))
			return $principale["giacenza"] <= v("giacenza_massima_mostrata") ? $principale["giacenza"] : v("giacenza_massima_mostrata") ;
		
		return 0;
	}
	
	public function marchio($record)
	{
		return $record["marchi"]["titolo"];
	}
	
	public function tag($record)
	{
		$pt = new PagestagModel();
		
		$tags = $pt->clear()->select("tag.titolo")->inner(array("tag"))->where(array(
			"id_page"	=>	(int)$record["pages"]["id_page"],
		))->toList("tag.titolo")->send();
		
		return implode("<br />", $tags);
	}
	
	public function aggiungiAccesori($idPage)
	{
		$ids = $this->clear()->where(array(
			"attivo"	=>	"Y",
			"aggiungi_sempre_come_accessorio"	=>	"Y",
		))->toList("id_page")->send();
		
		$c = new CorrelatiModel();
		
		foreach ($ids as $id)
		{
			$numero = $c->clear()->where(array(
				"id_page"	=>	(int)$idPage,
				"id_corr"	=>	(int)$id,
				"accessorio"=>	1,
			))->rowNumber();
			
			if (!$numero)
			{
				$c->setValues(array(
					"id_page"		=>	$idPage,
					"id_corr"		=>	$id,
					"accessorio"	=>	1,
				));
				
				$c->insert();
			}
		}
	}
	
	public function acquistabile($idPage)
	{
		return $this->clear()->where(array(
			"id_page"		=>	(int)$idPage,
			"acquistabile"	=>	"Y",
			"acquisto_diretto"	=>	"Y",
		))->rowNumber();
	}
	
	public static function gTipoPagina($tipo)
	{
		if (isset(self::$tipiPaginaId[$tipo]))
			return self::$tipiPaginaId[$tipo];
		
		$p = new PagesModel();
		
		return $p->clear()->where(array(
			"attivo"	=>	"Y",
			"tipo_pagina"		=>	sanitizeAll($tipo),
			"principale"	=>	"Y",
		))->field("id_page");
	}
	
	// Controlla che esista una pagina di $id e $tipo
	public function checkTipoPagina($id, $tipo)
	{
		return $this->clear()->where(array(
			"attivo"		=>	"Y",
			"tipo_pagina"	=>	sanitizeAll($tipo),
			"principale"	=>	"Y",
			"id_page"		=>	(int)$id,
		))->rowNumber();
	}
	
	public static function disponibilita($idPage = 0)
	{
		$c = new CombinazioniModel();
		
		if (VariabiliModel::combinazioniLinkVeri())
		{
			$c->clear()->select("giacenza as GIACENZA")->where(array(
				"id_page"	=>	(int)$idPage
			))->orderBy("canonical desc,id_order")->limit(1);
			
			if (self::$IdCombinazione)
				$c->aWhere(array(
					"id_c"	=>	(int)self::$IdCombinazione,
				));
			
			$res = $c->send();
			
			if (count($res) > 0)
				return (int)$res[0]["combinazioni"]["GIACENZA"];
		}
		else
		{
			$res = $c->clear()->select("max(giacenza) as GIACENZA")->where(array(
				"id_page"	=>	(int)$idPage
			))->send();
			
			if (count($res) > 0)
				return (int)$res[0]["aggregate"]["GIACENZA"];
		}
		
		return 0;
	}
	
	// Restituisce il codice 
	public function getFirstNotEmpty($idPage = 0, $field = "title", $parents = null, $funzione = null, $onlyParents = false)
	{
		if (!isset($parents))
		{
			$parents = $this->parents((int)$idPage, false, $onlyParents, false, "id_c,$field");
			
			//elimino la categoria root
			array_shift($parents);
			
			$parents = array_reverse($parents);
			
	// 		print_r($parents);
		}
		
		foreach ($parents as $p)
		{
			$pr = isset($p["categories"]) ? $p["categories"] : $p["pages"];
			
			if ($funzione)
			{
				if (call_user_func($funzione,$pr[$field]))
					return $pr[$field];
			}
			else
			{
				if ($pr[$field])
					return $pr[$field];
			}
		}
		
		return "";
	}
	
	public static function gXmlProdottiGoogle($p = null)
	{
		$c = new CategoriesModel();
		$cart = new CartModel();
		
		if (!isset($p))
		{
			$p = new PagesModel();
			$p->clear();
		}
		
		$m = new MarchiModel();
		$o = new OpzioniModel();
		
		$etichettePersonalizzate = $o->clear()->where(array(
			"codice"	=>	"ETICHETTE_FEED_GOOGLE",
			"attivo"	=>	1,
		))->orderBy("id_order")->toList("valore")->send();
		
		$idShop = $c->getShopCategoryId();
		
		$children = $c->children($idShop, true);
		
		$catWhere = "in(".implode(",",$children).")";
		
		if (isset($_GET["id_page"]))
			$p->aWhere(array(
				"id_page"	=>	(int)$_GET["id_page"],
			));
		
		$res = $p->select("distinct pages.codice_alfa,pages.*,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*")->aWhere(array(
				"in" => array("-id_c" => $children),
			))
			->addWhereAttivo()
			->addJoinTraduzionePagina()
			->orderBy("pages.title")->send();
		
		$res = PagesModel::impostaDatiCombinazionePagine($res);
		
		$arrayProdotti = array();
		
		foreach ($res as $r)
		{
			PagesModel::$IdCombinazione = $p->getIdCombinazioneCanonical($r["pages"]["id_page"]);
			
			$giacenza = self::disponibilita($r["pages"]["id_page"]);
			$outOfStock = v("attiva_giacenza") ? "out of stock" : "in stock";
			
			$prezzoMinimo = $p->prezzoMinimo($r["pages"]["id_page"]);
			$prezzoMinimoIvato = $prezzoFeed = calcolaPrezzoIvato($r["pages"]["id_page"],$prezzoMinimo);
			
			$prodottoInPromo = $p->inPromozione($r["pages"]["id_page"], $r);
			
			$prezzoFinale = $cart->calcolaPrezzoFinale($r["pages"]["id_page"], $prezzoMinimo, 1, true, true, PagesModel::$IdCombinazione);
			$prezzoFinaleIvato = calcolaPrezzoIvato($r["pages"]["id_page"],$prezzoFinale);
			
			if (!$prodottoInPromo && number_format($prezzoMinimoIvato,2,".","") != number_format($prezzoFinaleIvato,2,".",""))
				$prezzoFeed = $prezzoFinaleIvato;

			$temp = array(
				"g:id"	=>	v("usa_sku_come_id_item") ? $r["pages"]["codice"] : $r["pages"]["id_page"],
				"g:title"	=>	htmlentitydecode(field($r,"title")),
				"g:link"	=>	Url::getRoot().getUrlAlias($r["pages"]["id_page"]),
				"g:price"	=>	number_format($prezzoFeed,2,".",""). " EUR",
				"g:availability"	=>	$giacenza > 0 ? "in stock" : $outOfStock,
// 				"g:identifier_exists"	=>	v("identificatore_feed_default"),
			);
			
			if (!isset($_GET["fbk"]))
			{
				if ($r["pages"]["gtin"])
					$temp["g:gtin"] = htmlentitydecode($r["pages"]["gtin"]);
				
				if ($r["pages"]["mpn"])
					$temp["g:mpn"] = htmlentitydecode($r["pages"]["mpn"]);
			}
			
			$temp["g:identifier_exists"] = $r["pages"]["identifier_exists"] ? $r["pages"]["identifier_exists"] : v("identificatore_feed_default");
			
			if (isset($_GET["fbk"]) || v("no_tag_descrizione_feed"))
				$temp["g:description"] = strip_tags(htmlentitydecode(field($r,"description")));
			else
				$temp["g:description"] = htmlspecialchars(htmlentitydecode(field($r,"description")), ENT_QUOTES, "UTF-8");
			
			if (v("elimina_emoticons_da_feed"))
			{
				$temp["g:title"] = F::removeEmoji($temp["g:title"]);
				$temp["g:description"] = F::removeEmoji($temp["g:description"]);
			}
			
			$parents = $p->parents((int)$r["pages"]["id_page"], false, false, true);
			
			//elimino la categoria root
			array_shift($parents);
			
			$productType = array();
			
			$indice = 0;
			
			foreach ($parents as $pr)
			{
				if ($indice && isset($pr["categories"]))
					$productType[] = htmlentitydecode(cfield($pr,"title"));
				
				$indice++;
			}
			
			$parents = array_reverse($parents);
			
// 			print_r($parents);
			
			if (count($productType) > 0)
				$temp["g:product_type"] = implode(" &gt; ", $productType);
			
			$codiceGoogle = $p->getFirstNotEmpty($r["pages"]["id_page"], "codice_categoria_prodotto_google", $parents);
			
			if ($codiceGoogle)
				$temp["g:google_product_category"] = $codiceGoogle;
			else
				$temp["g:google_product_category"] = htmlentitydecode(cfield($r,"title"));
			
			if ($r["pages"]["immagine"])
				$temp["g:image_link"] = Url::getRoot()."thumb/dettagliobig/".$r["pages"]["immagine"];
			
			$altreImmagini = ImmaginiModel::altreImmaginiPagina((int)$r["pages"]["id_page"]);
			
			if (count($altreImmagini) > 0)
			{
				$temp["g:additional_image_link"] = array();
				
				$count = 0;
				
				$numeroLimite = isset($_GET["fbk"]) ? 20 : 10;
				
				foreach ($altreImmagini as $k => $img)
				{
					if ($count >= $numeroLimite)
						break;
					
					$temp["g:additional_image_link"][] = Url::getRoot()."thumb/dettagliobig/".$img["immagine"];
					
					$count++;
				}
				
				if (isset($_GET["fbk"]))
					$temp["g:additional_image_link"] = implode(",",$temp["g:additional_image_link"]);
			}
			
			if ($r["pages"]["id_marchio"])
			{
				$marchio = $m->clear()->addJoinTraduzione()->where(array(
					"id_marchio"	=>	(int)$r["pages"]["id_marchio"],
				))->first();
				
				if (!empty($marchio))
					$temp["g:brand"] = htmlentitydecode(mfield($marchio, "titolo"));
			}
			
			if (isset($_GET["fbk"]))
				$temp["condition"] = "new";
			
			if ($prodottoInPromo)
			{
				$temp["g:sale_price"] = number_format($prezzoFinaleIvato,2,".",""). " EUR";
				$temp["g:sale_price_effective_date"] = date("c",strtotime($r["pages"]["dal"]))."/".date("c",strtotime($r["pages"]["al"]." 23:59:00"));
				
				$r["pages"]["in_promo_feed"] = true;
			}
			
			if (!isset($_GET["fbk"]))
			{
				if (v("aggiungi_dettagli_prodotto_al_feed"))
				{
					$caratteristiche = $p->selectCaratteristiche($r["pages"]["id_page"]);
					
					if (count($caratteristiche) > 0)
					{
						$temp["g:product_detail"] = array();
						
						foreach ($caratteristiche as $rc)
						{
							$temp["g:product_detail"][] = array(
								"g:section_name"	=>	tcarfield($rc, "titolo") ? htmlentitydecode(tcarfield($rc, "titolo")) : gtext("Generale"),
								"g:attribute_name"	=>	htmlentitydecode(carfield($rc, "titolo")),
								"g:attribute_value"	=>	htmlentitydecode(carvfield($rc, "titolo")),
							);
						}
					}
				}
				
				if (v("aggiungi_dettagli_spedizione_al_feed") && v("attiva_spedizione"))
				{
					$subtotaleSpedizione = (!v("prezzi_ivati_in_carrello")) ? $prezzoFinale : $prezzoFinaleIvato;
					
					// Solo spedizioni gratuite e solo nazione default
					if (ImpostazioniModel::$valori["spedizioni_gratuite_sopra_euro"] > 0 && $subtotaleSpedizione >= ImpostazioniModel::$valori["spedizioni_gratuite_sopra_euro"])
					{
						$nazione = User::$nazioneNavigazione ? User::$nazioneNavigazione : v("nazione_default");
						
						$temp["g:shipping"]["g:country"] = $nazione;
						$temp["g:shipping"]["g:price"] = "0 EUR";
					}
				}
			}
			
			if (!isset($_GET["fbk"]) && count($etichettePersonalizzate) > 0)
			{
				$indiceEtichetta = 0;
				
				foreach ($etichettePersonalizzate as $etP)
				{
					if (method_exists($p, $etP))
					{
						$temp["g:custom_label_".$indiceEtichetta] = call_user_func_array(array($p, $etP), array($r["pages"]["id_page"], $r["pages"]));
						
						$indiceEtichetta++;
					}
				}
			}
			
			$arrayProdotti[] = $temp;
		}
		
		return $arrayProdotti;
	}
	
	public function tipopagina($record)
	{
		if (isset(self::$tipiPagina[$record["pages"]["tipo_pagina"]]))
			return self::$tipiPagina[$record["pages"]["tipo_pagina"]];
		
		return "";
	}
	
	public function addJoinTraduzionePagina($lingua = null)
	{
		if (!isset($lingua))
			$lingua = Params::$lang;
		
		$this->inner("categories")->on("categories.id_c = pages.id_c")
			->left("contenuti_tradotti")->on(array("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = ?", array(sanitizeDb($lingua))))
			->left("contenuti_tradotti as contenuti_tradotti_categoria")->on(array("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = ?", array(sanitizeDb($lingua))));
		
		if (!$this->select)
			$this->select("distinct pages.codice_alfa,pages.*,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*");
		
		return $this;
	}
	
	public static function isAttivaTrue($idPage)
	{
		return PagesModel::g(false)->where(array(
			"id_page"	=>	(int)$idPage,
		))->addWhereAttivo()->rowNumber();
	}
	
	public static function isAttiva($idPage)
	{
		$record = self::getPageDetails($idPage);
		
		return !empty($record) ? true : false;
	}
	
	public static function getPageDetails($idPage, $lingua = null)
	{
		$p = new PagesModel();
		
		return $p->clear()->addJoinTraduzionePagina($lingua)->where(array(
			"id_page"	=>	(int)$idPage,
		))->first();
	}
	
	public static function listaImmaginiPagina()
	{
		if (isset(self::$arrayImmagini))
			return self::$arrayImmagini;
		
		$p = new PagesModel();
		$i = new ImmaginiModel();
		
		// Immagine principale
		$strImm = $p->clear()->select("distinct codice_alfa,pages.id_page,pages.immagine")->where(array("id_c"=>0))->toList("id_page", "immagine")->send();
		
		$struttImmagini = array();
		
		foreach ($strImm as $idPage => $immagine)
		{
			if (trim($immagine))
				$struttImmagini[$idPage] = array($immagine);
		}
		
		// Altre immagini
		$elencoImmagini = $i->clear()->select("id_page,immagine")->orderBy("id_order")->send(false);
		
		foreach ($elencoImmagini as $row)
		{
			if (isset($struttImmagini[$row["id_page"]]))
				$struttImmagini[$row["id_page"]][] = $row["immagine"];
			else
				$struttImmagini[$row["id_page"]] = array($row["immagine"]);
		}
		
		self::$arrayImmagini = $struttImmagini;
		
		return self::$arrayImmagini;
	}
	
    public function aggiungiaprodotto($id)
    {
		$record = $this->selectId((int)$id);
		
		if (isset($_GET["id_pcorr"]))
			$recordPagina = $this->selectId((int)$_GET["id_pcorr"]);
		
		if (!empty($record) && isset($_GET["id_pcorr"]) && !empty($recordPagina) && isset($_GET["pcorr_sec"]))
		{
			$pp = new PagespagesModel();
			
			$pp->setValues(array(
				"id_page"	=>	(int)$_GET["id_pcorr"],
				"id_corr"	=>	(int)$id,
				"section"	=>	$_GET["pcorr_sec"],
			), "sanitizeAll");
			
			$pp->insert();
		}
    }
    
    public static function punteggio($id)
    {
		$f = new FeedbackModel();
		
		return (float)$f->clear()->where(array(
			"id_page"	=>	(int)$id,
// 			"is_admin"	=>	0,
			"attivo"	=>	1,
		))->getAvg("voto");
    }
    
    public static function numeroFeedback($id)
    {
		$f = new FeedbackModel();
		
		return $f->clear()->where(array(
			"id_page"	=>	(int)$id,
// 			"is_admin"	=>	0,
			"attivo"	=>	1,
		))->rowNumber();
    }
    
    public static function hasFeedback($id)
    {
		if (!isset(self::$pagineConFeedback))
		{
			$f = new FeedbackModel();
			
			self::$pagineConFeedback = $f->clear()->select("distinct id_page")->toList("id_page")->send();
		}
		
		if (in_array($id, self::$pagineConFeedback))
			return true;
		
		return false;
    }
    
    public static function getRichSnippet($id)
    {
		$pm = new PagesModel();
		
		$pm->clear()->addJoinTraduzionePagina()->where(array(
			"pages.id_page"	=>	(int)$id,
		));
		
		$pages = PagesModel::impostaDatiCombinazionePagine($pm->send());
		
		$i = new ImmaginiModel();
		
		$snippetArray = array();
		
		if (count($pages) > 0)
		{
			$p = $pages[0];
			
// 			PagesModel::$IdCombinazione = $pm->getIdCombinazioneCanonical($p["pages"]["id_page"]);
			
			$giacenza = self::disponibilita($p["pages"]["id_page"]);
			$outOfStock = v("attiva_giacenza") ? "https://schema.org/OutOfStock" : "https://schema.org/InStock";
			
			$prezzoMinimo = $pm->prezzoMinimo($p["pages"]["id_page"]);
			$c = new CartModel();
			$prezzoMinimo = $c->calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoMinimo, 1, true, true, PagesModel::$IdCombinazione);
			
			$prezzoMinimoIvato = calcolaPrezzoIvato($p["pages"]["id_page"],$prezzoMinimo);
			
			$images = array();
			
			if ($p["pages"]["immagine"])
				$images[] = Url::getFileRoot()."thumb/dettagliobig/".$p["pages"]["immagine"];
			
			$altreImmagini = ImmaginiModel::altreImmaginiPagina((int)$id);
			
// 			$altreImmagini = $i->clear()->where(array(
// 				"id_page"	=>	(int)$id,
// 				"id_c"		=>	0,
// 			))->orderBy("id_order")->send(false);
			
			foreach ($altreImmagini as $imm)
			{
				$images[] = Url::getFileRoot()."thumb/dettagliobig/".$imm["immagine"];
			}
			
			if ($pm->inPromozione($p["pages"]["id_page"]))
			{
				$now = DateTime::createFromFormat('Y-m-d', $p["pages"]["al"]);
			}
			else
			{
				$now = new dateTime();
				$now->modify("+10 days");
			}
			
			$snippetArray = array(
				"@context"	=>	"https://schema.org/",
				"@type"		=>	"Product",
				"name"		=>	sanitizeJs(F::meta(field($p, "title"))),
				"offers"	=>	array(
					"@type"	=>	"Offer",
					"price"	=>	number_format($prezzoMinimoIvato,2,".",""),
					"priceCurrency"	=>	"EUR",
					"availability"	=>	$giacenza > 0 ? "https://schema.org/InStock" : $outOfStock,
					"url"	=>	Url::getRoot().$pm->getUrlAlias($p["pages"]["id_page"]),
					"priceValidUntil"	=>	$now->format("Y-m-d"),
				),
			);
			
			if (!empty($images))
				$snippetArray["image"] = $images;
			
			$snippetArray["description"] = sanitizeJs(strip_tags(htmlentitydecode(field($p, "description"))));
			
			if ($p["pages"]["codice"])
				$snippetArray["sku"] = $p["pages"]["codice"];
			
			if (v("usa_marchi") && $p["pages"]["id_marchio"])
			{
				$m = new MarchiModel();
				
				$marchio = $m->clear()->addJoinTraduzione()->where(array(
					"id_marchio"	=>	(int)$p["pages"]["id_marchio"],
				))->first();
				
				if (!empty($marchio))
				{
					$snippetArray["brand"] = array(
						"@type"	=>	"Brand",
						"name"	=>	 sanitizeJs(mfield($marchio, "titolo")),
					);
				}
			}
			else if (v("marchio_rich_snippet"))
			{
				$snippetArray["brand"] = array(
					"@type"	=>	"Brand",
					"name"	=>	 sanitizeJs(v("marchio_rich_snippet")),
				);
			}
			
			if (v("abilita_feedback") && self::hasFeedback((int)$id))
			{
				$fm = new FeedbackModel();
				
				$feedback = $fm->clear()->where(array(
					"id_page"	=>	(int)$id,
// 					"is_admin"	=>	0,
					"attivo"	=>	1,
				))->orderBy("feedback.voto desc")->send(false);
				
				if (count($feedback) > 0)
				{
					$snippetArray["review"] = array();
					
					foreach ($feedback as $fd)
					{
						$snippetArray["review"][] = array(
							"@type"	=>	"Review",
							"reviewRating"	=>	array(
								"@type"	=>	"Rating",
								"ratingValue"	=>	$fd["voto"],
							),
							"author"	=>	array(
								"@type"	=>	"Person",
								"name"	=>	sanitizeJs($fd["autore"]),
							),
							"reviewBody"	=>	sanitizeJs(strip_tags(htmlentitydecode($fd["testo"]))),
						);
					}
				}
				
				$snippetArray["aggregateRating"] = array(
					"@type"	=>	"AggregateRating",
					"ratingValue"	=>	(string)number_format(self::punteggio((int)$id),1,".",""),
					"reviewCount"	=>	(string)self::numeroFeedback((int)$id),
					"bestRating"	=>	(string)5,
					"worstRating"	=>	(string)1,
				);
			}
		}
		
		return $snippetArray;
    }
    
    public static function getTagCanonical($id)
    {
		$pm = new PagesModel();
		
		$idP = $pm->clear()->where(array(
			"pages.id_page"	=>	(int)$id,
		))->field("id_p");
		
		if ($idP)
			return '<link rel="canonical" href="'.Url::getRoot().getUrlAlias((int)$idP).'" />';
		
// 		if (v("aggiorna_pagina_al_cambio_combinazione_in_prodotto"))
			return '<link rel="canonical" href="'.Url::getRoot().getUrlAlias((int)$id).'" />';
		
// 		return "";
    }
    
    public function nazioneCrud($record)
	{
		$str = "";
		
		$pr = new PagesregioniModel();
			
		$nazioni = $pr->clear()->where(array(
			"id_page"	=>	(int)$record[$this->_tables]["id_page"],
		))->toList("alias_nazione")->send();
		
		$nazioni = array_unique($nazioni);
		
		if (count($nazioni) > 0)
			$str .= strtoupper(implode("<br />", $nazioni));
		else if (v("prodotto_tutte_regioni_se_nessuna_regione"))
			$str .= gtext("TUTTE");
		
		return "<span class='text text-success text-bold'>".$str."</span>";
	}
	
	public function regioneCrud($record)
	{
		$pr = new PagesregioniModel();
			
		$regioni = $pr->clear()->select("regioni.titolo")->inner(array("regione"))->where(array(
			"id_page"	=>	(int)$record[$this->_tables]["id_page"],
		))->toList("regioni.titolo")->send();
		
		$regioni = array_unique($regioni);
		
		if (count($regioni))
			return "<span class='text text-success text-bold'>".strtoupper(implode("<br />", $regioni))."</span>";
		
		return "";
	}
    
    public function lingua($record)
	{
		LingueModel::getValori();
		
		$str = "";
		
		$dl = new PageslingueModel();
			
		$altreLingue = $dl->clear()->where(array(
			"id_page"	=>	(int)$record[$this->_tables]["id_page"],
			"includi"	=>	1,
		))->toList("lingua")->send();
		
		if (count($altreLingue) > 0)
			$str .= strtoupper(implode(" + ", $altreLingue));
		else
			$str .= gtext("TUTTE");
		
		return "<span class='text text-success text-bold'>".$str."</span>";
	}
	
	public function escludilingua($record)
	{
		$dl = new PageslingueModel();
		
		$altreLingue = $dl->clear()->where(array(
			"id_page"	=>	(int)$record[$this->_tables]["id_page"],
			"includi"	=>	0,
		))->toList("lingua")->send();
		
		if (count($altreLingue) > 0)
			return "<span class='text text-danger text-bold'>".strtoupper(implode(" + ", $altreLingue))."</span>";
		
		return "";
	}
	
	public function getLocalizzazione($idPage)
    {
		$pr = new PagesregioniModel();
		
		$regioni = $pr->clear()->select("distinct pages_regioni.id_regione,regioni.*")->inner(array("regione"))->where(array(
			"id_page"	=>	(int)$idPage,
		))->send();
		
		$nazioni = $pr->clear()->select("distinct pages_regioni.id_nazione,nazioni.*")->inner(array("nazione"))->where(array(
			"id_page"	=>	(int)$idPage,
		))->send();
		
		return array($nazioni, $regioni);
    }
    
    public function addWhereLingua()
    {
		$this->left("pages_lingue as lingue_includi")->on("pages.id_page = lingue_includi.id_page and lingue_includi.includi = 1");
		$this->left("pages_lingue as lingue_escludi")->on("pages.id_page = lingue_escludi.id_page and lingue_escludi.includi = 0");
		
		$this->sWhere(array(
			"(lingue_includi.id_page is null or pages.id_page in (select id_page from pages_lingue where lingua = ? and includi = 1))",
			array(
				sanitizeDb(Params::$lang)
			),
		));
		
		$this->sWhere(array(
			"(lingue_escludi.id_page is null or pages.id_page not in (select id_page from pages_lingue where lingua = ? and includi = 0))",
			array(
				sanitizeDb(Params::$lang)
			),
		));
		
		return $this;
    }
    
    public static function setCampiAggiuntivi($sezione, $arrayCampi, $traduci = false)
    {
		if (isset(self::$campiAggiuntivi[$sezione]))
			self::$campiAggiuntivi[$sezione] += $arrayCampi;
		else
			self::$campiAggiuntivi[$sezione] = $arrayCampi;
		
		if ($traduci)
		{
			foreach ($arrayCampi as $campo => $frm)
			{
				if (!in_array($campo, self::$campiAggiuntiviMeta["traduzione"]))
					self::$campiAggiuntiviMeta["traduzione"][] = $campo;
			}
		}
    }
    
    public static function gImmagine($id_page, $indice = 1)
	{
		$clean['id_page'] = (int)$id_page;

		$p = new PagesModel();
		$res = $p->clear()->select("*")->left("immagini")->on("immagini.id_page = pages.id_page")->where(array(
			"pages.id_page"	=>	$clean['id_page'],
		))->orderBy("immagini.id_order")->limit(2)->send();
		
		$arrayImmagini = array();
		$i = 0;
		foreach ($res as $r)
		{
			if (!$i)
			{
				if ($r["pages"]["immagine"])
					$arrayImmagini[] = $r["pages"]["immagine"];
			}
			
			if ($r["immagini"]["immagine"])
				$arrayImmagini[] = $r["immagini"]["immagine"];
			
			$i++;
		}
		
		$i = 1;
		foreach ($arrayImmagini as $imm)
		{
			if ($i == $indice)
				return $imm;
			
			$i++;
		}
		
		return "";
	}
	
	public function getArrayLingueAttiveFrontend($idPage)
	{
		$p = new PageslingueModel();
		
		$lingue = $p->clear()->where(array(
			"id_page"	=>	(int)$idPage,
		))->send(false);
		
		if ((int)count($lingue) === 0)
			return Params::$frontEndLanguages;
		
		$lingueIncludi = $lingueEscludi = array();
		
		foreach ($lingue as $l)
		{
			if ($l["includi"])
				$lingueIncludi[] = $l["lingua"];
			else
				$lingueEscludi[] = $l["lingua"];
		}
		
		$arrayLingueFrontend = array();
		
		foreach (Params::$frontEndLanguages as $lang)
		{
			if ((count($lingueIncludi) === 0 || in_array($lang, $lingueIncludi)) && !in_array($lang, $lingueEscludi))
				$arrayLingueFrontend[] = $lang;
		}
		
		return $arrayLingueFrontend;
	}
	
	public static function loadTemplateFile($file)
	{
		if (file_exists(tpf($file)))
		{
			ob_start();
			include tpf($file);
			$output = ob_get_clean();
			
			return $output;
		}
		
		return "";
	}
	
	public static function loadTemplateSceltaCookie()
	{
		return self::loadTemplateFile("Elementi/Cookie/scelta_tipo.php");
	}
	
	public function etichettaInEvidenza($idPage, $page = null)
	{
		if (!$page)
			$page = $this->selectId($page);
		
		return $page["in_evidenza"] == "Y" ? "IN EVIDENZA" : "NON IN EVIDENZA";
	}
	
	public function etichettaInPromozione($idPage, $page = null)
	{
		if ($page && isset($page["pages"]["in_promo_feed"]) && $page["pages"]["in_promo_feed"])
			$inPromo = true;
		else
			$inPromo = $this->inPromozione($idPage);
		
		return $inPromo == "Y" ? "IN PROMOZIONE" : "NON IN PROMOZIONE";
	}
	
	public function etichettaFasciaDiPrezzo($idPage, $page = null)
	{
		return "";
	}
	
	public function etichettaTitolo($idPage, $page = null, $numeroParole = 0)
	{
		if (!$page)
			$page = $this->selectId($page);
		
// 		print_r(explode(" ",htmlentitydecode($page["title"]), $numeroParole));
		
		if ($numeroParole)
			return implode(" ",array_slice(explode(" ",htmlentitydecode($page["title"])),0,$numeroParole));
		else
			return htmlentitydecode($page["title"]);
	}
	
	public function etichettaInizialiProdotto($idPage, $page = null)
	{
		return $this->etichettaTitolo($idPage, $page, (int)v("numero_parole_feed_iniziali_prodotto"));
	}
	
	public function getMarginePercentuale($idPage)
	{
		return (float)$this->getFirstNotEmpty($idPage, "margine", null, "maggioreDiZero");
	}
	
	public function margineEuro($idPage)
	{
		if (!PagesModel::$IdCombinazione)
			PagesModel::$IdCombinazione = $this->getIdCombinazioneCanonical($idPage);
		
		$prezzoMinimoPieno = $this->prezzoMinimo($idPage, true);
		
		$c = new CartModel();
		$prezzoMinimo = $c->calcolaPrezzoFinale($idPage, $prezzoMinimoPieno, 1, true, true, PagesModel::$IdCombinazione);
		
		// Calcolo lo sconto in euro
		$scontoEuro = $prezzoMinimoPieno - $prezzoMinimo;
		
		$margine = $this->getMarginePercentuale($idPage);
		
		$margineEuro = ($prezzoMinimoPieno * $margine) / 100;
		
		if (v("considera_promo_in_margine_euro"))
			return $margineEuro - $scontoEuro;
		else
			return $margineEuro;
	}
	
	public function etichettaMargineEuro($idPage, $page = null)
	{
		return number_format($this->margineEuro($idPage),0,".","");
	}
	
	public function etichettaMargine($idPage, $page = null)
	{
		$scaglione = (int)v("scaglioni_margine_di_euro");
		
		if ($scaglione > 0)
		{
			$margineEuro = $this->margineEuro($idPage);
			
			list($min, $max) = F::getLimitiMinMax($margineEuro, $scaglione);
			
			return "MARG $min - $max";
		}
		
		return "";
	}
	
	public function etichettaMargineGuadagnoPrevisto($idPage, $page = null)
	{
		$scaglione = (int)v("scaglioni_margine_di_guadagno");
		
		if (!$page)
			$page = $this->selectId($page);
		
		if ($scaglione > 0 && isset($page["guadagno_previsto"]))
		{
			list($min, $max) = F::getLimitiMinMax($page["guadagno_previsto"], $scaglione);
			
			return "MARG GUAD $min - $max";
		}
		
		return "";
	}
	
	public function etichettaCpc($idPage, $page = null)
	{
		return $this->etichettaCpcField($idPage, $page, "cpc_medio_pesato", "CPC");
	}
	
	public function etichettaCpcMax($idPage, $page = null)
	{
		return $this->etichettaCpcField($idPage, $page, "cpc_max", "CPC MAX");
	}
	
	public function etichettaCpcMedioEMax($idPage, $page = null)
	{
		if (!$page)
			$page = $this->selectId($page);
		
		if (isset($page["guadagno_previsto"]) && $page["guadagno_previsto"] > 0)
			return $this->etichettaCpcField($idPage, $page, "cpc_medio_pesato", "CPC MEDIO");
		else
			return $this->etichettaCpcField($idPage, $page, "cpc_max", "CPC MAX");
	}
	
	public function etichettaCpcField($idPage, $page = null, $field = "cpc_medio_pesato", $etichetta = "CPC")
	{
		$scaglione = (int)v("scaglioni_cpc_euro_centesimi") / 100;
		
		if (!$page)
			$page = $this->selectId($page);
		
		if ($scaglione > 0 && isset($page[$field]))
		{
			list($min, $max) = F::getLimitiMinMax($page[$field], $scaglione);
			
			return "$etichetta $min - $max";
		}
		
		return "";
	}
	
	public function etichettaAttivoPassivo($idPage, $page = null)
	{
		if (!$page)
			$page = $this->selectId($page);
		
		if (isset($page["guadagno_previsto"]))
			return $page["guadagno_previsto"] > 0 ? "GUADAGNO STIMATO POSITIVO" : "GUADAGNO STIMATO NEGATIVO";
		
		return "NON STIMATO";
	}
	
	public function etichettaDisponibilita($idPage, $page = null)
	{
		$giacenza = self::disponibilita($idPage);
		
		return $giacenza > 0 ? "DISPONIBILE" : "NON DISPONIBILE";
	}
	
	// Restituisce gli elementi da usare nella fascia
	public static function getElementiFascia($numero = 0, $orderBy = "pages.id_order desc", $where = "")
	{
		$className = get_called_class();
		
		$c = new $className;
		
		$c->clear()->select("*")
			->addJoinTraduzionePagina()
			->addWhereAttivo()
			->addWhereCategoria((int)CategoriesModel::getIdCategoriaDaSezione($c->hModel->section))
			->orderBy($orderBy);
		
		if ($where)
			$c->sWhere($where);
		
		if ($numero)
			$c->limit($numero);
		
		return $c->send();
	}
	
	public static function getRedirectQuery($char = "?")
	{
		if (self::$currentIdPage)
		{
			$page = self::getPageDetails((int)self::$currentIdPage);
			
			if (!empty($page))
				return $char."redirect=".self::$currentIdPage;
		}
		
		return "";
	}
	
	public function aggiornaTabellaLocalita($id)
	{
		$record = $this->selectId($id);
		
		if (!empty($record))
		{
			$pr = new PagesregioniModel();
			
			$pr->query("delete from pages_regioni where id_page = ".(int)$id);
			
			$idNazione = 0;
			
			if ($record["id_regione"])
			{
				$r = new RegioniModel();
				
				$idNazione = $r->clear()->select("id_nazione")->where(array(
					"id_regione"	=>	sanitizeAll($record["id_regione"])
				))->field("id_nazione");
			}
			else if ($record["codice_nazione"])
			{
				$n = new NazioniModel();
				
				$idNazione = $n->clear()->select("id_nazione")->where(array(
					"iso_country_code"	=>	sanitizeAll($record["codice_nazione"])
				))->field("id_nazione");
			}
			
			if ($idNazione || $record["id_regione"])
			{
				$pr->setValues(array(
					"id_page"		=>	$id,
					"id_nazione"	=>	$idNazione,
					"id_regione"	=>	$record["id_regione"],
				));
				
				$pr->pInsert();
			}
		}
	}
	
	public function setAliasAndCategory()
	{
		if (!isset($this->values["alias"]) || !$this->values["alias"])
			$this->values["alias"] = "";
		
		$c = new CategoriesModel();
		
		$this->values["id_c"] = (int)$c->clear()->where(array("section"=>$this->hModel->section))->field("id_c");
	}
	
	// Duplica la pagina
	public function duplicaPagina($id, $modelAssociati = array())
	{
		$clean['id'] = (int)$id;
		
		$res = $this->clear()->where(array("id_page"=>$clean['id']))->send();
		
		if (count($res) > 0)
		{
			$section = $this->hModel->section;
			
			$this->values = $res[0]["pages"];
			
			$this->values["title"] = "(Copia di) " . $this->values["title"];
			
			$this->checkDates();
			
			$this->values["principale"] = "Y";
			
			$this->delFields("id_page");
			$this->delFields("data_creazione");
			$this->delFields("id_order");
			
			$this->values["codice_alfa"] = md5(randString(22).microtime().uniqid(mt_rand(),true));
			
			$this->sanitize();

			Params::$setValuesConditionsFromDbTableStruct = false;
			
			$this->clearConditions("values");
			$this->clearConditions("strong");
			$this->clearConditions("soft");
			
			$this->insert();
			
			if ($this->queryResult)
			{
				$lId = $this->lId;
				
				foreach (PagesModel::$modelliDaDuplicare as $daDuplicare)
				{
					if ($daDuplicare != "PagesregioniModel" || $section != "sedi" || $section != "soci")
					{
						$modelDaDuplicare = new $daDuplicare();
						$modelDaDuplicare->duplica($clean['id'], $lId);
					}
				}
				
				// Duplico i model associati
				foreach ($modelAssociati as $modelAssociato => $modelParams)
				{
					if (isset($modelParams["duplica"]) && $modelParams["duplica"])
					{
						$modelDaDuplicare = new $modelAssociato();
						$modelDaDuplicare->duplica($clean['id'], $lId);
					}
				}
				
				return $lId;
			}
			
			return 0;
		}
	}
	
	// Restituisce le pagine correlate ad una pagina
	public static function getElementiCorrelatiA($idPage, $section, $numero = 0, $orderBy = "pages.id_order desc")
	{
		$className = get_called_class();
		
		$c = new $className;
		
		$c->clear()->select("*")
			->addJoinTraduzionePagina()
			->addWhereAttivo()
			->inner("pages_pages")->on("pages.id_page = pages_pages.id_corr")
			->aWhere(array(
				"pages_pages.section"	=>	sanitizeAll($section),
				"pages_pages.id_page"	=>	(int)$idPage,
			))
			->orderBy($orderBy);
		
		if ($numero)
			$c->limit($numero);
		
		return $c->send();
	}
	
	public static function setPagesStruct($pages)
	{
		$indice = 0;
		
		foreach ($pages as $page)
		{
			if ($indice > 0)
				self::$pagesStruct[$page["pages"]["id_page"]]["prev"] = $pages[($indice - 1)];
			
			if ($indice < (count($pages) - 1))
				self::$pagesStruct[$page["pages"]["id_page"]]["next"] = $pages[($indice + 1)];
			
			$indice++;
		}
	}
	
	public static function getNext($idPage)
	{
		if (isset(self::$pagesStruct[$idPage]) && isset(self::$pagesStruct[$idPage]["next"]))
			return self::$pagesStruct[$idPage]["next"];
		
		return null;
	}
	
	public static function getPrev($idPage)
	{
		if (isset(self::$pagesStruct[$idPage]) && isset(self::$pagesStruct[$idPage]["prev"]))
			return self::$pagesStruct[$idPage]["prev"];
		
		return null;
	}
	
	public function addWhereCombinazione($idC)
	{
		$this->left("combinazioni")->on("combinazioni.id_page = pages.id_page")->aWhere(array(
			"combinazioni.id_c"	=>	(int)$idC,
		));
		
		return $this;
	}
	
	public static function impostaDatiCombinazionePagine($pages)
	{
		$pModel = new PagesModel();
		$cModel = new CombinazioniModel();
		
		$pagesFinale = array();
		
		foreach ($pages as $p)
		{
			$temp = $p;
			
			$idC = PagesModel::$IdCombinazione ? PagesModel::$IdCombinazione : $pModel->getIdCombinazioneCanonical((int)$p["pages"]["id_page"]);
			
			$combinazione = $cModel->selectId((int)$idC);
			
			if (!empty($combinazione))
			{
				$temp["pages"]["codice"] = $combinazione["codice"];
				$temp["pages"]["peso"] = $combinazione["peso"];
				
				if (v("immagini_separate_per_variante"))
				{
					$immaginiCombinazione = ImmaginiModel::immaginiCombinazione($idC);
				
					if (count($immaginiCombinazione) > 0)
						$temp["pages"]["immagine"] = $immaginiCombinazione[0]["immagine"];
				}
			}
			
			$pagesFinale[] = $temp;
		}
		
		return $pagesFinale;
	}
	
	public function addWhereEvidenza()
	{
		$this->aWhere(array(
			"in_evidenza"	=>	"Y",
		));
		
		return $this;
	}
	
	public function addWhereNuovo()
	{
		$this->aWhere(array(
			"nuovo"	=>	"Y",
		));
		
		return $this;
	}
	
	public function addWherePromo()
	{
		$nowDate = date("Y-m-d");
		
		$this->aWhere(array(
			"    lte"	=>	array("pages.dal"	=>	$nowDate),
			"     gte"	=>	array("pages.al" 	=>	$nowDate),
			"pages.in_promozione" => "Y",
		));
		
		return $this;
	}
	
	public static function numeroStato($stato = "evidenza", $filtriSuccessivi = false)
	{
		$p = new PagesModel();
		
		$p->clear()->addWhereAttivo()->addWhereCategoria((int)CategoriesModel::getIdCategoriaDaSezione("prodotti"));
		
		if ($stato == "evidenza")
			$p->addWhereEvidenza();
		else if ($stato == "nuovo")
			$p->addWhereNuovo();
		else if ($stato == "promozione")
			$p->addWherePromo();
		
		if ($filtriSuccessivi)
			$p->sWhereFiltriSuccessivi("[$stato]");
		
		return $p->rowNumber();
	}
	
	public static function clearIdCombinazione()
	{
		PagesModel::$bckIdCombinazione = PagesModel::$IdCombinazione;
		PagesModel::$IdCombinazione = 0;
	}
	
	public static function restoreIdCombinazione()
	{
		PagesModel::$IdCombinazione = PagesModel::$bckIdCombinazione;
		PagesModel::$bckIdCombinazione = 0;
	}
}
