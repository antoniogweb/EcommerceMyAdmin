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

class BaseHomeController extends BaseController
{
	public $slide;
	
	public function __construct($model, $controller, $queryString)
	{
		parent::__construct($model, $controller, $queryString);

		if (Output::$html)
		{
			$this->load('header');
			$this->load('footer','last');
		}

		$data['title'] = Parametri::$nomeNegozio . ' - ' . htmlentitydecode(ImpostazioniModel::$valori["title_home_page"]);

		$this->append($data);
	}

	public function index()
	{
		$data["slide"] = $this->slide = $this->m["PagesModel"]->clear()->select("*")->inner("categories")->on("categories.id_c = pages.id_c")
			->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")
			->where(array(
				"categories.section"	=>	"slide",
				"attivo"=>"Y",
			))->orderBy(v("main_slide_order"))->send();
		
		$clean["idShop"] = $this->m["CategoriesModel"]->getShopCategoryId();
		
		$data["marchi"] = $this->m["MarchiModel"]->clear()->orderBy("titolo")->send(false);
		
		
		
// 		$data["home"] = $this->m["PagesModel"]->clear()->inner("categories")->on("categories.id_c = pages.id_c")->where(array(
// 			"categories.section"	=>	"home",
// 			"attivo"=>"Y",
// 		))->orderBy("pages.id_order desc")->send();
		
		$this->append($data);
		
		$pagineConDecode = array();
		
		if (Output::$json)
		{
			foreach ($data["pages"] as $page)
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
			
			Parametri::$nomeSezioneProdotti;
			
			$idShopCat = (int)$this->m['CategoriesModel']->clear()->where(array(
				"section"	=>	Parametri::$nomeSezioneProdotti
			))->field("id_c");
			
			$res = $this->m["CategoriesModel"]->recursiveTree($idShopCat,2);
			
			Output::setBodyValue("Categories", $res);
		}
		
		Output::setBodyValue("Type", "Home");
		Output::setBodyValue("Pages", $pagineConDecode);
		
		if (Output::$html)
			$this->load('main');
		else
			$this->load("api_output");
	}
	
	public function settacookie()
	{
		$this->clean();
		
		$time = time() + 3600*24*365*10;
		setcookie("ok_cookie","OK",$time,"/");
	}
	
	public function xmlprodotti($p = null)
	{
		User::$nazione = null;
		
		if (isset($_GET["listino"]) && $_GET["listino"] != v("nazione_default") && CombinazionilistiniModel::listinoEsistente($_GET["listino"]))
			User::$nazione = sanitizeAll($_GET["listino"]);
		
		$this->clean();
		
		$prodotti = PagesModel::gXmlProdottiGoogle($p);
		
		$xmlArray = array();
		
		$xmlArray["channel"] = array(
			"title"	=>	htmlentitydecode(ImpostazioniModel::$valori["title_home_page"]),
			"link"	=>	Url::getRoot(),
			"description"	=>	htmlentitydecode(ImpostazioniModel::$valori["meta_description"]),
			"item"	=>	$prodotti,
		);
		
		$xml = aToX($xmlArray);
		
		header ("Content-Type:text/xml");
		echo '<?xml version="1.0"?>';
		echo '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">';
		echo $xml;
		echo '</rss>';
	}

}
