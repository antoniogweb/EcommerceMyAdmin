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

require(LIBRARY."/Application/Models/ContattiModel.php");
require_once(LIBRARY."/Application/Modules/F.php");

class BaseBaseController extends Controller
{
	protected static $adminPanelCaricato = false;
	protected $islogged = false;
	protected $iduser = 0;
	protected $dettagliUtente = null;
	
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
	public $prodottiInPromozione = array();
	public $sectionsId = array();
	
	public $pages = array(); // Array di pagina
	public $p = array(); // singola pagina
	
	public $defaultRegistrazione = array();
	
	public static $isPromo = false;
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		if (!defined("FRONT"))
			define('FRONT', ROOT);
		
		Domain::setPath();
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		Domain::$adminName = $this->baseUrlSrc."/admin";
		Domain::$publicUrl = $this->baseUrlSrc;
		
		$this->model("TraduzioniModel");
		
		$this->m["TraduzioniModel"]->ottieniTraduzioni();
		
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
		
		$this->model("CategoriesModel");
		$this->model("MenuModel");
		$this->model("MenusecModel");
		$this->model("PagesModel");
		$this->model("ImmaginiModel");
		$this->model("CartModel");
		$this->model("WishlistModel");
		$this->model("RigheModel");
		$this->model("OrdiniModel");
		$this->model("RegusersModel");
		$this->model("PromozioniModel");
		
// 		$this->model("NewsModel");
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
		
		if (v("abilita_feedback"))
			$this->model("FeedbackModel");
		
		RegioniModel::$nAlias = gtext(v("label_nazione_url"));
		RegioniModel::$rAlias = gtext(v("label_regione_url"));
		
		// Predisponi i filtri in coda nell'URL
		$this->predisponiAltriFiltri();
		
		$this->model("CaratteristichevaloriModel");
		
// 		$this->m["ImpostazioniModel"]->getImpostazioni();
		
		ImpostazioniModel::init();
		
		$this->session('registered');
		$this->s['registered']->checkStatus();
		
		$this->session('admin');
		$this->s['admin']->checkStatus();
		
		//controllo login
		$data['username'] = null;
		$data['islogged'] = false;
		$data['dettagliUtente'] = null;
		$data['iduser'] = 0;
		$data['nomeCliente'] = "";
		
		$data["adminUser"] = false;
		
		// Controlla se arriva dall'app
		if (isset($_GET["asJson"]))
		{
			Output::$html = false;
			Output::$json = true;
		}
		
		if ($this->s['registered']->status['status'] === 'logged')
		{
			$data['username'] = $this->s['registered']->status['user'];

			$res = $this->m['RegusersModel']->clear()->where(array("id_user"=>(int)$this->s['registered']->status['id_user']))->send();
			$data['dettagliUtente'] = $this->dettagliUtente = User::$dettagli = $res[0]['regusers'];

			$this->iduser = User::$id = (int)$this->s['registered']->status['id_user'];
			$data['iduser'] = $this->iduser;

			$data['islogged'] = true;
			$this->islogged = User::$logged = $data['islogged'];
			
			User::$groups = $this->s['registered']->status['groups'];
			
			$data['nomeCliente'] = User::$nomeCliente = (strcmp($data['dettagliUtente']["tipo_cliente"],"privato") === 0 || strcmp($data['dettagliUtente']["tipo_cliente"],"libero_professionista") === 0) ?  $data['dettagliUtente']["nome"] : $data['dettagliUtente']["ragione_sociale"];
			
			// Estraggo lo sconto dell'utente
			User::$classeSconto = $this->m["ClassiscontoModel"]->selectId(User::$dettagli["id_classe"]);
			
			if (!empty(User::$classeSconto) && User::$classeSconto["sconto"] > 0 && User::$classeSconto["sconto"] < 100)
			{
				User::$sconto = User::$classeSconto["sconto"];
				
				User::$categorieInClasseSconto = $this->m["CategoriesModel"]->getListaCategorieInClasseSconto();
			}
			
			User::$ruid = $this->s['registered']->getUid();
			
			// Imposto lo stato loggato su Output
			Output::setHeaderValue("Status","logged");
			Output::setHeaderValue("UserId",User::$ruid);
			Output::setHeaderValue("Nome",$data['nomeCliente']);
			Output::setHeaderValue("Email",User::$dettagli["username"]);
// 			print_r(User::$categorieInClasseSconto);
		}
		
		if ($this->s['admin']->status['status'] === 'logged')
		{
			$data["adminUser"] = User::$adminLogged = true;
		}
		
		$data["isProdotto"] = false;
		$data["title"] =  gtext(ImpostazioniModel::$valori["title_home_page"]);
		
		$this->initCookieEcommerce();
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$data["carrello"] = $this->m["CartModel"]->getProdotti();
		
		// Recuperta dati da cliente loggato
		$this->m["CartModel"]->collegaDatiCliente($data["carrello"]);
		
		// Correggi decimali imponibili sulla base dell'IVA estera
		$this->m["CartModel"]->correggiPrezzi();
		
		$data["prodInCart"] = $this->m["CartModel"]->numberOfItems();
		$data["prodInWishlist"] = $this->m["WishlistModel"]->numberOfItems();
		
		Domain::$name = $this->baseUrl;
		
		if (in_array($_SERVER['REQUEST_URI'],array("/home","/home/index","/home/index/")))
		{
			$this->redirect("");
		}
		
		//estraggo i prodotti in evidenza
// 		$data["inEvidenza"] = getRandom($this->m["PagesModel"]->clear()->where(array("attivo"=>"Y","in_evidenza"=>"Y"))->limit(20)->orderBy("pages.id_order")->send());
		
		$clean["idShop"] = $data["idShop"] = $this->idShop = CategoriesModel::$idShop = $this->m["CategoriesModel"]->getShopCategoryId();
		
// 		$childrenProdotti = $this->m["CategoriesModel"]->children($clean["idShop"], true);
		
		$data["prodottiInEvidenza"] = $this->prodottiInEvidenza = getRandom($this->m["PagesModel"]->clear()->select("*")
			->addJoinTraduzionePagina()
			->where(array(
				"in_evidenza"=>"Y",
			))
			->addWhereCategoria($clean["idShop"])
			->addWhereAttivo()
			->orderBy("pages.id_order desc")->send(), v("numero_in_evidenza"));
		
		if (v("mostra_avvisi"))
			$data["avvisi"] = $this->m["PagesModel"]->where(array(
				"categories.section"	=>	"avvisi",
				"attivo"=>"Y",
			))->send();
		
		// Modali
		if (v("attiva_modali") && $controller == "home" && $action == "index")
		{
			$data["modali_frontend"] = $this->m["PagesModel"]->where(array(
				"categories.section"	=>	"modali",
				"attivo"=>"Y",
			))->orderBy("pages.id_order desc")->limit(1)->send();
			
			// Preparo i cookie
			foreach ($data["modali_frontend"] as $mod)
			{
				$idModale = $mod["pages"]["id_page"];
				
				if ($mod["pages"]["giorni_durata_modale"] >= 0)
				{
					$tempoModale = 0;
					
					if ($mod["pages"]["giorni_durata_modale"] > 0)
						$tempoModale = time() + $mod["pages"]["giorni_durata_modale"] * 3600 * 24;
						
					if (!isset($_COOKIE["modale_".$idModale]))
						setcookie("modale_".$idModale,$idModale,$tempoModale,"/");
				}
			}
		}
		
		$data["isHome"] = false;
		$data['headerClass'] = "";
		$data['customHeaderClass'] = "";
		$data['inlineCssFile'] = "";
		
		// In evidenza per APP
		if ($controller == "home")
		{
			$data["isHome"] = true;
			
			$pagineConDecode = array();
			
			if (Output::$json)
			{
				foreach ($data["prodottiInEvidenza"] as $page)
				{
					$temp = $page;
					$page["quantity"] = 1;
					$page["pages"]["url-alias"] = getUrlAlias($page["pages"]["id_page"]);
					$page["pages"]["price"] = number_format(calcolaPrezzoIvato($page["pages"]["id_page"], $page["pages"]["price"]),2,",",".");
					$page["pages"]["prezzo_promozione"] = number_format($page["pages"]["prezzo_promozione"],2,",",".");
					$page["pages"]["prezzo_scontato"] = prezzoPromozione($temp);
					$page["pages"] = htmlentitydecodeDeep($page["pages"]);
					
					$pagineConDecode[] = $page;
				}
			}
			
			Output::setBodyValue("Evidenza", $pagineConDecode);
		}
		
		$data["categoriaShop"] = $this->m["CategoriesModel"]->selectId($clean["idShop"]);
		
		$data["idBlog"] = 0;
		
		if (v("blog_attivo"))
		{
			$idBlog = $this->sectionsId["blog"] = $data["idBlog"] = (int)CategoriesModel::getIdCategoriaDaSezione("blog");

			$data["ultimiArticoli"] = $this->getNewsInEvidenza = $this->m['PagesModel']->clear()->select("*")
				->addJoinTraduzionePagina()
				->where(array(
					"attivo" => "Y",
				))
				->addWhereCategoria((int)$idBlog)
				->orderBy("data_news desc")->limit(v("numero_news_in_evidenza"))->send();
		}
		
		if (v("team_attivo"))
		{
			$idTeam = (int)$this->m["CategoriesModel"]->clear()->where(array(
				"section"	=>	"team",
			))->field("id_c");
			
			$data["team"] = $this->team = $this->m['PagesModel']->clear()->select("*")
				->addJoinTraduzionePagina()
				->where(array(
					"attivo"	=>	"Y",
					"id_c"		=>	(int)$idTeam,
				))->orderBy("pages.id_order")->send();
		}
		
		if (v("mostra_testimonial"))
		{
			$idTest = (int)CategoriesModel::getIdCategoriaDaSezione("testimonial");
			
			$data["testimonial"] = $this->testimonial = $this->m['PagesModel']->clear()->select("*")
				->addJoinTraduzionePagina()
				->where(array(
					"attivo"	=>	"Y",
					"id_c"		=>	$idTest,
				))->orderBy("pages.id_order")->send();
		}
		
		if (v("mostra_faq"))
		{
			$idFaq = $this->sectionsId["faq"] = $data["idFaq"] = (int)CategoriesModel::getIdCategoriaDaSezione("faq");
			
			$data["faq"] = $this->faq = $this->m['PagesModel']->clear()->select("*")
				->addJoinTraduzionePagina()
				->where(array(
					"attivo"	=>	"Y",
					"in_evidenza"=>"Y",
					"id_c"		=>	$idFaq,
				))->orderBy("pages.id_order")->send();
		}
		
		$data["categorieBlog"] = $this->m["CategoriesModel"]->children(87, false, false);
		
		if (v("estrai_in_promozione_home"))
		{
			//estraggo i prodotti in promozione
			$nowDate = date("Y-m-d");
			$pWhere = array(
				"gte"	=>	array("n!datediff('$nowDate',pages.dal)" => 0),
				" gte"	=>	array("n!datediff(pages.al,'$nowDate')" => 0),
				"in_promozione" => "Y",
// 				"acquistabile"	=>	"Y",
// 				"attivo" => "Y",
			);
			
			$prodottiInPromo = $this->m["PagesModel"]->clear()->addWhereAttivo()->addJoinTraduzionePagina()->where($pWhere)->orderBy("pages.id_order")->send();
			
			$data["inPromozione"] = $this->prodottiInPromozione = getRandom($prodottiInPromo);
			
			$data["prodottiInPromozione"] = $prodottiInPromo;
		}
		
		if ($controller != "contenuti" || $action != "category")
			$this->estraiDatiFiltri();
		
		if (v("attiva_in_evidenza_nazioni"))
			NazioniModel::$elencoNazioniInEvidenza = $this->m["NazioniModel"]->clear()->where(array(
				"in_evidenza"	=>	"Y"
			))->toList("iso_country_code")->send();
		
		$data["meta_description"] = F::meta(gtext(htmlentitydecode(ImpostazioniModel::$valori["meta_description"])));
		$data["keywords"] = F::meta(gtext(htmlentitydecode(ImpostazioniModel::$valori["keywords"])));
		
		Lang::$current = Params::$lang;
		
		$data["alberoCategorieProdotti"] = $this->m["CategoriesModel"]->recursiveTree($clean["idShop"],2);
		
		$data["alberoCategorieProdottiConShop"] = array($data["categoriaShop"]) + $data["alberoCategorieProdotti"];
		
		$data["elencoCategorieFull"] = $this->elencoCategorieFull = CategoriesModel::$elencoCategorieFull = $this->m['CategoriesModel']->clear()->select("categories.*,contenuti_tradotti_categoria.*")->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")->where(array("id_p"=>$clean["idShop"]))->orderBy("lft")->send();
		
		if (Output::$html)
		{
			$data["tipiPagina"] = PagesModel::$tipiPaginaId = $this->m["PagesModel"]->clear()->where(array(
				"ne"		=>	array("tipo_pagina" => ""),
				"attivo"	=>	"Y",
			))->toList("tipo_pagina", "id_page")->send();
			
			$data["tipiClienti"] = TipiclientiModel::getArrayTipi();
			
			$data["selectNazioni"] = array(""	=>	gtext("Seleziona",true)) + $this->m["NazioniModel"]->selectNazioniAttive();
			$data["selectNazioniSpedizione"] = array(""	=>	gtext("Seleziona",true)) + $this->m["NazioniModel"]->selectNazioniAttiveSpedizione();
			
			$data["selectRuoli"] = $this->m["RuoliModel"]->selectTipi(true);
			
			if (v("attiva_tipi_azienda"))
				$data["selectTipiAziende"] = $this->m["TipiaziendaModel"]->selectTipi(true);
		}
		
		$data["isPromo"] = self::$isPromo;
		
		$data["arrayLingue"] = array();
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l;
		}
		
		$data["arrayLingueCompleto"] = LingueModel::getValoriAttivi();
		
		$res = $resMobile = $this->m["MenuModel"]->getTreeWithDepth(v("profondita_menu_desktop"), null, Params::$lang);
		$data["menu"] = $this->m["MenuModel"]->getMenu($res,false, Params::$lang);
		
		if (v("profondita_menu_mobile") != v("profondita_menu_desktop"))
			$resMobile = $this->m["MenuModel"]->getTreeWithDepth(v("profondita_menu_mobile"), null, Params::$lang);
		
		if (v("abilita_menu_semplice"))
			$data["menuSemplice"] = $this->m["MenuModel"]->getMenu($resMobile,false, Params::$lang, true);
		
		$data["menuMobile"] = $this->m["MenuModel"]->getMenu($resMobile,false, Params::$lang, false, true);
		
		$data["langDb"] = $this->langDb = Lang::$langDb = null;
		
		$data["pagesCss"] = $data["paginaGenerica"] = "";
		
		$this->append($data);
		
		Params::$rewriteStatusVariables = false;
		
		VariabiliModel::inizializza();
		VariabiliModel::checkCookieTerzeParti();
		
		if (getSubTotalN() > 9999999)
		{
			$this->m["CartModel"]->emptyCart();
		}
		
		$this->m["PagesModel"]->aggiornaStatoProdottiInPromozione();
	}
	
	protected function initCookieEcommerce()
	{
		// Recupero il cookie di contatto
		$this->m["ContattiModel"]->getCookie();
		
		if (!v("ecommerce_attivo"))
			return;
		
		//set the cookie for the cart
		if ((isset($_COOKIE["cart_uid"]) && $_COOKIE["cart_uid"]) || (isset($_GET["cart_uid"]) && $_GET["cart_uid"] && (int)strlen($_GET["cart_uid"]) === 32))
		{
			if (isset($_GET["cart_uid"]))
				User::$cart_uid = sanitizeAll($_GET["cart_uid"]);
			else
				User::$cart_uid = sanitizeAll($_COOKIE["cart_uid"]);
			
			if ($this->m['OrdiniModel']->cartUidAlreadyPresent(User::$cart_uid))
			{
				User::$cart_uid = md5(randString(10).microtime().uniqid(mt_rand(),true));
				$time = time() + 3600*24*365*10;
				setcookie("cart_uid",User::$cart_uid,$time,"/");
			
// 				setcookie("cart_uid", "", time()-3600,"/");
// 				$this->redirect("");
			}
		}
		else
		{
			User::$cart_uid = md5(randString(10).microtime().uniqid(mt_rand(),true));
			$time = time() + 3600*24*365*10;
			setcookie("cart_uid",User::$cart_uid,$time,"/");
		}
		
		//set the cookie for the wishlist
		if (isset($_COOKIE["wishlist_uid"]))
		{
			User::$wishlist_uid = sanitizeAll($_COOKIE["wishlist_uid"]);
		}
		else
		{
			User::$wishlist_uid = md5(randString(10).microtime().uniqid(mt_rand(),true));
			$time = time() + 3600*24*365*10;
			setcookie("wishlist_uid",User::$wishlist_uid,$time,"/");
		}
		
		if (Output::$json)
		{
			// Imposto il cart uid nell'header dell'output json
			Output::setHeaderValue("CartUid",User::$cart_uid);
			
			Output::setHeaderValue("CartProductsNumber",$this->m["CartModel"]->numberOfItems());
		}
		
		//setto il coupon se presente
		User::$coupon = null;
		
		if (isset($_POST["invia_coupon"]))
		{
			User::$coupon = $this->request->post("il_coupon","","sanitizeAll");
			
			$time = time() + 3600*24*365*10;
			setcookie("coupon",User::$coupon,$time,"/");
		}
		else
		{
			if (isset($_COOKIE["coupon"]))
				User::$coupon = sanitizeAll($_COOKIE["coupon"]);
		}
		
		if (User::$coupon)
		{
			if ($this->m["PromozioniModel"]->isActiveCoupon(User::$coupon))
			{
				// Estraggo tutti i prodotti della promozione
				User::$prodottiInCoupon = $this->m["PromozioniModel"]->elencoProdottiPromozione(User::$coupon);
			}
			else
			{
				//setto il coupon se presente
				User::$coupon = null;
				
				if (isset($_COOKIE["coupon"]))
					setcookie("coupon", "", time()-3600,"/");
			}
		}
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
		
		if (v("mostra_fasce_prezzo"))
		{
			$this->model("FasceprezzoModel");
			
			$aliasFasciaPrezzo = gtext(v("alias_fascia_prezzo"), true, "none", null, 0);
			AltriFiltri::$altriFiltri[] = $aliasFasciaPrezzo;
			AltriFiltri::$altriFiltriTipi["fascia-prezzo"] = $aliasFasciaPrezzo;
		}
	}
	
	protected function estraiDatiFiltri()
	{
		$whereIn = "";
		
		if (v("attiva_filtri_successivi"))
		{
			if (count(CategoriesModel::$arrayIdsPagineFiltrate) > 0)
				$whereIn = "pages.id_page in (".implode(",",CategoriesModel::$arrayIdsPagineFiltrate).")";
			else
				$whereIn = "1 =! 1";
		}
		
		if (v("usa_marchi"))
		{
			$data["elencoMarchi"] = $this->m["MarchiModel"]->clear()->orderBy("titolo")->toList("id_marchio", "titolo")->send();
			
			$data["elencoMarchiFull"] = $this->elencoMarchiFull = $this->m["MarchiModel"]->clear()->addJoinTraduzione()->orderBy("marchi.titolo")->send();
			$data["elencoMarchiNuoviFull"] = $this->elencoMarchiNuoviFull = $this->m["MarchiModel"]->clear()->where(array(
				"nuovo"	=>	"Y",
			))->addJoinTraduzione()->orderBy("marchi.titolo")->send();
			
			$data["elencoMarchiFullFiltri"] = $this->m["MarchiModel"]->clear()->select("*,count(marchi.id_marchio) as numero_prodotti")->inner(array("pagine"))->groupBy("marchi.id_marchio")->addWhereAttivo()->send();
		}
		
		if (v("usa_tag"))
		{
			$data["elencoTagFull"] = $this->elencoTagFull = $this->m["TagModel"]->clear()->addJoinTraduzione()->where(array(
				"attivo"	=>	"Y",
			))->orderBy("tag.titolo")->send();
			
			$data["elencoTagFullFiltri"] = $this->m["TagModel"]->select("*,count(tag.id_tag) as numero_prodotti")
				->inner(array("pagine"))
				->inner("pages")->on("pages.id_page = pages_tag.id_page")
				->addWhereAttivo()->groupBy("tag.id_tag")->send();
		}
		
		$data["filtriCaratteristiche"] = array();
		
		if (v("attiva_filtri_caratteristiche"))
		{
			$data["filtriCaratteristiche"] = $this->m["PagescarvalModel"]->clear()->select("count(caratteristiche_valori.id_cv) as numero_prodotti,caratteristiche.titolo,caratteristiche.alias,caratteristiche.id_car,caratteristiche_valori.titolo,caratteristiche_valori.alias,caratteristiche_valori.id_cv,caratteristiche_tradotte.titolo,caratteristiche_tradotte.alias,caratteristiche_valori_tradotte.titolo,caratteristiche_valori_tradotte.alias")
				->inner(array("caratteristica_valore"))
				->inner("caratteristiche")->on("caratteristiche_valori.id_car = caratteristiche.id_car and filtro = 'Y'")
				->left("contenuti_tradotti as caratteristiche_tradotte")->on("caratteristiche_tradotte.id_car = caratteristiche.id_car and caratteristiche_tradotte.lingua = '".sanitizeDb(Params::$lang)."'")
				->left("contenuti_tradotti as caratteristiche_valori_tradotte")->on("caratteristiche_valori_tradotte.id_cv = caratteristiche_valori.id_cv and caratteristiche_valori_tradotte.lingua = '".sanitizeDb(Params::$lang)."'")
				->inner("pages")->on("pages.id_page = pages_caratteristiche_valori.id_page")
				->addWhereAttivo()
				->orderBy("caratteristiche.id_order,caratteristiche_valori.id_order")
				->groupBy("caratteristiche_valori.id_cv")
				->send();
		}
		
		$data["filtriNazioni"] = $data["filtriRegioni"] = array();
		
		if (v("attiva_localizzazione_prodotto"))
		{
			$data["filtriNazioni"] = $this->m["PagesregioniModel"]->filtriNazioni();
			
			$data["filtriRegioni"] = $this->m["PagesregioniModel"]->filtriRegioni();
		}
		
		$this->append($data);
	}
	
	protected function formRegistrazione()
	{
		if( !session_id() )
			session_start();
		
		// Setta password
		$this->m["RegusersModel"]->settaPassword();
		
		$data['notice'] = null;
		$data['isRegistrazione'] = true;
		
		$tipo_cliente = $this->request->post("tipo_cliente","","sanitizeAll");
		$pec = $this->request->post("pec","","sanitizeAll");
		$codiceDestinatario = $this->request->post("codice_destinatario","","sanitizeAll");
		
// 		$baseFields = v("insert_account_fields");
		
		$baseFields = OpzioniModel::stringaValori("CAMPI_SALVATAGGIO_UTENTE");
		
		$baseFields .= ",accetto";
		
		// BASE: 'nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,username,accetto,tipo_cliente,nazione,pec,codice_destinatario,dprovincia,telefono_2';
		
		$fields = $baseFields.',password:'.PASSWORD_HASH;
		
		if (v("attiva_ruoli"))
			$fields .= ",id_ruolo";
		
		if (v("attiva_tipi_azienda"))
			$fields .= ",id_tipo_azienda";
		
		$this->m['RegusersModel']->setFields($fields,'strip_tags');
		$datiCliente = $this->m['RegusersModel']->values;
		$this->m['RegusersModel']->sanitize("sanitizeAll");
		
		$this->m['RegusersModel']->setConditions($tipo_cliente, "insert", $pec, $codiceDestinatario);
		
		$this->m['RegusersModel']->fields = "$baseFields,newsletter,password";
		
		if (v("account_attiva_conferma_password"))
			$this->m['RegusersModel']->fields .= ",confirmation";
		
		if (v("account_attiva_conferma_username"))
			$this->m['RegusersModel']->fields .= ",conferma_username";
		
		if (v("attiva_ruoli"))
			$this->m['RegusersModel']->fields .= ",id_ruolo";
		
		if (v("attiva_tipi_azienda"))
			$this->m['RegusersModel']->fields .= ",id_tipo_azienda";
		
		if (isset($_POST['updateAction']))
		{
			if (CaptchaModel::getModulo()->checkRegistrazione())
			{
				if ($this->m['RegusersModel']->checkConditions('insert'))
				{
					$tokenConferma = $this->m['RegusersModel']->values['confirmation_token'] = md5(randString(20).microtime().uniqid(mt_rand(),true));
					
					if ($this->m['RegusersModel']->insert())
					{
						$lId = $this->m['RegusersModel']->lastId();
						
						$password = $this->request->post("password","","none");
						$clean["username"] = $this->request->post("username","","sanitizeAll");
						
						$_SESSION["email_carrello"] = $clean["username"];
						
						//loggo l'utente
						if (!v("conferma_registrazione") && !v("gruppi_inseriti_da_approvare_alla_registrazione"))
							$this->s['registered']->login($clean["username"],$password);
						
						if (Output::$json)
							$this->setUserHead();
						
						// Iscrizione alla newsletter
						if (isset($_POST["newsletter"]) && ImpostazioniModel::$valori["mailchimp_api_key"] && ImpostazioniModel::$valori["mailchimp_list_id"])
						{
							$this->m['RegusersModel']->iscriviANewsletter($lId);
							
							// Inserisco il contatto
							$this->m['ContattiModel']->insertDaArray($datiCliente, "NEWSLETTER_DA_REGISTRAZIONE");
						}
						
						$_SESSION['result'] = 'utente_creato';
						
						$res = MailordiniModel::inviaCredenziali($lId, array(
							"username"	=>	$clean["username"],
							"password"	=>	$password,
							"tokenConferma"	=>	$tokenConferma,
						));
						
						if ($res)
						{
							ob_start();
							include tpf("Regusers/mail_al_negozio_registr_nuovo_cliente.php");
							$output = ob_get_clean();
							
							$res = MailordiniModel::inviaMail(array(
								"emails"	=>	array(Parametri::$mailInvioOrdine),
								"oggetto"	=>	"invio credenziali nuovo utente",
								"testo"		=>	$output,
								"tipologia"	=>	"ISCRIZIONE AL NEGOZIO",
								"id_user"	=>	(int)$lId,
								"id_page"	=>	0,
							));
						}
						
						if (Output::$html)
						{
							$urlRedirect = RegusersModel::getUrlRedirect();
							
							if ($urlRedirect && !v("conferma_registrazione"))
								header('Location: '.$urlRedirect);
							else
								$this->redirect("avvisi");
						}
					}
					else
					{
						$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m['RegusersModel']->notice;
					}
				}
				else
				{
					$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m['RegusersModel']->notice;
				}
			}
		}
		
		$data['values'] = $this->m['RegusersModel']->getFormValues('insert','sanitizeHtml',null,$this->defaultRegistrazione);
		
		$data['province'] = $this->m['ProvinceModel']->selectTendina();
		
		if (strcmp($data['values']["tipo_cliente"],"") === 0)
		{
			$data['values']["tipo_cliente"] = "privato";
		}
		
		$this->append($data);
	}
	
	protected function inserisciFeedback($id)
	{
		if (!v("abilita_feedback"))
			return;
		
		if( !session_id() )
			session_start();
		
		FeedbackModel::gIdProdotto();
		
		if ($this->m['PagesModel']->checkTipoPagina($id, "FORM_FEEDBACK"))
		{
			if (!isset(FeedbackModel::$idProdotto) || !$this->m['PagesModel']->isProdotto((int)FeedbackModel::$idProdotto))
				$this->redirect("");
			
			$par = $this->m["PagesModel"]->parents((int)FeedbackModel::$idProdotto,false,false,Params::$lang);
			
			//tolgo la root
			array_shift($par);
			
// 			print_r($par);
			
			$data["breadcrumb"] = $this->breadcrumbHtml = $this->breadcrumb("page", true, "&raquo;", $par).v("divisone_breadcrum").$this->breadcrumb("page");
			
			Domain::$currentUrl =  $this->getCurrentUrl();
			
			if (User::$id)
				$_POST["email"] = User::$dettagli["username"];
			
			$campiForm = "autore,testo,email,accetto,accetto_feedback,voto";
			
			$this->m['FeedbackModel']->strongConditions['insert'] = array(
				'checkNotEmpty'	=>	$campiForm,
				'checkMail'		=>	'email|'.gtext("Si prega di controllare il campo Email").'<div class="evidenzia">class_email</div>',
				'checkIsStrings|1,2,3,4,5'		=>	'voto|'.gtext("Si prega di scegliere un punteggio").'<div class="evidenzia">class_voto</div>',
			);
			
			$this->m['FeedbackModel']->setFields($campiForm,'strip_tags');
			
			if (isset($_POST['inviaFeedback']))
			{
				if (CaptchaModel::getModulo()->check())
				{
					if ($this->m['FeedbackModel']->checkConditions('insert'))
					{
						$this->m['FeedbackModel']->setUserData();
						
						$valoriEmail = $this->m['FeedbackModel']->values;
						
						$this->m['FeedbackModel']->sanitize("sanitizeAll");
						
						if ($this->m['FeedbackModel']->insert())
						{
							$lId = $this->m['FeedbackModel']->lastId();
							
							$_SESSION["email_carrello"] = sanitizeAll($valoriEmail["email"]);
							
							$fonte = "FORM_FEEDBACK";
							
							// Inserisco il contatto
	// 						$idContatto = $this->m['ContattiModel']->insertDaArray($valoriEmail, $fonte);
							
							$pagina = $this->m["PagesModel"]->selectId((int)FeedbackModel::$idProdotto);
							
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
								$this->redirect(getUrlAlias($idGrazieFeedback));
							else if ($idGrazie)
								$this->redirect(getUrlAlias($idGrazie));
							else
								$this->redirect("grazie.html");
						}
						else
						{
							$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m['FeedbackModel']->notice;
						}
					}
					else
					{
						FeedbackModel::$sNotice = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m['FeedbackModel']->notice;
					}
				}
			}
			
			$defaultValues = array(
				"voto"	=>	0,
			);
			
			if (User::$id)
				$defaultValues["autore"] = User::$nomeCliente;
			
			FeedbackModel::$sValues = $this->m['FeedbackModel']->getFormValues('insert','sanitizeHtml', 0, $defaultValues);
			
			$this->append($data);
		}
	}
	
	protected function inviaMailConfermaContatto($idContatto)
	{
		$contatto = $this->m['ContattiModel']->selectId((int)$idContatto);
		
		if (!empty($contatto))
		{
			ob_start();
			include (tpf("Elementi/Mail/mail_conferma_contatto.php"));
			$output = ob_get_clean();
			
			$res = MailordiniModel::inviaMail(array(
				"emails"	=>	array($contatto["email"]),
				"oggetto"	=>	"conferma la tua mail",
				"testo"		=>	$output,
				"tipologia"	=>	"CONFERMA_CONTATTO",
				"id_contatto"	=>	$idContatto,
			));
		}
	}
	
	protected function inviaMailContatto($id, $idContatto, $valoriEmail, $fonte)
	{
		$pagina = $this->m["PagesModel"]->selectId((int)$id);
		
		if ($fonte == "NEWSLETTER")
			$oggetto = "form iscrizione a newsletter";
		else
			$oggetto = "form richiesta informazioni";
		
		ob_start();
		if ($fonte == "NEWSLETTER")
			include (tpf("Regusers/mail_form_newsletter.php"));
		else
			include (tpf("Regusers/mail_form_contatti.php"));
		$output = ob_get_clean();
		
		$emails = array(Parametri::$mailInvioOrdine);
		
		if (isset($valoriEmail["nazione"]) && $valoriEmail["nazione"])
		{
			$clientiNazioni = $this->m["NazioniModel"]->elencoClientiDaCodice($valoriEmail["nazione"]);
			
			if (count($clientiNazioni) > 0)
				$emails = array_merge($emails, $clientiNazioni);
			
			$emails = array_unique($emails);
		}
		
		return MailordiniModel::inviaMail(array(
			"emails"	=>	$emails,
			"oggetto"	=>	$oggetto,
			"testo"		=>	$output,
			"tipologia"	=>	"CONTATTO_NEWSLETT",
			"id_user"	=>	(int)User::$id,
			"id_page"	=>	(int)$id,
			"reply_to"	=>	$valoriEmail["email"],
			"id_contatto"	=>	$idContatto,
		));
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
		
		$this->m['ContattiModel']->strongConditions['insert'] = array(
			'checkNotEmpty'	=>	$campiObbligatori ? $campiObbligatori : $campiForm,
			'checkMail'		=>	'email|'.gtext("Si prega di controllare il campo Email").'<div class="evidenzia">class_email</div>',
		);
		
		$this->m['ContattiModel']->setFields($campiForm,'strip_tags');
		
		Form::sNotice($tipo, null);
		
		if (isset($_POST['invia']))
		{
			if (CaptchaModel::getModulo()->check())
			{
				if ($this->m['ContattiModel']->checkConditions('insert'))
				{
					$valoriEmail = $this->m['ContattiModel']->values;
					
					$_SESSION["email_carrello"] = sanitizeAll($valoriEmail["email"]);
					
					$fonte = $isNewsletter ? "NEWSLETTER" : "FORM_CONTATTO";
					
					// Inserisco il contatto
					$idContatto = $this->m['ContattiModel']->insertDaArray($valoriEmail, $fonte);
					
					$pagina = $this->m["PagesModel"]->selectId((int)$id);
					
					if (v("attiva_verifica_contatti") && $idContatto)
						$this->inviaMailConfermaContatto($idContatto);
					
					$res = $this->inviaMailContatto($id, $idContatto, $valoriEmail, $fonte);
					
// 					if ($isNewsletter)
// 						$oggetto = "form iscrizione a newsletter";
// 					else
// 						$oggetto = "form richiesta informazioni";
// 					
// 					ob_start();
// 					if ($isNewsletter)
// 						include (tpf("Regusers/mail_form_newsletter.php"));
// 					else
// 						include (tpf("Regusers/mail_form_contatti.php"));
// 					$output = ob_get_clean();
// 					
// 					$res = MailordiniModel::inviaMail(array(
// 						"emails"	=>	array(Parametri::$mailInvioOrdine),
// 						"oggetto"	=>	$oggetto,
// 						"testo"		=>	$output,
// 						"tipologia"	=>	"CONTATTO_NEWSLETT",
// 						"id_user"	=>	(int)User::$id,
// 						"id_page"	=>	(int)$id,
// 						"reply_to"	=>	$valoriEmail["email"],
// 						"id_contatto"	=>	$idContatto,
// 					));
					
// 						$mail->SMTPDebug = 2;
					
					if($res) {
						$idGrazie = PagineModel::gTipoPagina("GRAZIE");
						$idGrazieNewsletter = 0;
						
						// Iscrivo a Mailchimp
						if ($isNewsletter && ImpostazioniModel::$valori["mailchimp_api_key"] && ImpostazioniModel::$valori["mailchimp_list_id"])
						{
							$dataMailChimp = array(
								"email"	=>	$valoriEmail["email"],
								"status"=>	"subscribed",
							);
							
							if (isset($valoriEmail["nome"]))
								$dataMailChimp["firstname"] = $valoriEmail["nome"];
							
							if (isset($valoriEmail["cognome"]))
								$dataMailChimp["lastname"] = $valoriEmail["cognome"];
							
							syncMailchimp($dataMailChimp);
						}
						
						if ($isNewsletter)
							$idGrazieNewsletter = PagineModel::gTipoPagina("GRAZIE_NEWSLETTER");
						
						if ($idGrazieNewsletter)
							$this->redirect(getUrlAlias($idGrazieNewsletter).F::partial());
						else if ($idGrazie)
							$this->redirect(getUrlAlias($idGrazie).F::partial());
						else
							$this->redirect("grazie.html".F::partial());
					} else {
						Form::sNotice($tipo, "<div class='".v("alert_error_class")."'>errore nell'invio del messaggio, per favore riprova pi&ugrave tardi</div>");
					}
				}
				else
				{
					Form::sNotice($tipo, "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m['ContattiModel']->notice);
				}
			}
		}
		
// 		Form::$values = $this->m['ContattiModel']->getFormValues('insert','sanitizeHtml');
		
		Form::sValues($tipo, $this->m['ContattiModel']->getFormValues('insert','sanitizeHtml',null,Form::$defaultValues));
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
		return CommonModel::camboObbligatorio($campo, $this->controller, $queryType);
	}
}
