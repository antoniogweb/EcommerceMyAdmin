<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
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

class BaseBaseController extends Controller
{
	protected $islogged = false;
	protected $iduser = 0;
	protected $dettagliUtente = null;
	
	public $prodottiInEvidenza;
	public $elencoMarchiFull;
	public $idShop = 0;
	public $getNewsInEvidenza;
	public $team = array();

	public function __construct($model, $controller, $queryString)
	{
		if (!defined("FRONT"))
			define('FRONT', ROOT);
		
// 		$_GET["asJson"] = true;
		
		Domain::$parentRoot = FRONT;
		Domain::$adminRoot = LIBRARY;
		Domain::$adminName = $this->baseUrlSrc."/admin";
		
		parent::__construct($model, $controller, $queryString);
		
		$this->model("TraduzioniModel");
		
		$this->m["TraduzioniModel"]->ottieniTraduzioni();
		
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
		
		$this->m["ImpostazioniModel"]->getImpostazioni();
		
		//leggi le impostazioni
		if (ImpostazioniModel::$valori)
		{
			Parametri::$useSMTP = ImpostazioniModel::$valori["usa_smtp"] == "Y" ? true : false;
			Parametri::$SMTPHost = ImpostazioniModel::$valori["smtp_host"];
			Parametri::$SMTPPort = ImpostazioniModel::$valori["smtp_port"];
			Parametri::$SMTPUsername = ImpostazioniModel::$valori["smtp_user"];
			Parametri::$SMTPPassword = ImpostazioniModel::$valori["smtp_psw"];
			Parametri::$mailFrom = ImpostazioniModel::$valori["smtp_from"];
			Parametri::$mailFromName = ImpostazioniModel::$valori["smtp_nome"];
			Parametri::$mailInvioOrdine = ImpostazioniModel::$valori["mail_invio_ordine"];
			Parametri::$mailInvioConfermaPagamento = ImpostazioniModel::$valori["mail_invio_conferma_pagamento"];
			Parametri::$nomeNegozio = ImpostazioniModel::$valori["nome_sito"];
			Parametri::$iva = ImpostazioniModel::$valori["iva"];
			Parametri::$ivaInclusa = ImpostazioniModel::$valori["iva_inclusa"] == "Y" ? true : false;
			Parametri::$useSandbox = ImpostazioniModel::$valori["usa_sandbox"] == "Y" ? true : false;
			Parametri::$paypalSeller = ImpostazioniModel::$valori["paypal_seller"];
			Parametri::$paypalSandBoxSeller = ImpostazioniModel::$valori["paypal_sandbox_seller"];
		}
		
		// Variabili
		$this->model('VariabiliModel');
		VariabiliModel::ottieniVariabili();
		
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
			
			$data['nomeCliente'] = (strcmp($data['dettagliUtente']["tipo_cliente"],"privato") === 0 || strcmp($data['dettagliUtente']["tipo_cliente"],"libero_professionista") === 0) ?  $data['dettagliUtente']["nome"] : $data['dettagliUtente']["ragione_sociale"];
			
			// Estraggo lo sconto dell'utente
			User::$classeSconto = $this->m["ClassiscontoModel"]->selectId(User::$dettagli["id_classe"]);
			
			if (!empty(User::$classeSconto) && User::$classeSconto["sconto"] > 0 && User::$classeSconto["sconto"] < 100)
			{
				User::$sconto = User::$classeSconto["sconto"];
				
				User::$categorieInClasseSconto = $this->m["CategoriesModel"]->getListaCategorieInClasseSconto();
			}
			
			// Imposto lo stato loggato su Output
			Output::setHeaderValue("Status","logged");
			Output::setHeaderValue("UserId",$this->s['registered']->getUid());
			Output::setHeaderValue("Nome",$data['nomeCliente']);
			Output::setHeaderValue("Email",User::$dettagli["username"]);
// 			print_r(User::$categorieInClasseSconto);
		}
		
		if ($this->s['admin']->status['status'] === 'logged')
		{
			$data["adminUser"] = User::$adminLogged = true;
		}
		
		$data["isProdotto"] = false;
		$data["title"] =  ImpostazioniModel::$valori["title_home_page"];
		
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
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		$data["carrello"] = $this->m["CartModel"]->getProdotti();
		
		$data["prodInCart"] = $this->m["CartModel"]->numberOfItems();
		$data["prodInWishlist"] = $this->m["WishlistModel"]->numberOfItems();
		
		Domain::$name = $this->baseUrl;
		
		if (in_array($_SERVER['REQUEST_URI'],array("/home","/home/index","/home/index/")))
		{
			$this->redirect("");
		}
		
		//estraggo i prodotti in evidenza
// 		$data["inEvidenza"] = getRandom($this->m["PagesModel"]->clear()->where(array("attivo"=>"Y","in_evidenza"=>"Y"))->limit(20)->orderBy("pages.id_order")->send());
		
		$clean["idShop"] = $data["idShop"] = $this->idShop = $this->m["CategoriesModel"]->getShopCategoryId();
		
		$childrenProdotti = $this->m["CategoriesModel"]->children($clean["idShop"], true);
		
		$data["prodottiInEvidenza"] = $this->prodottiInEvidenza = getRandom($this->m["PagesModel"]->clear()->select("*")->inner("categories")->on("categories.id_c = pages.id_c")
			->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")
			->where(array(
				"in" => array("-id_c" => $childrenProdotti),
				"attivo"=>"Y",
				"in_evidenza"=>"Y",
			))->orderBy("pages.id_order desc")->send(), 4);
		
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
		
		$idBlog = (int)$this->m["CategoriesModel"]->clear()->where(array(
			"section"	=>	"blog",
		))->field("id_c");
		
		$children = $this->m["CategoriesModel"]->children($idBlog, true);

		$data["ultimiArticoli"] = $this->getNewsInEvidenza = $this->m['PagesModel']->clear()->select("*")
			->inner("categories")->on("categories.id_c = pages.id_c")
			->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")
			->where(array(
				"attivo" => "Y",
				"in" => array("-id_c" => $children),
			))->orderBy("data_news desc")->limit(4)->send();
		
		if (v("team_attivo"))
		{
			$idTeam = (int)$this->m["CategoriesModel"]->clear()->where(array(
				"section"	=>	"team",
			))->field("id_c");
			
			$data["team"] = $this->team = $this->m['PagesModel']->clear()->select("*")
				->inner("categories")->on("categories.id_c = pages.id_c")
				->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")
				->where(array(
					"attivo"	=>	"Y",
					"id_c"		=>	(int)$idTeam,
				))->orderBy("pages.id_order")->send();
		}
		
// 		$data["articoliRecenti"] = $this->m["PagesModel"]->clear()->inner("categories")->on("categories.id_c = pages.id_c")->where(array(
// 			"categories.section"	=>	"slide",
// 			"attivo"=>"Y",
// 		))->orderBy("pages.id_order desc")->send();
		
		$data["categorieBlog"] = $this->m["CategoriesModel"]->children(87, false, false);
		
		//estraggo i prodotti in promozione
// 		$nowDate = date("Y-m-d");
// 		$pWhere = array(
// 			"gte"	=>	array("n!datediff('$nowDate',pages.dal)" => 0),
// 			" gte"	=>	array("n!datediff(pages.al,'$nowDate')" => 0),
// 			"attivo" => "Y",
// 			"in_promozione" => "Y",
// 		);
// 		
// 		$data["inPromozione"] = getRandom($this->m["PagesModel"]->clear()->where($pWhere)->limit(20)->orderBy("pages.id_order")->send());
		
		$data["meta_description"] = $data["title"] =  htmlentitydecode(ImpostazioniModel::$valori["meta_description"]);
		$data["keywords"] = $data["title"] =  htmlentitydecode(ImpostazioniModel::$valori["keywords"]);
		
		Lang::$current = Params::$lang;
		
// 		echo $clean["idShop"];
		$data["alberoCategorieProdotti"] = $this->m["CategoriesModel"]->recursiveTree($clean["idShop"],2);
		
		$data["alberoCategorieProdottiConShop"] = array($data["categoriaShop"]) + $data["alberoCategorieProdotti"];
		
// 		print_r($data["alberoCategorieProdottiConShop"]);die();
		
		$data["elencoMarchi"] = $this->m["MarchiModel"]->clear()->orderBy("titolo")->toList("id_marchio", "titolo")->send();
		
		$data["elencoMarchiFull"] = $this->elencoMarchiFull = $this->m["MarchiModel"]->clear()->addJoinTraduzione()->orderBy("marchi.titolo")->send();
// 		print_r($data["elencoMarchi"]);
// 		$res = json_encode($res);
// 		echo $res;
// 		die();
// 		$res = $this->m["CategoriesModel"]->getTreeWithDepth(4);
// 		print_r($res);die();
// 		$res = $this->m["MenuModel"]->getTreeWithDepth(2);
// // 		print_r($res);
// 		$data["menu"] = $this->m["MenuModel"]->getMenu($res);
// 		$data["menu_select"] = $this->m["MenuModel"]->getSelectMenu($res);
		
// 		$res = $this->m["CategoriesModel"]->getTreeWithDepth(2,87);
// 		$data["menu_blog"] =  $this->m["CategoriesModel"]->getMenu($res);
		
// 		$data["itUrl"] = "it";
// 		$data["enUrl"] = "en";
		
		$data["arrayLingue"] = array();
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l;
		}
		
// 		if (Params::$lang === "it")
// 		{
			$res = $this->m["MenuModel"]->getTreeWithDepth(2, null, Params::$lang);
			$data["menu"] = $this->m["MenuModel"]->getMenu($res,false, Params::$lang);
// 			$data["menuMobile"] = $this->m["MenuModel"]->getMenu($res,true,true);
			
			$data["langDb"] = $this->langDb = Lang::$langDb = null;
// 		}
// 		else
// 		{
// 			$res = $this->m["MenusecModel"]->getTreeWithDepth(2);
// 			$data["menu"] = $this->m["MenusecModel"]->getMenu($res);
// 			
// 			$data["langDb"] = $this->langDb = Lang::$langDb = "_en";
// 		}
		
		if (Output::$html)
		{
			$data["selectNazioni"] = array(""	=>	gtext("Seleziona",true)) + $this->m["NazioniModel"]->selectNazioniAttive();
			$data["selectNazioniSpedizione"] = array(""	=>	gtext("Seleziona",true)) + $this->m["NazioniModel"]->selectNazioniAttiveSpedizione();
			
			$data["selectRuoli"] = $this->m["RuoliModel"]->selectRuoli(true);
		}
		
		$data["pagesCss"] = $data["paginaGenerica"] = "";
		
		$this->append($data);
		
		Params::$rewriteStatusVariables = false;
		
		if (getSubTotalN() > 9999999)
		{
			$this->m["CartModel"]->emptyCart();
		}
		
		$this->m["PagesModel"]->aggiornaStatoProdottiInPromozione();
	}

	public function getProdotti()
	{
		if (!isset($this->pages))
			return "";
		
		$pages = $this->pages;
		
		ob_start();
		include ROOT."/Application/Views/Contenuti/Elementi/Categorie/blocco_prodotti.php";
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getProdottiInEvidenza()
	{
		if (!isset($this->prodottiInEvidenza))
			return "";
		
		if (!isset($this->elencoMarchiFull))
			return "";
		
		$pages = $this->prodottiInEvidenza;
		$elencoMarchiFull = $this->elencoMarchiFull;
		$idShop = $this->idShop;
		
		ob_start();
		include ROOT."/Application/Views/Contenuti/Elementi/Categorie/prodotti_in_evidenza.php";
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getNewsInEvidenza()
	{
		if (!isset($this->getNewsInEvidenza))
			return "";
		
		$pages = $this->getNewsInEvidenza;
		
		ob_start();
		include ROOT."/Application/Views/Contenuti/Elementi/Categorie/news_in_evidenza.php";
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getTeam()
	{
		if (!isset($this->team))
			return "";
		
		$pages = $this->team;
		
		ob_start();
		include ROOT."/Application/Views/Contenuti/Elementi/Categorie/team.php";
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getSlideProdotto()
	{
		if (!isset($this->pages))
			return "";
		
		$pages = $this->pages;
		$p = $this->p;
		$altreImmagini = $this->altreImmagini;
		
		ob_start();
		include ROOT."/Application/Views/Contenuti/Elementi/Pagine/slide_prodotto.php";
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getCarrelloProdotto()
	{
		if (!isset($this->pages))
			return "";
		
		$pages = $this->pages;
		$p = $this->p;
		
		$lista_attributi = $this->lista_attributi;
		$lista_valori_attributi = $this->lista_valori_attributi;
		$scaglioni = $this->scaglioni;
		$prezzoMinimo = $this->prezzoMinimo;
		
		ob_start();
		include ROOT."/Application/Views/Contenuti/Elementi/Pagine/carrello_prodotto.php";
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getSlide()
	{
		if (!isset($this->slide))
			return "";
		
		$pages = $this->slide;
		
		ob_start();
		include ROOT."/Application/Views/Contenuti/Elementi/Pagine/slide_principale.php";
		$output = ob_get_clean();
		
		return $output;
	}
}
