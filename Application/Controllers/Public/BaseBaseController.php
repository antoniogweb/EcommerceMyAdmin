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

require(LIBRARY."/Application/Models/ContattiModel.php");
require_once(LIBRARY."/Frontend/Application/Hooks/BeforeChecksLegacy.php");

class BaseBaseController extends Controller
{
	protected static $adminPanelCaricato = false;
	protected $islogged = false;
	protected $iduser = 0;
	protected $dettagliUtente = null;
	protected $fonteContatto = null;
	protected $idRedirectContatti = null;
	
	protected $estratiDatiGenerali = true;
	
	protected $registrazioneAgente = false;
	
	public $langDb = null;
	public $cleanAlias = null;
	public $prodottiInEvidenza;
	public $elencoCategorieFull;
	public $elencoMarchiFull;
	public $elencoMarchiNuoviFull;
	public $elencoTagFull;
	public $idShop = 0;
	public $getNewsInEvidenza;
	public $team = array();
	public $testimonial = array();
	public $faq = array();
	public $prodottiInPromozione = null;
	public $sectionsId = array();
	
	public $slide;
	public $pages = array(); // Array di pagina
	public $p = array(); // singola pagina
	public $cat = array(); // singola categoria
	
	public $recordHomeMeta = array(); // it contains the meta of the home page and the general meta of the website (title, keywords, meta description)
	public $defaultRegistrazione = array();
	
	public static $isPromo = false;
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		if (!defined("FRONT"))
			define('FRONT', ROOT);
		
		$cache = Cache_Html::getInstance();
		
		Domain::setPath();
		
		parent::__construct($model, $controller, $queryString, $application, $action);
			
		if (!$this->isNotFoundAndCached($action))
		{
			$this->setTabelleCacheAggiuntive($model, $controller, $queryString, $application, $action);
			
			Domain::$adminName = $this->baseUrlSrc."/admin";
			Domain::$publicUrl = $this->baseUrlSrc;
			
			TraduzioniModel::getInstance()->ottieniTraduzioni();
			
			// Variabili
			$this->model('VariabiliModel');
			
			if (empty(VariabiliModel::$valori))
				VariabiliModel::ottieniVariabili();
			
			if (!class_exists("Tema"))
				require(LIBRARY."/Application/Include/tema.php");
			
			// Imposta il tema
			Tema::set();
			
			$this->defaultRegistrazione = array(
				"nazione"		=>	v("nazione_default"),
				"tipo_cliente"	=>	v("tipo_cliente_default"),
			);
			
			if (v("carica_tutti_i_model"))
			{
				$this->model("CategoriesModel");
				$this->model("MenuModel");
				$this->model("PagesModel");
				$this->model("ImmaginiModel");
				$this->model("CartModel");
				$this->model("WishlistModel");
				$this->model("RigheModel");
				$this->model("OrdiniModel");
				$this->model("RegusersModel");
				$this->model("PromozioniModel");
				
				$this->model("AttributiModel");
				$this->model("AttributivaloriModel");
				$this->model("CombinazioniModel");
				$this->model("PagesattributiModel");
				$this->model("PagescarvalModel");
				
				$this->model("SpedizioniModel");
				$this->model("ScaglioniModel");
				$this->model("ImpostazioniModel");
				$this->model("LayerModel");
				$this->model("CorrieriModel");
				$this->model("CorrierispeseModel");
				$this->model("NazioniModel");
				$this->model("ClassiscontoModel");
				$this->model("ProvinceModel");
				$this->model("MarchiModel");
				$this->model("ContenutiModel");
				$this->model("DocumentiModel");
				$this->model("RuoliModel");
				$this->model("PersonalizzazioniModel");
				$this->model("TagModel");
				$this->model("TipiaziendaModel");
				$this->model("PagesregioniModel");
				$this->model("CaptchaModel");
				$this->model('ContattiModel');
				
				if (v("attiva_liste_regalo"))
					$this->model('ListeregaloModel');
				
				if (v("abilita_feedback"))
					$this->model("FeedbackModel");
			}
			
			RegioniModel::$nAlias = gtext(v("label_nazione_url"));
			RegioniModel::$rAlias = gtext(v("label_regione_url"));
			
			// Traduzioni sezione crud
			Lang_It_UploadStrings::$staticStrings = array(
				"error" => "<div class='".v("alert_error_class")."'>".gtext("Errore: verificare i permessi del file/directory")."</div>\n",
				"executed"	=>	"<div class='".v("alert_success_class")."'>".gtext("Operazione eseguita!")."</div>",
				"not-child" => "<div class='".v("alert_error_class")."'>".gtext("La cartella selezionata non è una sotto directory della directory base")."</div>\n",
				"not-dir" => "<div class='".v("alert_error_class")."'>".gtext("La cartella selezionata non è una directory")."</div>\n",
				"not-empty" => "<div class='".v("alert_error_class")."'>".gtext("La cartella selezionata non è vuota")."</div>\n",
				"no-folder-specified" => "<div class='".v("alert_error_class")."'>".gtext("Non è stata specificata alcuna cartella")."</div>\n",
				"no-file-specified" => "<div class='".v("alert_error_class")."'>".gtext("Non è stato specificato alcun file")."</div>\n",
				"not-writable" => "<div class='".v("alert_error_class")."'>".gtext("La cartella non è scrivibile")."</div>\n",
				"not-writable-file" => "<div class='".v("alert_error_class")."'>".gtext("Il file non è scrivibile")."</div>\n",
				"dir-exists" => "<div class='".v("alert_error_class")."'>".gtext("Esiste già una directory con lo stesso nome")."</div>\n",
				"no-upload-file" => "<div class='".v("alert_error_class")."'>".gtext("Non c'è alcun file di cui fare l'upload")."</div>\n",
				"size-over" => "<div class='".v("alert_error_class")."'>".gtext("La dimensione del file è troppo grande")."</div>\n",
				"not-allowed-ext" => "<div class='".v("alert_error_class")."'>".gtext("L'estensione del file che vuoi caricare non è consentita")."</div>\n",
				"not-allowed-mime-type" => "<div class='".v("alert_error_class")."'>".gtext("Il tipo MIME del file che vuoi caricare non è consentito")."</div>\n",
				"file-exists" => "<div class='".v("alert_error_class")."'>".gtext("Esiste già un file con lo stesso nome")."</div>\n",
			);
			
			ImpostazioniModel::init();
			
			$this->session('registered', array(
				new RegusersModel(),
				new RegsessioniModel(),
				new RegaccessiModel(),
				new ReggroupsModel(),
			), User::getTwoFactorModelFront());
			
			$this->s['registered']->checkStatus();
			
			$this->session('admin',array(
				new UsersModel(),
				new SessioniModel(),
				new AccessiModel(),
				new GroupsModel(),
			), User::getTwoFactorModelAdmin());
			
			$this->s['admin']->checkStatus();
			
			//controllo login
			$data['username'] = null;
			$data['islogged'] = false;
			$data['dettagliUtente'] = null;
			$data['iduser'] = 0;
			$data['nomeCliente'] = "";
			
			$data["adminUser"] = false;
			
			if ($this->s['registered']->status['status'] === 'logged')
			{
				$data['username'] = $this->s['registered']->status['user'];
				
				$res = $this->m('RegusersModel')->clear()->where(array("id_user"=>(int)$this->s['registered']->status['id_user']))->send();
				$data['dettagliUtente'] = $this->dettagliUtente = User::$dettagli = $res[0]['regusers'];
				
				if (User::$dettagli["has_confirmed"])
				{
					$this->s['registered']->logout();
					$this->redirect("");
				}
				
				$this->iduser = User::$id = (int)$this->s['registered']->status['id_user'];
				$data['iduser'] = $this->iduser;

				User::$email = User::$dettagli["username"];
				
				$data['islogged'] = true;
				$this->islogged = User::$logged = $data['islogged'];
				
				User::$groups = $this->s['registered']->status['groups'];
				
				$data['nomeCliente'] = User::$nomeCliente = (strcmp($data['dettagliUtente']["tipo_cliente"],"privato") === 0 || strcmp($data['dettagliUtente']["tipo_cliente"],"libero_professionista") === 0) ?  $data['dettagliUtente']["nome"] : $data['dettagliUtente']["ragione_sociale"];
				
				// Estraggo lo sconto dell'utente
				User::setClasseSconto();
				
				User::$ruid = $this->s['registered']->getUid();
				
				if (User::$dettagli["agente"])
					User::$isAgente = true;
				
				// Imposto lo stato loggato su Output
				Output::setHeaderValue("Status","logged");
				
				if (Params::$allowSessionIdFromGet)
					$this->s['registered']->setCookieFromGetToken();
				
				if (v("attiva_prezzi_ivati_in_carrello_per_utente_e_ordine"))
					VariabiliModel::$valori["prezzi_ivati_in_carrello"] = (int)User::$dettagli["prezzi_ivati_in_carrello"];
				
				// Controlla la scadenza dell'account
				$this->controllaScadenzaAccount();
				
				// Imposta le preferenze sulla base dell'utente loggato
				$this->settaPreferenzeUtenteLoggato();
			}
			
			if ($this->s['admin']->status['status'] === 'logged')
			{
				$data["adminUser"] = User::$adminLogged = true;
				$cache->loadHtml = false;
				
				Cache_Functions::getInstance()->setSaveToDisk(false);
				
				Cache_Db::$cacheFolder = null;
			}
			
			// Predisponi i filtri in coda nell'URL
			$this->predisponiAltriFiltri();
			
			$this->model("CaratteristichevaloriModel");
			
			$data["isProdotto"] = false;
			
			$this->initCookieEcommerce();
			
			$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
			
			// Controlla quantità prodotti in base a lista (se hai prodotti in una lista regalo)
			$this->m("CartModel")->controllaQuantitaProdottiListaInCarrello();
			
			if (v("ecommerce_attivo"))
			{
				if (v("imposta_la_nazione_dell_utente_a_quella_nell_url") && ($controller != "ordini" || ($action == "index" || $action == "totale")))
				{
					User::setUserCountryFromPostSpedizioneOrFromUrl();
					
					// Ricolcola prezzi come da listino se è cambiata la nazione
					$this->m("CartModel")->checkERicalcolaPrezziListino();
				}
				
				// Correggi decimali imponibili sulla base dell'IVA estera
				$this->m("CartModel")->correggiPrezzi();
			}
			
			$data["carrello"] = $this->m("CartModel")->getProdotti();
			
			// Recuperta dati da cliente loggato
			$this->m("CartModel")->collegaDatiCliente($data["carrello"]);
			
			$data["prodInCart"] = $this->m("CartModel")->numberOfItems();
			$data["prodInWishlist"] = $this->m("WishlistModel")->numberOfItems();
			
			if (v("attiva_liste_regalo") && (int)$data["prodInCart"] === 0)
				ListeregaloModel::unsetCookieIdLista();
			
			Domain::$name = $this->baseUrl;
			
			if (in_array($_SERVER['REQUEST_URI'],array("/home","/home/index","/home/index/")))
			{
				$this->redirect("");
			}
			
			$clean["idShop"] = $data["idShop"] = $this->idShop = CategoriesModel::$idShop = $this->m("CategoriesModel")->getShopCategoryId();
			
			if (!$cache->saved() && $this->estratiDatiGenerali)
				$this->estratiDatiGenerali($controller, $action);
			
			// Modali
			if (v("attiva_modali") && $controller == "home" && $action == "index" && isset($_COOKIE["ok_cookie"]))
				$data["modali_frontend"] = $this->m("PagesModel")->getModaliFrontend();
			
			Lang::$current = Params::$lang;
			
			$this->append($data);
			
			Params::$rewriteStatusVariables = false;
			
			VariabiliModel::inizializza();
			VariabiliModel::checkCookieTerzeParti();
			
			if (v("ecommerce_attivo"))
			{
				if (getSubTotalN() > 9999999)
				{
					$this->m("CartModel")->emptyCart();
				}
				
				$this->m("PagesModel")->aggiornaStatoProdottiInPromozione();
				
				if (!v("usa_codice_combinazione_in_url_prodotto") && !v("usa_alias_combinazione_in_url_prodotto"))
				{
					VariabiliModel::$valori["aggiorna_pagina_al_cambio_combinazione_in_prodotto"] = 0;
					VariabiliModel::$valori["immagini_separate_per_variante"] = 0;
				}
				
				if ($controller != "ordini" || $action != "summary")
					User::impostaNazioneSpedizioneECorriereDaUrl();
			}
		}
	}
	
	protected function correggiValoriPostFormRegistrazioneEOrdine()
	{
		if (v("sistema_maiuscole_clienti"))
		{
			if (isset($_POST["nome"]))
				$_POST["nome"] = eg_ucfirst($_POST["nome"]);
			
			if (isset($_POST["cognome"]))
				$_POST["cognome"] = eg_ucfirst($_POST["cognome"]);
			
			if (isset($_POST["codice_fiscale"]))
				$_POST["codice_fiscale"] = eg_strtoupper($_POST["codice_fiscale"]);
		}
		
		if (v("clienti_tutto_maiuscolo"))
		{
			$campiInMaiuscoloFatturazione = array("nome", "cognome", "indirizzo", "citta", "codice_fiscale", "ragione_sociale");
			$campiInMaiuscoloSpedizione = array("indirizzo_spedizione", "citta_spedizione", "destinatario_spedizione");
			
			if ($this->action == "add" || $this->action == "modify")
				$campiInMaiuscolo = $campiInMaiuscoloFatturazione;
			else if ($this->action == "spedizione")
				$campiInMaiuscolo = $campiInMaiuscoloSpedizione;
			else
				$campiInMaiuscolo = array_merge($campiInMaiuscoloFatturazione, $campiInMaiuscoloSpedizione);
			
			foreach ($campiInMaiuscolo as $campo)
			{
				if (isset($_POST[$campo]) && trim($_POST[$campo]))
					$_POST[$campo] = eg_strtoupper($_POST[$campo]);
			}
		}
	}
	
	protected function getPageNotFoundFileName()
	{
		return "404_".Params::$langCountry.".html";
	}
	
	// Se trova il file 404 oppure no
	protected function isNotFoundAndCached($action = "notfound")
	{
		$path = ROOT."/Logs/404/".$this->getPageNotFoundFileName();
		
		if ($action == "notfound" && file_exists($path))
			return true;
		
		return false;
	}
	
	// Controlla la scadenza dell'account
	protected function controllaScadenzaAccount()
	{
		if (v("attiva_scadenza_account") && $this->dettagliUtente["data_scadenza"])
		{
			if (checkIsoDate($this->dettagliUtente["data_scadenza"]))
			{
				$dataScadenza = DateTime::createFromFormat("Y-m-d", $this->dettagliUtente["data_scadenza"]);
				$dataScadenza->setTime(0, 0, 0);
				$dataOggi = new DateTime();
				$dataOggi->setTime(0, 0, 0);
				
				if ($dataOggi >= $dataScadenza)
				{
					$this->s['registered']->logout();
					
					$idScaduto = PagineModel::gTipoPagina("SCADUTO");
					
					if ($idScaduto)
						$this->redirect(getUrlAlias($idScaduto).F::partial());
					else
						$this->redirect("");
				}
			}
		}
	}
	
	// Imposta le preferenze sulla base dell'utente loggato
	protected function settaPreferenzeUtenteLoggato()
	{
		
	}
	
	protected function checkCacheHtmlAttiva()
	{
		return true;
	}
	
	// Setta la lingua e la nazione e ricarica le traduzioni
	protected function settaLinguaENazione($lingua, $nazione = null)
	{
		if (count(Params::$frontEndLanguages) > 0 && in_array($lingua,Params::$frontEndLanguages))
		{
			Params::$lang = sanitizeAll($lingua);

			if ($nazione && count(Params::$frontEndCountries) > 0 && in_array(strtolower($nazione),Params::$frontEndCountries))
				Params::$country = sanitizeAll(strtolower($nazione));

			$this->baseUrl = rtrim(Url::getRoot(),"/");
			$this->theme->baseUrl = $this->baseUrl;

			TraduzioniModel::getInstance()->ottieniTraduzioni();

			CategoriesModel::$strutturaCategorie = [];
			CategoriesModel::$strutturaCategorieRid = [];

			$this->estratiDatiGenerali($this->controller, $this->action);
		}
	}

	protected function estratiDatiGenerali($controller, $action)
	{
		if (v("estrai_in_evidenza_home"))
			$data["prodottiInEvidenza"] = $this->prodottiInEvidenza = PagesModel::getProdottiInEvidenza();
		
		if (v("mostra_avvisi"))
			$data["avvisi"] = $this->m("PagesModel")->clear()->getQueryClauseProdotti()->where(array(
				"categories.section"	=>	"avvisi",
				"attivo"=>"Y",
			))->send();
		
		$data["isHome"] = false;
		$data['headerClass'] = "";
		$data['customHeaderClass'] = "";
		$data['inlineCssFile'] = "";
		
		$data["categoriaShop"] = $this->m("CategoriesModel")->selectId(CategoriesModel::$idShop);
		
		$data["idBlog"] = 0;
		
		if (v("blog_attivo"))
		{
			$idBlog = $this->sectionsId["blog"] = $data["idBlog"] = (int)CategoriesModel::getIdCategoriaDaSezione("blog");

			$data["ultimiArticoli"] = $this->getNewsInEvidenza = $this->m('PagesModel')->clear()->select("*")
				->addJoinTraduzionePagina()
				->where(array(
					"attivo" => "Y",
				))
				->addWhereCategoria((int)$idBlog)
				->orderBy("data_news desc,pages.id_order desc")->limit(v("numero_news_in_evidenza"))->send();
		}
		
		if (v("team_attivo"))
		{
			$idTeam = (int)$this->m("CategoriesModel")->clear()->where(array(
				"section"	=>	"team",
			))->field("id_c");
			
			$data["team"] = $this->team = $this->m('PagesModel')->clear()->select("*")
				->addJoinTraduzionePagina()
				->where(array(
					"attivo"	=>	"Y",
					"id_c"		=>	(int)$idTeam,
				))->orderBy("pages.id_order")->send();
		}
		
		if (v("mostra_testimonial"))
		{
			$idTest = (int)CategoriesModel::getIdCategoriaDaSezione("testimonial");
			
			$data["testimonial"] = $this->testimonial = $this->m('PagesModel')->clear()->select("*")
				->addJoinTraduzionePagina()
				->where(array(
					"attivo"	=>	"Y",
					"id_c"		=>	$idTest,
				))->orderBy("pages.id_order")->send();
		}
		
		if (v("mostra_faq"))
		{
			$idFaq = $this->sectionsId["faq"] = $data["idFaq"] = (int)CategoriesModel::getIdCategoriaDaSezione("faq");
			
			$data["faq"] = $this->faq = $this->m('PagesModel')->clear()->select("*")
				->addJoinTraduzionePagina()
				->where(array(
					"attivo"	=>	"Y",
					"in_evidenza"=>"Y",
					"id_c"		=>	$idFaq,
				))->orderBy("pages.id_order")->send();
		}
		
		if (v("estrai_categorie_blog"))
			$data["categorieBlog"] = $this->m("CategoriesModel")->children(87, false, false);
		
		if (v("estrai_in_promozione_home"))
			$data["inPromozione"] = $data["prodottiInPromozione"] = $this->prodottiInPromozione = PagesModel::getProdottiInPromo();
		
		if (v("estrai_elenco_categorie_prodotti_base_controller"))
		{
			$data["alberoCategorieProdotti"] = $this->m("CategoriesModel")->recursiveTree(CategoriesModel::$idShop,2);
			$data["alberoCategorieProdottiConShop"] = array($data["categoriaShop"]) + $data["alberoCategorieProdotti"];
		}
		
		$data["elencoCategorieFull"] = $this->elencoCategorieFull = CategoriesModel::$elencoCategorieFull = $this->m('CategoriesModel')->clear()
			->addJoinTraduzioneCategoria()
			->where(array("id_p"=>CategoriesModel::$idShop))
			->orderBy("lft")
			->save()
			->send();
		
		$data["tipiPagina"] = PagesModel::$tipiPaginaId = $this->m("PagesModel")->clear()->where(array(
			"ne"		=>	array("tipo_pagina" => ""),
			"attivo"	=>	"Y",
			"principale"	=>	"Y",
		))->toList("tipo_pagina", "id_page")->send();
		
		$data["tipiClienti"] = TipiclientiModel::getArrayTipi();
		
		$this->estraiNazioni();
		
		// $data["selectNazioni"] = array(""	=>	gtext("Seleziona",true)) + $this->m("NazioniModel")->selectNazioniAttive();
		// $data["selectNazioniSpedizione"] = array(""	=>	gtext("Seleziona",true)) + $this->m("NazioniModel")->selectNazioniAttiveSpedizione();
		
		$data["selectRuoli"] = RuoliModel::$listaElementi = $this->m("RuoliModel")->selectTipi(true);
		
		if (v("attiva_tipi_azienda"))
			$data["selectTipiAziende"] = $this->m("TipiaziendaModel")->selectTipi(true);
		
		$data["isPromo"] = self::$isPromo;
		
		$data["arrayLingue"] = array();
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l;
		}
		
		$data["arrayLingueCompleto"] = LingueModel::getValoriAttivi();
		
		$this->generaMenu();
		
		$data["langDb"] = $this->langDb = Lang::$langDb = null;
		
		$data["pagesCss"] = $data["paginaGenerica"] = "";
		
		if (!($controller == "contenuti" && $action == "index"))
			$this->estraiDatiFiltri();
		
		if (v("attiva_in_evidenza_nazioni"))
			NazioniModel::$elencoNazioniInEvidenza = $this->m("NazioniModel")->clear()->where(array(
				"in_evidenza"	=>	"Y"
			))->toList("iso_country_code")->send();
		
		$data["title"] =  gtext(ImpostazioniModel::$valori["title_home_page"]);
		$data["meta_description"] = F::meta(htmlentitydecode(ImpostazioniModel::$valori["meta_description"]));
		$data["keywords"] = F::meta(htmlentitydecode(ImpostazioniModel::$valori["keywords"]));
		
		if (v("usa_meta_pagina_home"))
		{
			PagesModel::$homeIdPage = (int)$this->m("PagesModel")->clear()->where(array(
				"tipo_pagina"	=>	"HOME",
			))->field("id_page");
			
			if (PagesModel::$homeIdPage)
			{
				$recordHome = $this->recordHomeMeta = $this->m("PagesModel")->clear()->select("pages.meta_title,pages.meta_description,pages.keywords,contenuti_tradotti.meta_title,contenuti_tradotti.meta_description,contenuti_tradotti.keywords")->where(array(
					"id_page"	=>	(int)PagesModel::$homeIdPage,
				))->addJoinTraduzionePagina()->first();
				
				if (!empty($recordHome))
				{
					if (field($recordHome, "meta_description"))
						$data["meta_description"] = F::meta(field($recordHome, "meta_description"));
					
					if (field($recordHome, "keywords"))
						$data["keywords"] = F::meta(field($recordHome, "keywords"));
				}
			}
		}
		
		$this->append($data);
	}
	
	protected function estraiNazioni()
	{
		$data["selectNazioni"] = array(""	=>	gtext("Seleziona",true)) + $this->m("NazioniModel")->selectNazioniAttive();
		$data["selectNazioniSpedizione"] = array(""	=>	gtext("Seleziona",true)) + $this->m("NazioniModel")->selectNazioniAttiveSpedizione();
		
		$this->append($data);
	}
	
	protected function generaMenu()
	{
		$res = $resMobile = $this->m("MenuModel")->getTreeWithDepth(v("profondita_menu_desktop"), null, Params::$lang);
		$data["menu"] = $this->m("MenuModel")->getMenu($res,false, Params::$lang);
		
		if (v("profondita_menu_mobile") != v("profondita_menu_desktop"))
			$resMobile = $this->m("MenuModel")->getTreeWithDepth(v("profondita_menu_mobile"), null, Params::$lang);
		
		if (v("abilita_menu_semplice"))
			$data["menuSemplice"] = $this->m("MenuModel")->getMenu($resMobile,false, Params::$lang, true);
		
		$data["menuMobile"] = $this->m("MenuModel")->getMenu($resMobile,false, Params::$lang, false, true);
		
		$this->append($data);
	}
	
	protected function initCookieEcommerce()
	{
		// Recupero il cookie di contatto
		$this->m("ContattiModel")->getCookie();
		
		// Recupero il cookie di ordinamento prodotti
		$this->m("PagesModel")->getCookieOrdinamento();
		
		if (!v("ecommerce_attivo"))
			return;

		//set the cookie for the cart
		CartModel::skipCheckCart();
		$this->m("CartModel")->setCartUid();
		
		if (v("traccia_sorgente_utente"))
			User::getSorgente();
		
		OrdiniModel::setStatiOrdine();
		OrdiniModel::setPagamenti();
		
		//set the cookie for the wishlist
		if (isset($_COOKIE["wishlist_uid"]) && $_COOKIE["wishlist_uid"] && (int)strlen($_COOKIE["wishlist_uid"]) === 32 && ctype_alnum((string)$_COOKIE["wishlist_uid"]))
		{
			User::$wishlist_uid = sanitizeAll((string)$_COOKIE["wishlist_uid"]);
		}
		else
		{
			User::$wishlist_uid = md5(randString(10).microtime().uniqid(mt_rand(),true));
			$time = time() + v("durata_carrello_wishlist_coupon");
			setcookie("wishlist_uid",User::$wishlist_uid,$time,"/");
		}
		
		//setto il coupon se presente
		User::$coupon = null;
		
		if (isset($_POST["invia_coupon"]))
		{
			IpcheckModel::check("POST_COUPON");
			
			User::$coupon = $this->request->post("il_coupon","","sanitizeAll");
			
			$time = time() + v("durata_carrello_wishlist_coupon");
			setcookie("coupon",User::$coupon,$time,"/");
		}
		else
		{
			if (isset($_COOKIE["coupon"]))
				User::$coupon = sanitizeAll($_COOKIE["coupon"]);
		}
		
		if (User::$coupon)
		{
			if ($this->m("PromozioniModel")->isActiveCoupon(User::$coupon))
			{
				// Estraggo tutti i prodotti della promozione
				User::$prodottiInCoupon = $this->m("PromozioniModel")->elencoProdottiPromozione(User::$coupon);
			}
			else
			{
				//setto il coupon se presente
				User::$coupon = null;
				
				if (isset($_COOKIE["coupon"]))
					setcookie("coupon", "", time()-3600,"/");
			}
		}
		
		if (v("attiva_liste_regalo"))
			ListeregaloModel::getCookieIdLista();
		
		CartModel::attivaDisattivaSpedizione();
		
// 		if (CartModel::soloProdottiSenzaSpedizione())
// 			VariabiliModel::$valori["attiva_spedizione"] = 0;
	}
	
	protected function predisponiAltriFiltri()
	{
		// Filtro stato prodotto IN EVIDENZA
		$aliasStatoProdotto = gtext(v("alias_stato_prodotto"), true, "none", null, 0);
		AltriFiltri::$altriFiltri[] = $aliasStatoProdotto;
		AltriFiltri::$altriFiltriTipi["stato-prodotto"] = $aliasStatoProdotto;
		AltriFiltri::$aliasValoreTipoInEvidenza = array(
			gtext(v("alias_valore_tipo_in_evidenza"), true, "none", null, 0),
			gtext(v("valore_tipo_in_evidenza"), true, "none", null, 0),
		);
		
		// Filtro stato prodotto NUOVO
		$aliasStatoProdotto = gtext(v("alias_stato_prodotto_nuovo"), true, "none", null, 0);
		AltriFiltri::$altriFiltri[] = $aliasStatoProdotto;
		AltriFiltri::$altriFiltriTipi["stato-prodotto-nuovo"] = $aliasStatoProdotto;
		AltriFiltri::$aliasValoreTipoNuovo = array(
			gtext(v("alias_valore_tipo_nuovo"), true, "none", null, 0),
			gtext(v("valore_tipo_nuovo"), true, "none", null, 0),
		);
		
		// Filtro stato prodotto PROMO
		$aliasStatoProdotto = gtext(v("alias_stato_prodotto_promo"), true, "none", null, 0);
		AltriFiltri::$altriFiltri[] = $aliasStatoProdotto;
		AltriFiltri::$altriFiltriTipi["stato-prodotto-promo"] = $aliasStatoProdotto;
		AltriFiltri::$aliasValoreTipoPromo = array(
			gtext(v("alias_valore_tipo_promo"), true, "none", null, 0),
			gtext(v("valore_tipo_promo"), true, "none", null, 0),
		);
		
		if (v("mostra_fasce_prezzo") || v("filtro_prezzo_slider"))
		{
			$this->model("FasceprezzoModel");
			
			$aliasFasciaPrezzo = gtext(v("alias_fascia_prezzo"), true, "none", null, 0);
			AltriFiltri::$altriFiltri[] = $aliasFasciaPrezzo;
			AltriFiltri::$altriFiltriTipi["fascia-prezzo"] = $aliasFasciaPrezzo;
		}
	}
	
	protected function estraiDatiFiltri()
	{
		if (v("usa_tag"))
		{
			$data["elencoTagFull"] = $this->elencoTagFull = $data["elencoTagFullFiltri"] = $this->m("TagModel")->clear()->addJoinTraduzione()->where(array(
				"attivo"	=>	"Y",
			))->orderBy("tag.titolo")->send();
			
// 			if (v("attiva_filtri_successivi"))
				$data["elencoTagFullFiltri"] = $this->m("TagModel")->select("*,count(tag.id_tag) as numero_prodotti")
				->inner(array("pagine"))
				->inner("pages")->on("pages.id_page = pages_tag.id_page")
				->addWhereAttivo()->groupBy("tag.id_tag")->sWhereFiltriSuccessivi("[tag]")->send();
		}
		
		if (v("usa_marchi"))
		{
			if (v("estrai_elenco_marchi"))
			{
				$data["elencoMarchi"] = $this->m("MarchiModel")->clear()->orderBy("titolo")->toList("id_marchio", "titolo")->send();
				
				$data["elencoMarchiFull"] = $this->elencoMarchiFull = $data["elencoMarchiFullFiltri"] = $this->m("MarchiModel")->clear()->addJoinTraduzione()->orderBy("marchi.titolo")->send();
				$data["elencoMarchiNuoviFull"] = $this->elencoMarchiNuoviFull = $this->m("MarchiModel")->clear()->where(array(
					"nuovo"	=>	"Y",
				))->addJoinTraduzione()->orderBy("marchi.titolo")->send();
			}
			
// 			if (v("attiva_filtri_successivi"))
			$this->m("MarchiModel")->clear()->select("*,count(marchi.id_marchio) as numero_prodotti")->inner(array("pagine"))->groupBy("marchi.id_marchio")->sWhereFiltriSuccessivi("[marchio]");
			
			if (!v("attiva_filtri_successivi"))
				$this->m("MarchiModel")->addWhereAttivo();
			
			$data["elencoMarchiFullFiltri"] =  $this->m("MarchiModel")->send();
		}
		
		if (!v("ecommerce_attivo"))
		{
			if (isset($data))
				$this->append($data);
			
			return;
		}
		
		$data["filtriCaratteristiche"] = array();
		
		if (v("attiva_filtri_caratteristiche"))
			$data["filtriCaratteristiche"] = PagescarvalModel::getFiltriCaratteristiche();
		
		$data["filtriNazioni"] = $data["filtriRegioni"] = array();
		
		if (v("attiva_localizzazione_prodotto"))
		{
			$data["filtriNazioni"] = $this->m("PagesregioniModel")->filtriNazioni(v("attiva_filtri_successivi"));
			
			$data["filtriRegioni"] = $this->m("PagesregioniModel")->filtriRegioni(v("attiva_filtri_successivi"));
		}
		
		$this->append($data);
	}
	
	protected function setCondizioniDatiUtente($idUser = 0)
	{
		
	}
	
	protected function formRegistrazione()
	{
		if( !session_id() )
			session_start();
		
		// Sistema maiuscole
		$this->correggiValoriPostFormRegistrazioneEOrdine();
		
		$logSubmit = new LogModel();
		
		// Setta password
		$this->m("RegusersModel")->settaPassword();
		
		$data['notice'] = null;
		$data['isRegistrazione'] = true;
		
		$tipo_cliente = $this->request->post("tipo_cliente","","sanitizeAll");
		$pec = $this->request->post("pec","","sanitizeAll");
		$codiceDestinatario = $this->request->post("codice_destinatario","","sanitizeAll");
		
// 		$baseFields = v("insert_account_fields");
		
		$baseFields = OpzioniModel::stringaValori("CAMPI_SALVATAGGIO_UTENTE");
		
		$baseFields .= ",accetto";
		
		if (v("attiva_accetto_2"))
			$baseFields .= ",accetto_2";
		
		// BASE: 'nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,username,accetto,tipo_cliente,nazione,pec,codice_destinatario,dprovincia,telefono_2';
		
		$fields = $baseFields.',password:'.PASSWORD_HASH;
		
		if (v("attiva_ruoli"))
			$fields .= ",id_ruolo";
		
		if (v("attiva_tipi_azienda"))
			$fields .= ",id_tipo_azienda";
		
		$this->m('RegusersModel')->setFields($fields,'strip_tags');
		$datiCliente = $this->m('RegusersModel')->values;
		$this->m('RegusersModel')->sanitize("sanitizeAll");
		
		$this->m('RegusersModel')->setConditions($tipo_cliente, "insert", $pec, $codiceDestinatario);
		
		$this->m('RegusersModel')->fields = "$baseFields,newsletter,password";
		
		if (v("account_attiva_conferma_password"))
			$this->m('RegusersModel')->fields .= ",confirmation";
		
		if (v("account_attiva_conferma_username"))
			$this->m('RegusersModel')->fields .= ",conferma_username";
		
		if (v("attiva_ruoli"))
			$this->m('RegusersModel')->fields .= ",id_ruolo";
		
		if (v("attiva_tipi_azienda"))
			$this->m('RegusersModel')->fields .= ",id_tipo_azienda";
		
		$this->setCondizioniDatiUtente(0);
		
		if (isset($_POST['updateAction']))
		{
			if (CaptchaModel::getModulo()->checkRegistrazione())
			{
				if ($this->m('RegusersModel')->checkConditions('insert'))
				{
					$tokenConferma = $this->m('RegusersModel')->values['confirmation_token'] = md5(randString(20).microtime().uniqid(mt_rand(),true));
					$tokenReinvio = $this->m('RegusersModel')->values['token_reinvio'] = md5(randString(30).microtime().uniqid(mt_rand(),true));
					$codiceConfermaRegistrazione = generateString(v("conferma_registrazione_numero_cifre_codice_verifica"), "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ");
					
					$this->m('RegusersModel')->values['codice_verifica'] = sanitizeAll(Aes::encrypt($codiceConfermaRegistrazione));
					
					if ($this->registrazioneAgente)
						$this->m('RegusersModel')->values['agente'] = 1;
					
					if ($this->m('RegusersModel')->insert())
					{
						$lId = $this->m('RegusersModel')->lastId();
						
						$password = $this->request->post("password","","none");
						$clean["username"] = $this->request->post("username","","sanitizeAll");
						
						$_SESSION["email_carrello"] = $clean["username"];
						
						$statoAccessoUtente = "login-error";
						
						//loggo l'utente
						if (!v("conferma_registrazione") && !v("gruppi_inseriti_da_approvare_alla_registrazione"))
						{
							$statoAccessoUtente = $this->s['registered']->login($clean["username"],$password);
							
							if ($statoAccessoUtente == "accepted")
								$this->hookAfterLogin();
						}
						
						// Iscrizione alla newsletter
						if (isset($_POST["newsletter"]) && IntegrazioninewsletterModel::integrazioneAttiva())
						{
							IntegrazioninewsletterModel::getModulo()->iscrivi(IntegrazioninewsletterModel::elaboraDati($datiCliente));
							
							// Inserisco il contatto
							$this->m('ContattiModel')->insertDaArray($datiCliente, "NEWSLETTER_DA_REGISTRAZIONE");
						}
						
						$_SESSION['result'] = 'utente_creato';
						
						if ($this->registrazioneAgente)
							$_SESSION['result'] = 'agente_creato';
						
						$_SESSION['token_reinvio'] = $tokenReinvio;
						
						if (isset($_SESSION['conferma_utente']))
							unset($_SESSION['conferma_utente']);
						
						$res = MailordiniModel::inviaCredenziali($lId, array(
							"username"	=>	$clean["username"],
							"password"	=>	$password,
							"tokenConferma"	=>	$tokenConferma,
							"agente"	=>	$this->registrazioneAgente ? 1 : 0,
							"codiceVerifica"	=>	$codiceConfermaRegistrazione,
						));
						
						if (!v("conferma_registrazione"))
							$this->maindaMailNegozioNuovaRegistrazione($lId);
						
						$logSubmit->write(LogModel::LOG_REGISTRAZIONE, LogModel::REGISTRAZIONE_ESEGUITA);
						
						$urlRedirect = RegusersModel::getUrlRedirect();
						
						if (!v("conferma_registrazione") && !v("gruppi_inseriti_da_approvare_alla_registrazione"))
						{
							if ($statoAccessoUtente == 'two-factor')
								$this->redirectTwoFactorSendMail();
							else if ($urlRedirect)
								HeaderObj::location($urlRedirect);
							else
								$this->redirect("avvisi");
						}
						else if (v("conferma_registrazione"))
							$this->redirect("conferma-account/$tokenConferma");
						else
							$this->redirect("avvisi");
					}
					else
					{
						$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m('RegusersModel')->notice;
					}
				}
				else
				{
					$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m('RegusersModel')->notice;
				}
			}
			else
			{
				ob_start();
				include(tpf(CaptchaModel::getModulo()->getErrorIncludeFile()));
				$data['notice'] = ob_get_clean();
				$data['notice'] .= $this->m('RegusersModel')->notice;
				$this->m('RegusersModel')->result = false;
				
				$logSubmit->setSpam();
			}
		}
		
		$logSubmit->setErroriSubmit($data['notice']);
		$logSubmit->write(LogModel::LOG_REGISTRAZIONE, $data['notice'] ? LogModel::ERRORI_VALIDAZIONE : "");
		
		$data['values'] = $this->m('RegusersModel')->getFormValues('insert','sanitizeHtml',null,$this->defaultRegistrazione);
		
		$this->estraiProvince();
		// $data['province'] = $this->m('ProvinceModel')->selectTendina("nazione");
		// $data['provinceSpedizione'] = $this->m('ProvinceModel')->selectTendina("nazione_spedizione");
		
		if (strcmp($data['values']["tipo_cliente"],"") === 0)
		{
			$data['values']["tipo_cliente"] = "privato";
		}
		
		$this->append($data);
	}
	
	protected function estraiProvince()
	{
		$data['province'] = $this->m('ProvinceModel')->selectTendina("nazione");
		$data['provinceSpedizione'] = $this->m('ProvinceModel')->selectTendina("nazione_spedizione");
		
		$this->append($data);
	}
	
	protected function maindaMailNegozioNuovaRegistrazione($idUser)
	{
		$datiCliente = $this->m('RegusersModel')->selectId((int)$idUser);
		
		if (empty($datiCliente))
			return;
		
		$clean["username"] = $datiCliente["username"];
		
		if ($datiCliente["agente"])
			$this->registrazioneAgente = true;
		
		ob_start();
		include tpf("Regusers/mail_al_negozio_registr_nuovo_cliente.php");
		$output = ob_get_clean();
		
		$emailCMS = (ImpostazioniModel::$valori["mail_registrazione_utenti"] && checkMail(ImpostazioniModel::$valori["mail_registrazione_utenti"])) ? ImpostazioniModel::$valori["mail_registrazione_utenti"] : Parametri::$mailInvioOrdine;
		
		$res = MailordiniModel::inviaMail(array(
			"emails"	=>	array($emailCMS),
			"oggetto"	=>	$datiCliente["agente"] ? "invio dati nuovo agente" : "invio dati nuovo utente",
			"testo"		=>	$output,
			"tipologia"	=>	"ISCRIZIONE AL NEGOZIO",
			"id_user"	=>	(int)$idUser,
			"id_page"	=>	0,
		));
	}
	
	protected function inserisciFeedback($id)
	{
		if (!v("abilita_feedback"))
			return;
		
		if( !session_id() )
			session_start();
		
		FeedbackModel::gIdProdotto();
		
		if ($this->m('PagesModel')->checkTipoPagina($id, "FORM_FEEDBACK"))
		{
			if (!isset(FeedbackModel::$idProdotto) || !$this->m('PagesModel')->isProdotto((int)FeedbackModel::$idProdotto))
				$this->redirect("");
			
			if (!v("permetti_aggiunta_feedback"))
				$this->redirect("");
			
			$par = $this->m("PagesModel")->parents((int)FeedbackModel::$idProdotto,false,false,Params::$lang);
			
			//tolgo la root
			array_shift($par);
			
// 			print_r($par);
			
			$data["breadcrumb"] = $this->breadcrumbHtml = $this->breadcrumb("page", true, "&raquo;", $par).v("divisone_breadcrum").$this->breadcrumb("page");
			
			Domain::$currentUrl =  $this->getCurrentUrl().FeedbackModel::getCurrUrlIdRif();
			
			if (User::$id)
			{
				$_POST["email"] = User::$dettagli["username"];
				
				if (!v("feedback_permetti_di_editare_nome_se_loggato"))
					$_POST["autore"] = User::$nomeCliente;
			}
			
			$campiForm = "autore,testo,email,accetto,accetto_feedback,voto";
			
			$this->m('FeedbackModel')->strongConditions['insert'] = array(
				'checkNotEmpty'	=>	$campiForm,
				'checkMail'		=>	'email|'.gtext("Si prega di controllare il campo Email").'<div class="evidenzia">class_email</div>',
				'checkIsStrings|1,2,3,4,5'		=>	'voto|'.gtext("Si prega di scegliere un punteggio").'<div class="evidenzia">class_voto</div>',
			);
			
			$campiFormInsert = $campiForm .= ",id_c";
			$this->m('FeedbackModel')->setFields($campiForm,'strip_tags');
			
			$esitoInvio = "KO";
			
			if (isset($_POST['inviaFeedback']) && (!v("feedback_solo_se_loggato") || User::$logged))
			{
				IpcheckModel::check("POST_FEEDBACK");
				
				// Imposto l'output in JSON
				if (isset($_POST['ajaxsubmit']))
				{
					Output::setJson();
					$this->clean();
				}
				
				if (CaptchaModel::getModulo()->check())
				{
					if ($this->m('FeedbackModel')->checkConditions('insert'))
					{
						$this->m('FeedbackModel')->setUserData();
						
						$valoriEmail = $this->m('FeedbackModel')->values;
						
						$this->m('FeedbackModel')->sanitize("sanitizeAll");
						
						if (!v("feedback_solo_se_loggato") || $this->m('FeedbackModel')->numeroFeedbackPagina(FeedbackModel::$idProdotto) < v("feedback_max_per_prodotto"))
						{
							if ($this->m('FeedbackModel')->insert())
							{
								$esitoInvio = "OK";
								
								$lId = $this->m('FeedbackModel')->lastId();
								
								$_SESSION["email_carrello"] = sanitizeAll($valoriEmail["email"]);
								
								$fonte = "FORM_FEEDBACK";
								
								// Inserisco il contatto
		// 						$idContatto = $this->m('ContattiModel')->insertDaArray($valoriEmail, $fonte);
								
								$pagina = $this->m("PagesModel")->selectId((int)FeedbackModel::$idProdotto);
								
								$oggetto = "inserimento valutazione prodotto";
								
								ob_start();
								include tpf("Elementi/Mail/mail_form_feedback_cliente.php");
								$output = ob_get_clean();
								
								$res = MailordiniModel::inviaMail(array(
									"emails"	=>	array($valoriEmail["email"]),
									"oggetto"	=>	$oggetto,
									"testo"		=>	$output,
									"tipologia"	=>	"FEEDBACK_CLIENTE",
									"id_user"	=>	(int)User::$id,
									"id_page"	=>	(int)FeedbackModel::$idProdotto,
								));
								
	// 							if ($res)
	// 							{
									ob_start();
									include (tpf("Elementi/Mail/mail_form_feedback_negozio.php"));
									$output = ob_get_clean();
									
									$res = MailordiniModel::inviaMail(array(
										"emails"	=>	array(Parametri::$mailInvioOrdine),
										"oggetto"	=>	$oggetto,
										"testo"		=>	$output,
										"tipologia"	=>	"FEEDBACK_NEGOZIO",
										"id_user"	=>	(int)User::$id,
										"id_page"	=>	(int)FeedbackModel::$idProdotto,
										"reply_to"	=>	$valoriEmail["email"],
									));
	// 							}
								
								$idGrazieFeedback = PagineModel::gTipoPagina("GRAZIE_FEEDBACK");
								$idGrazie = PagineModel::gTipoPagina("GRAZIE");
								
								if ($idGrazieFeedback)
									$idGrazie = $idGrazieFeedback;
								
								if (Output::$html)
								{
									if ($idGrazie)
										$this->redirect(getUrlAlias($idGrazie));
									else
										$this->redirect("grazie.html");
								}
								else
								{
									$pageGrazieDetails = null;
									
									if ($idGrazie)
										$pageGrazieDetails = PagesModel::getPageDetails($idGrazie);
										
									if ($pageGrazieDetails)
										Output::setBodyValue("Notice", "<div class='".v("alert_success_class")."'>".htmlentitydecode(field($pageGrazieDetails, "description"))."</div>");
									else
										Output::setBodyValue("Notice", "<div class='".v("alert_success_class")."'>".gtext("Il vostro messaggio è stato correttamente inviato")."</div>");
								}
							}
							else
							{
								$erroreInvio = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m('FeedbackModel')->notice;
								
								FeedbackModel::$sNotice = $erroreInvio;
								Output::setBodyValue("Notice", $erroreInvio);
							}
						}
						else
						{
							$this->m('FeedbackModel')->result = false;
							
							$erroreInvio = "<div class='".v("alert_error_class")."'>".gtext("Hai già aggiunto una valutazione a questa pagina")."</div>";
							
							FeedbackModel::$sNotice = $erroreInvio;
							Output::setBodyValue("Notice", $erroreInvio);
						}
					}
					else
					{
						$erroreInvio = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m('FeedbackModel')->notice;
						
						FeedbackModel::$sNotice = $erroreInvio;
						Output::setBodyValue("Notice", $erroreInvio);
					}
				}
				else
				{
					ob_start();
					include(tpf(CaptchaModel::getModulo()->getErrorIncludeFile()));
					$erroreInvio = ob_get_clean();
				
// 					$erroreInvio = "<div class='".v("alert_error_class")."'>".gtext("errore nell'invio del messaggio, per favore riprova più tardi")."</div>";
					
					FeedbackModel::$sNotice = $erroreInvio;
					Output::setBodyValue("Notice", $erroreInvio);
				}
			}
			
			$defaultValues = array(
				"voto"	=>	0,
				"id_c"	=>	FeedbackModel::gIdCombinazione(),
			);
			
			if (User::$id)
				$defaultValues["autore"] = User::$nomeCliente;
			
			Output::setBodyValue("Esito", $esitoInvio);
			
			FeedbackModel::$sValues = $this->m('FeedbackModel')->getFormValues('insert','sanitizeHtml', 0, $defaultValues);
			
			$this->append($data);
		}
	}
	
	protected function inviaMailConfermaContatto($idContatto)
	{
		$contatto = $this->m('ContattiModel')->selectId((int)$idContatto);
		
		if (!empty($contatto))
		{
			ob_start();
			include (tpf("Elementi/Mail/mail_conferma_contatto.php"));
			$output = ob_get_clean();
			
			$res = MailordiniModel::inviaMail(array(
				"emails"	=>	array($contatto["email"]),
				"oggetto"	=>	v("oggetto_mail_conferma_contatto"),
				"testo"		=>	$output,
				"tipologia"	=>	"CONFERMA_CONTATTO",
				"id_contatto"	=>	$idContatto,
			));
		}
	}
	
	protected function elencoIndirizziEmailACuiInviare($valoriEmail, $pagina = array(), $fonte = "")
	{
		$emails = array();
		
		if (v("invia_mail_contatto_a_piattaforma"))
			$emails = array(Parametri::$mailInvioOrdine);
		
		// Estraggo gli indirizzi dalla pagina
		if ($fonte && $fonte != "NEWSLETTER" && !empty($pagina) && isset($pagina["indirizzi_to_form_contatti"]) && trim($pagina["indirizzi_to_form_contatti"]))
		{
			$indirizziHtml = nl2br(str_replace(" ","", $pagina["indirizzi_to_form_contatti"]));
			$emails = explode("<br />", $indirizziHtml);
			$emails = array_map('trim', $emails);
		}
		
		if (isset($valoriEmail["nazione"]) && $valoriEmail["nazione"])
		{
			$clientiNazioni = $this->m("NazioniModel")->elencoClientiDaCodice($valoriEmail["nazione"]);
			
			if (count($clientiNazioni) > 0)
				$emails = array_merge($emails, $clientiNazioni);
			
			$emails = array_unique($emails);
		}
		
		if ((int)count($emails) === 0)
			$emails = array(Parametri::$mailInvioOrdine);
		
		return $emails;
	}
	
	protected function inviaMailContatto($id, $idContatto, $valoriEmail, $fonte)
	{
		$pagina = $this->m("PagesModel")->selectId((int)$id);
		$contatto = $this->m('ContattiModel')->selectId((int)$idContatto);
		
		if ($fonte == "NEWSLETTER")
			$oggetto = v("oggetto_form_newsletter");
		else
			$oggetto = v("oggetto_form_contatti");
		
		ob_start();
		if ($fonte == "NEWSLETTER")
			include (tpf("Regusers/mail_form_newsletter.php"));
		else
			include (tpf("Regusers/mail_form_contatti.php"));
		$output = ob_get_clean();
		
		$emails = $this->elencoIndirizziEmailACuiInviare($valoriEmail, $pagina, $fonte);
		
		$tipologia = ($fonte == "NEWSLETTER") ? "NEWSLETTER" : "CONTATTO";
		
		return MailordiniModel::inviaMail(array(
			"emails"	=>	$emails,
			"oggetto"	=>	$oggetto,
			"testo"		=>	$output,
			"tipologia"	=>	$tipologia,
			"id_user"	=>	(int)User::$id,
			"id_page"	=>	(int)$id,
			"reply_to"	=>	$valoriEmail["email"],
			"id_contatto"	=>	$idContatto,
		));
	}
	
	protected function setCondizioniContatti()
	{
		
	}
	
	protected function inviaMailFormContatti($id)
	{
		if( !session_id() )
			session_start();
		
		Domain::$currentUrl =  $this->getCurrentUrl();
		
		$isNewsletter = false;
		
		$campiObbligatori = "";
		
		if (isset($_POST['invia']))
		{
			if ($_POST["invia"] == "newsletter")
			{
				$isNewsletter = true;
				$campiForm = v("campo_form_newsletter");
				$campiObbligatori = v("campo_form_newsletter_obbligatori");
			}
			else
			{
				$campiForm = v("campo_form_contatti");
				$campiObbligatori = v("campo_form_contatti_obbligatori");
			}
		}
		else
			$campiForm = implode(",",array_unique(array_merge(explode(",",v("campo_form_newsletter")), explode(",",v("campo_form_contatti")))));
		
		$tipo = $isNewsletter ? "N" : "C";
		
		Form::$fields = array(
			"C"	=>	v("campo_form_contatti"),
			"N"	=>	v("campo_form_newsletter"),
		);
		
		$this->m('ContattiModel')->strongConditions['insert'] = array(
			'checkNotEmpty'	=>	$campiObbligatori ? $campiObbligatori : $campiForm,
			'checkMail'		=>	'email|'.gtext("Si prega di controllare il campo Email").'<div class="evidenzia">class_email</div>',
		);
		
		// aggiungi o sovrascrivi le condizioni
		$this->setCondizioniContatti();
		
		$this->m('ContattiModel')->setFields($campiForm,'strip_tags');
		
		Form::sNotice($tipo, null);
		
		$esitoInvio = "KO";
		
		if (isset($_POST['invia']))
		{
			IpcheckModel::check("POST_CONTATTO");
			
			// Imposto l'output in JSON
			if (isset($_POST['ajaxsubmit']))
			{
				Output::setJson();
				$this->clean();
			}
			
			if (CaptchaModel::getModulo()->check())
			{
				if ($this->m('ContattiModel')->checkConditions('insert'))
				{
					$valoriEmail = $this->m('ContattiModel')->values;
					
					$_SESSION["email_carrello"] = sanitizeAll($valoriEmail["email"]);
					
					$fonte = $isNewsletter ? "NEWSLETTER" : "FORM_CONTATTO";
					
					// Salvo la fonte dal controller
					if ($this->fonteContatto)
						$fonte = $this->fonteContatto;
					
					// Inserisco il contatto
					$idContatto = $this->m('ContattiModel')->insertDaArray($valoriEmail, $fonte, $id);
					
					if ($idContatto)
					{
						if (!$isNewsletter && v("attiva_verifica_contatti") && $idContatto)
							$this->inviaMailConfermaContatto($idContatto);
						
						$res = true;
						
						if ($isNewsletter || v("invia_subito_mail_contatto"))
							$res = $this->inviaMailContatto($id, $idContatto, $valoriEmail, $fonte);
						
						if($res)
						{
							$esitoInvio = "OK";
							
							// Iscrivo a Mailchimp
							if ($isNewsletter && IntegrazioninewsletterModel::integrazioneAttiva())
							{
								IntegrazioninewsletterModel::getModulo()->iscrivi(IntegrazioninewsletterModel::elaboraDati($valoriEmail));
							}
							
							$idGrazie = PagineModel::gTipoPagina("GRAZIE");
							
							if ($this->idRedirectContatti)
								$idGrazie = $this->idRedirectContatti;
							
							$idGrazieNewsletter = 0;
							
							if ($isNewsletter)
								$idGrazieNewsletter = PagineModel::gTipoPagina("GRAZIE_NEWSLETTER");
							
							if ($idGrazieNewsletter)
								$idGrazie = $idGrazieNewsletter;
							
							if (Output::$html)
							{
								if ($idGrazie)
									$this->redirect(getUrlAlias($idGrazie).F::partial());
								else
									$this->redirect("grazie.html".F::partial());
							}
							else
							{
								$pageGrazieDetails = null;
								
								if ($idGrazie)
									$pageGrazieDetails = PagesModel::getPageDetails($idGrazie);
									
								if ($pageGrazieDetails)
									Output::setBodyValue("Notice", "<div class='".v("alert_success_class")."'>".htmlentitydecode(field($pageGrazieDetails, "description"))."</div>");
								else
									Output::setBodyValue("Notice", "<div class='".v("alert_success_class")."'>".gtext("Il vostro messaggio è stato correttamente inviato")."</div>");
							}
						} else {
							$erroreInvio = "<div class='".v("alert_error_class")."'>".gtext("errore nell'invio del messaggio, per favore riprova più tardi")."</div>";
							
							Form::sNotice($tipo, $erroreInvio);
							Output::setBodyValue("Notice", $erroreInvio);
						}
					}
					else
					{
						$erroriValidazione = "<div class='".v("alert_error_class")."'>".gtext(v("testo_errori_form"))."</div>".$this->m('ContattiModel')->notice;
						
						Form::sNotice($tipo, $erroriValidazione);
						Output::setBodyValue("Notice", $erroriValidazione);
					}
				}
				else
				{
					$erroriValidazione = "<div class='".v("alert_error_class")."'>".gtext(v("testo_errori_form"))."</div>".$this->m('ContattiModel')->notice;
					
					Form::sNotice($tipo, $erroriValidazione);
					Output::setBodyValue("Notice", $erroriValidazione);
				}
			}
			else
			{
				ob_start();
				include(tpf(CaptchaModel::getModulo()->getErrorIncludeFile()));
				$erroreInvio = ob_get_clean();
				
// 				$erroreInvio = "<div class='".v("alert_error_class")."'>".gtext("errore nell'invio del messaggio, per favore riprova più tardi")."</div>";
				
				Form::sNotice($tipo, $erroreInvio);
				Output::setBodyValue("Notice", $erroreInvio);
			}
		}
		
		Output::setBodyValue("Esito", $esitoInvio);
		
// 		Form::$values = $this->m('ContattiModel')->getFormValues('insert','sanitizeHtml');
		
		Form::sValues($tipo, $this->m('ContattiModel')->getFormValues('insert','sanitizeHtml',null,Form::$defaultValues));
	}
	
	protected function getCurrentUrl($completeUrl = true)
	{
		return $this->currPage;
	}
	
	protected function cload($viewFile,$option = 'none')
	{
		if (v("attiva_gestione_fasce_frontend") && User::$adminLogged && isset($_GET[v("token_edit_frontend")]) && !User::$isPhone && isset($_GET["em_edit_frontend"]))
		{
			if (!self::$adminPanelCaricato)
			{
				if( !session_id() )
					session_start();
				
				$_SESSION["modalita_edit_fronted"] = 1;
				
				$currentUrl = $data["currentUrl"] = $this->getCurrentUrl();
				$this->clean();
				
				ob_start();
				$tipoOutput = "mail_al_cliente";
				include tpf("/admin_panel.php");
				$output = ob_get_clean();
				
				self::$adminPanelCaricato = true;
				
				echo $output;
			}
		}
		else
			$this->load($viewFile, $option);
	}
	
	protected function campoObbligatorio($campo, $queryType = "insert")
	{
		return GenericModel::camboObbligatorio($campo, $this->controller, $queryType);
	}
	
	protected function getAppLogin()
	{
		if (!VariabiliModel::confermaUtenteRichiesta())
		{
			if (isset($_SESSION["test_login_effettuato"]))
				unset($_SESSION["test_login_effettuato"]);
			
			if (isset($_SESSION["ok_csrf"]))
				unset($_SESSION["ok_csrf"]);
			
			$data["csrf_code"] = $_SESSION["csrf_code"] = md5(randString(15).uniqid(mt_rand(),true));
			
			$data["elencoAppLogin"] = IntegrazioniloginModel::g()->clear()->where(array(
				"attivo"	=>	1,
			))->orderBy("id_order")->send(false);
			
			$this->append($data);
		}
	}
	
	protected function checkAggiuntaAlCarrello($id_page, $defaultErrorJson)
	{
		// Se non è un prodotto
		if (!$id_page || !$this->m("PagesModel")->isProdotto((int)$id_page))
		{
			$defaultErrorJson["errore"] = gtext("Il seguente prodotto non può essere aggiunto al carrello.");
			
			echo json_encode($defaultErrorJson);
			
			die();
		}
		
		if (!v("ecommerce_online"))
		{
			echo json_encode($defaultErrorJson);
			
			die();
		}
	}
	
	protected function setTabelleCacheAggiuntive($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		if (($controller == "home" && ($action == "index" || $action == "xmlprodotti")) || ($controller == "contenuti" && $action == "index"))
			Cache_Db::addTablesToCache(array("combinazioni","scaglioni"));
	}
	
	// Aggiunge il nome del negozio davanti o in coda
	protected function aggiungiNomeNegozioATitle($title)
	{
		return Parametri::$nomeNegozio . ' - '.$title;
	}
	
	protected function creaArrayLingueNazioni($url)
	{
		$arrayLingue = [];
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$arrayLingue[$l] = $l.$url;
		}
		
		return $arrayLingue;
	}
	
	protected function redirectTwoFactorSendMail()
	{
		$this->redirect($this->applicationUrl.$this->controller."/twofactorsendmail/".RegusersModel::$redirectQueryString,0);
	}
	
	protected function hookAfterLogin()
	{
		if (v("hook_after_login_utente"))
			callFunction(v("hook_after_login_utente"), (int)$this->s['registered']->status['id_user'], v("hook_after_login_utente"));
	}
	
	protected function checkCategory($id)
	{
		$clean['id'] = (int)$id;
		
		if (!$this->m('CategoriesModel')->check($clean["id"]))
		{
			if ($this->islogged)
			{
				$this->redirect("contenuti/nonpermesso");
			}
			else
			{
				$this->redirect("regusers/login");
			}
		}
	}
}
