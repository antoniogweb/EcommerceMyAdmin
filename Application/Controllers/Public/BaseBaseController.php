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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!defined('EG')) die('Direct access not allowed!');

class BaseBaseController extends Controller
{
	protected $islogged = false;
	protected $iduser = 0;
	protected $dettagliUtente = null;
	
	public $prodottiInEvidenza;
	public $elencoMarchiFull;
	public $elencoTagFull;
	public $idShop = 0;
	public $getNewsInEvidenza;
	public $team = array();
	
	public $defaultRegistrazione = array();
	
	public function __construct($model, $controller, $queryString)
	{
		if (!defined("FRONT"))
			define('FRONT', ROOT);
		
// 		$_GET["asJson"] = true;
		
		Domain::$parentRoot = FRONT;
		Domain::$adminRoot = LIBRARY;
		
		parent::__construct($model, $controller, $queryString);
		
		Domain::$adminName = $this->baseUrlSrc."/admin";
		Domain::$publicUrl = $this->baseUrlSrc;
		
		$this->model("TraduzioniModel");
		
		$this->m["TraduzioniModel"]->ottieniTraduzioni();
		
		// Variabili
		$this->model('VariabiliModel');
		
		if (empty(VariabiliModel::$valori))
			VariabiliModel::ottieniVariabili();
		
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
		
		if (v("mostra_fasce_prezzo"))
			$this->model("FasceprezzoModel");
		
		$this->model("CaratteristichevaloriModel");
		
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
		
		// Correggi decimali imponibili sulla base dell'IVA estera
		$this->m["CartModel"]->correggiPrezzi();
		
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
		
		$data["prodottiInEvidenza"] = $this->prodottiInEvidenza = getRandom($this->m["PagesModel"]->clear()->select("*")
			->addJoinTraduzionePagina()
			->where(array(
				"in" => array("-id_c" => $childrenProdotti),
				"attivo"=>"Y",
				"in_evidenza"=>"Y",
				"acquistabile"	=>	"Y",
			))->orderBy("pages.id_order desc")->send(), v("numero_in_evidenza"));
		
		if (v("mostra_avvisi"))
			$data["avvisi"] = $this->m["PagesModel"]->where(array(
				"categories.section"	=>	"avvisi",
				"attivo"=>"Y",
			))->send();
		
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
			$idBlog = $data["idBlog"] = (int)$this->m["CategoriesModel"]->clear()->where(array(
				"section"	=>	"blog",
			))->field("id_c");
			
			$children = $this->m["CategoriesModel"]->children($idBlog, true);

			$data["ultimiArticoli"] = $this->getNewsInEvidenza = $this->m['PagesModel']->clear()->select("*")
				->addJoinTraduzionePagina()
				->where(array(
					"attivo" => "Y",
					"in" => array("-id_c" => $children),
				))->orderBy("data_news desc")->limit(4)->send();
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
		
// 		$data["articoliRecenti"] = $this->m["PagesModel"]->clear()->inner("categories")->on("categories.id_c = pages.id_c")->where(array(
// 			"categories.section"	=>	"slide",
// 			"attivo"=>"Y",
// 		))->orderBy("pages.id_order desc")->send();
		
		$data["categorieBlog"] = $this->m["CategoriesModel"]->children(87, false, false);
		
		if (v("estrai_in_promozione_home"))
		{
			//estraggo i prodotti in promozione
			$nowDate = date("Y-m-d");
			$pWhere = array(
				"gte"	=>	array("n!datediff('$nowDate',pages.dal)" => 0),
				" gte"	=>	array("n!datediff(pages.al,'$nowDate')" => 0),
				"attivo" => "Y",
				"in_promozione" => "Y",
				"acquistabile"	=>	"Y",
			);
			
			$data["inPromozione"] = getRandom($this->m["PagesModel"]->clear()->addJoinTraduzionePagina()->where($pWhere)->limit(20)->orderBy("pages.id_order")->send());
		}
		
		$data["meta_description"] = $data["title"] =  htmlentitydecode(ImpostazioniModel::$valori["meta_description"]);
		$data["keywords"] = $data["title"] =  htmlentitydecode(ImpostazioniModel::$valori["keywords"]);
		
		Lang::$current = Params::$lang;
		
		$data["alberoCategorieProdotti"] = $this->m["CategoriesModel"]->recursiveTree($clean["idShop"],2);
		
		$data["alberoCategorieProdottiConShop"] = array($data["categoriaShop"]) + $data["alberoCategorieProdotti"];
		
		$data["elencoCategorieFull"] = $this->m['CategoriesModel']->clear()->select("categories.*,contenuti_tradotti_categoria.*")->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")->where(array("id_p"=>$clean["idShop"]))->orderBy("lft")->send();
		
// 		print_r($data["alberoCategorieProdottiConShop"]);die();
		
		if (v("usa_marchi"))
		{
			$data["elencoMarchi"] = $this->m["MarchiModel"]->clear()->orderBy("titolo")->toList("id_marchio", "titolo")->send();
			
			$data["elencoMarchiFull"] = $this->elencoMarchiFull = $this->m["MarchiModel"]->clear()->addJoinTraduzione()->orderBy("marchi.titolo")->send();
		}
		
		if (v("usa_tag"))
		{
			$data["elencoTagFull"] = $this->elencoTagFull = $this->m["TagModel"]->clear()->addJoinTraduzione()->where(array(
				"attivo"	=>	"Y",
			))->orderBy("tag.titolo")->send();
		}
		
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
			$data["tipiPagina"] = $this->m["PagesModel"]->clear()->where(array(
				"ne"	=>	array("tipo_pagina" => ""),
			))->toList("tipo_pagina", "id_page")->send();
			
// 			print_r($data["tipiPagina"]);
			
			$data["selectNazioni"] = array(""	=>	gtext("Seleziona",true)) + $this->m["NazioniModel"]->selectNazioniAttive();
			$data["selectNazioniSpedizione"] = array(""	=>	gtext("Seleziona",true)) + $this->m["NazioniModel"]->selectNazioniAttiveSpedizione();
			
			$data["selectRuoli"] = $this->m["RuoliModel"]->selectTipi(true);
			
			if (v("attiva_tipi_azienda"))
				$data["selectTipiAziende"] = $this->m["TipiaziendaModel"]->selectTipi(true);
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
	
	protected function formRegistrazione()
	{
		require_once(LIBRARY.'/External/PHPMailer-master/src/Exception.php');
		require_once(LIBRARY.'/External/PHPMailer-master/src/PHPMailer.php');
		require_once(LIBRARY.'/External/PHPMailer-master/src/SMTP.php');

		// Se da App, genero la password e la invio all'utente
		if (isset($_GET["fromApp"]))
		{
			$randPass = generateString(10);
			$_POST["password"] = $_POST["confirmation"] = $randPass;
		}
		
		$data['notice'] = null;
		$data['isRegistrazione'] = true;
		
		$tipo_cliente = $this->request->post("tipo_cliente","","sanitizeAll");
		$pec = $this->request->post("pec","","sanitizeAll");
		$codiceDestinatario = $this->request->post("codice_destinatario","","sanitizeAll");
		
		$baseFields = v("insert_account_fields");
		
		// BASE: 'nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,username,accetto,tipo_cliente,nazione,pec,codice_destinatario,dprovincia,telefono_2';
		
		$fields = $baseFields.',password:sha1';
		
		if (v("attiva_ruoli"))
			$fields .= ",id_ruolo";
		
		if (v("attiva_tipi_azienda"))
			$fields .= ",id_tipo_azienda";
		
		$this->m['RegusersModel']->setFields($fields,'strip_tags');
		$datiCliente = $this->m['RegusersModel']->values;
		$this->m['RegusersModel']->sanitize("sanitizeAll");
		
		$this->m['RegusersModel']->setConditions($tipo_cliente, "insert", $pec, $codiceDestinatario);
		
		$this->m['RegusersModel']->fields = "$baseFields,password";
		
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
			$tessera = $this->request->post('tessera','');
			if (strcmp($tessera,'') === 0)
			{
				if ($this->m['RegusersModel']->checkConditions('insert'))
				{
					$tokenConferma = $this->m['RegusersModel']->values['confirmation_token'] = md5(randString(20).microtime().uniqid(mt_rand(),true));
					
					if ($this->m['RegusersModel']->insert())
					{
						$password = $this->request->post("password","","none");
						$clean["username"] = $this->request->post("username","","sanitizeAll");
						
						//loggo l'utente
						if (!v("conferma_registrazione"))
							$this->s['registered']->login($clean["username"],$password);
						
						if (Output::$json)
							$this->setUserHead();
						
// 							require_once(ROOT."/External/phpmailer/class.phpmailer.php");

						$mail = new PHPMailer(true); //New instance, with exceptions enabled

// 							$mail->SMTPDebug = 4;
						
						if (Parametri::$useSMTP)
						{
							$mail->IsSMTP();                         // tell the class to use SMTP
							$mail->SMTPAuth   = true;                  // enable SMTP authentication
							$mail->Port       = 25;                    // set the SMTP server port
							$mail->Host       = Parametri::$SMTPHost; 		// SMTP server
							$mail->Username   = Parametri::$SMTPUsername;     // SMTP server username
							$mail->Password   = Parametri::$SMTPPassword;            // SMTP server password
						}
						
						$mail->From       = Parametri::$mailFrom;
						$mail->FromName   = Parametri::$mailFromName;
						$mail->CharSet = 'UTF-8';
						
						$mail->SMTPOptions = array(
							'ssl' => array(
								'verify_peer' => false,
								'verify_peer_name' => false,
								'allow_self_signed' => true
							)
						);
						
						//manda mail con credenziali al cliente
						$mail->ClearAddresses();
						$mail->AddAddress($clean["username"]);
						
						if (ImpostazioniModel::$valori["bcc"])
							$mail->addBCC(ImpostazioniModel::$valori["bcc"]);
						
						$mail->AddReplyTo(Parametri::$mailFrom, Parametri::$mailFromName);
						$mail->Subject  = Parametri::$nomeNegozio." - ".gtext("invio credenziali nuovo utente");
						$mail->IsHTML(true);
						
						//mail con credenziali
						ob_start();
						include tp()."/Regusers/mail_credenziali.php";

						$output = ob_get_clean();
						$output = MailordiniModel::loadTemplate($mail->Subject, $output);
						
						$mail->AltBody = "Per vedere questo messaggio si prega di usare un client di posta compatibile con l'HTML";
						$mail->MsgHTML($output);
						if (@$mail->Send())
						{
							$mail->ClearAddresses();
							$mail->AddAddress(Parametri::$mailInvioOrdine);
							
							if (ImpostazioniModel::$valori["bcc"])
								$mail->addBCC(ImpostazioniModel::$valori["bcc"]);
							
							//mail con credenziali
							ob_start();
							include tp()."/Regusers/mail_al_negozio_registr_nuovo_cliente.php";

							$output = ob_get_clean();
							
							$output = MailordiniModel::loadTemplate($mail->Subject, $output);
							$mail->MsgHTML($output);
							
							@$mail->Send();
							
							$_SESSION['result'] = 'utente_creato';
							
							if (Output::$html)
								$this->redirect("avvisi");
						}
						else
						{
							$_SESSION['result'] = 'error';
							
							if (Output::$html)
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
}
