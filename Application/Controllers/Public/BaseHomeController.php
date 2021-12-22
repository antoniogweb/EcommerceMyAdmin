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

class BaseHomeController extends BaseController
{
	public $slide;
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);

		if (Output::$html)
		{
			$this->load('header');
			$this->load('footer','last');
		}

		$data['title'] = Parametri::$nomeNegozio . ' - ' . gtext(htmlentitydecode(ImpostazioniModel::$valori["title_home_page"]));

		$this->append($data);
	}

	public function index()
	{
		if (v("attiva_formn_contatti"))
			$this->inviaMailFormContatti(0);
		
		$data["slide"] = $this->slide = $this->m["PagesModel"]->clear()->addJoinTraduzionePagina()
			->where(array(
				"categories.section"	=>	"slide",
				"attivo"=>"Y",
				"in_evidenza"	=>	"Y",
			))->orderBy(v("main_slide_order"))->send();
		
		$clean["idShop"] = $this->m["CategoriesModel"]->getShopCategoryId();
		
		$data["marchi"] = $this->m["MarchiModel"]->clear()->orderBy("titolo")->send(false);
		
		$data["fasce"]  = "";
		
		// Estraggo le fasce
		if (v("usa_fasce_in_home"))
		{
			$clean["idPaginaHome"] = (int)$this->m["PagesModel"]->clear()->where(array(
				"tipo_pagina"	=>	"HOME",
			))->field("id_page");
			
			if ($clean["idPaginaHome"])
				$data["fasce"] = $this->m["ContenutiModel"]->elaboraContenuti($clean['idPaginaHome'], 0, $this);
		}
		
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
			$this->cload('main');
		else
			$this->load("api_output");
	}
	
	public function settacookie()
	{
		$this->clean();
		
		$time = time() + 3600*24*365*10;
		Cookie::set("ok_cookie", "OK", $time, "/");
		Cookie::set("ok_cookie_terzi", "OK", $time, "/");
// 		setcookie("ok_cookie","OK",$time,"/");
// 		setcookie("ok_cookie_terzi","OK",$time,"/");
	}
	
	public function xmlprodotti($p = null)
	{
		// controllo token
		if (v("attiva_feed_solo_se_con_token") && !VariabiliModel::checkToken("token_feed_google_facebook"))
			die();
		
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
		
// 		print_r($xmlArray);
		
		$xml = aToX($xmlArray);
		
		header ("Content-Type:text/xml");
		echo '<?xml version="1.0"?>';
		echo '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">';
		echo $xml;
		echo '</rss>';
	}

}
