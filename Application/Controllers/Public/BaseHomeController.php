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
		Cache_Db::addTablesToCache(array("combinazioni","scaglioni"));
		
		if (v("attiva_formn_contatti"))
			$this->inviaMailFormContatti(0);
		
		if (!User::$adminLogged)
		{
			$cache = Cache_Html::getInstance();
			$cache->saveHtml = true;
		}
		
		$data["slide"] = $this->slide = $this->m("PagesModel")->clear()->addJoinTraduzionePagina()
			->where(array(
				"categories.section"	=>	"slide",
				"attivo"=>"Y",
				"in_evidenza"	=>	"Y",
			))->orderBy(v("main_slide_order"))->send();
		
		$clean["idShop"] = $this->m("CategoriesModel")->getShopCategoryId();
		
		$data["marchi"] = $this->m("MarchiModel")->clear()->orderBy("titolo")->send(false);
		
		$data["fasce"]  = "";
		
		// Estraggo le fasce
		if (v("usa_fasce_in_home"))
		{
			$clean["idPaginaHome"] = (int)$this->m("PagesModel")->clear()->where(array(
				"tipo_pagina"	=>	"HOME",
			))->field("id_page");
			
			if ($clean["idPaginaHome"])
			{
				PagesModel::$currentIdPage = $clean['idPaginaHome'];
				$data["fasce"] = $this->m("ContenutiModel")->elaboraContenuti($clean['idPaginaHome'], 0, $this);
			}
		}
		
		$data["tagCanonical"] = '<link rel="canonical" href="'.Url::getRoot().'" />';
		
		$this->append($data);
		
		if (Output::$html)
			$this->cload('main');
		else
			$this->load("api_output");
	}
	
	public function settacookie()
	{
		$this->clean();
		
		App::settaCookiesGdpr(true);
	}
	
	public function xmlprodotti($p = null)
	{
		// controllo token
		if (v("attiva_feed_solo_se_con_token") && !VariabiliModel::checkToken("token_feed_google_facebook"))
			die();
		
		Cache_Db::addTablesToCache(array("combinazioni","scaglioni"));
		
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
		
		if (v("elimina_emoticons_da_feed"))
		{
			$xmlArray["channel"]["title"] = F::removeEmoji($xmlArray["channel"]["title"]);
			$xmlArray["channel"]["description"] = F::removeEmoji($xmlArray["channel"]["description"]);
		}
		
// 		print_r($xmlArray);
		
		$xml = aToX($xmlArray);
		
		header ("Content-Type:text/xml");
		echo '<?xml version="1.0"?>';
		echo '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">';
		echo $xml;
		echo '</rss>';
	}

}
