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

class BaseWishlistController extends BaseController
{

	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);

		if (Output::$html)
		{
			$this->load('header');
			$this->load('footer','last');
		}
		
		$data['title'] = Parametri::$nomeNegozio . ' - Carrello';
		
		$data["arrayLingue"] = array();
		
		$this->append($data);
	}

	public function index($pageView = "full")
	{
		$data["pageView"] = sanitizeAll($pageView);
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/wishlist/vedi";
		}
		
		if (strcmp($pageView,"partial") === 0)
		{
			$this->clean();
		}
		
		$clean["wishlist_uid"] = sanitizeAll(User::$wishlist_uid);
		
		$data["pages"] = $this->m["WishlistModel"]->getProdotti();
// 		$data["pages"] = $this->m["WishlistModel"]->clear()->select("wishlist.*,pages.*")->inner("pages")->using("id_page")->where(array("wishlist_uid"=>$clean["wishlist_uid"]))->orderBy("id_wishlist ASC")->send();
		
		$this->append($data);
		
		if (Output::$json)
		{
			$pagineConDecode = array();
			
			foreach ($data["pages"] as $page)
			{
				$temp = $page;
				$page["quantity"] = 1;
				$page["pages"]["url-alias"] = getUrlAlias($page["pages"]["id_page"]);
				$page["pages"]["price"] = number_format($temp["pages"]["price"],2,",","");
// 				$page["pages"]["prezzo_promozione"] = number_format($page["pages"]["prezzo_promozione"],2,",",".");
// 				$page["pages"]["prezzo_scontato"] = number_format($temp["pages"]["price"],2,",","");
// 				$page["pages"]["iva"] = number_format($page["pages"]["iva"],2,",","");
				
				$page["wishlist"] = htmlentitydecodeDeep($page["wishlist"]);
				
				$pagineConDecode[] = $page;
			}
			
			Output::setBodyValue("Type", "Wishlist");
			Output::setBodyValue("Pages", $pagineConDecode);
			Output::setHeaderValue("CartProductsNumber",$this->m["WishlistModel"]->numberOfItems());
			
			$this->load("api_output");
		}
		else
		{
			if (strcmp($pageView,"partial") !== 0)
			{
				$this->load("top_cart");
			}
			
			$this->load('main');
		}
	}

	//HTML del carrello aggiornato via ajax
	public function ajax()
	{
		$data["pageView"] = "partial";
		
		$this->clean();
		
		$clean["wishlist_uid"] = sanitizeAll(User::$wishlist_uid);
		
		$data["pages"] = $this->m["WishlistModel"]->clear()->select("wishlist.*,pages.id_page")->inner("pages")->using("id_page")->where(array("wishlist_uid"=>$clean["wishlist_uid"]))->orderBy("id_wishlist ASC")->send();
		
		$this->append($data);
		
		$this->load('ajax_cart');
	}
	
	public function add($id_cart = 0)
	{
		$clean["id_cart"] = (int)$id_cart;
		
		$this->clean();
		
		$contentsFbk = "";
		
		if ($this->m["WishlistModel"]->add($clean["id_cart"]))
		{
			$result = "OK";
			
			$p = new PagesModel();
			$page = $p->selectId($clean["id_cart"]);
			
			if (!empty($page))
			{
				$contentsFbk = array(
					"currency"	=>	"EUR",
					"content_type"	=>	"product",
					"content_name"	=>	sanitizeJs(htmlentitydecode($page["title"])),
					"contents"	=>	array(
						array(
							"id"		=>	$page["id_page"],
							"quantity"	=>	1,
						)
					),
				);
			}
		}
		else
		{
			$result = "KO";
		}
		
		// Se sono JSON, stampa in output il carrello completo
		if (Output::$json)
		{
			Output::setHeaderValue("CartProductsNumber",$this->m["WishlistModel"]->numberOfItems());
			
			$this->load("api_output");
		}
		else
			echo json_encode(array(
				"result"	=>	$result,
				"contens_fbk"	=>	$contentsFbk,
			));
	}
	
	public function delete($id_cart)
	{
		$clean["id_cart"] = (int)$id_cart;
		
		$this->clean();
		
		if ($this->m["WishlistModel"]->delete($clean["id_cart"]))
		{
			$result = "OK";
		}
		else
		{
			$result = "KO";
		}
		
		// Se sono JSON, stampa in output il carrello completo
		if (Output::$json)
			$this->index();
		else
			echo json_encode(array(
				"result"	=>	$result,
				"contens_fbk"	=>	"",
			));
			
	}
	
	//$quantita: id_page:quantity|id_page:quantity|...
// 	public function update()
// 	{
// 		$this->clean();
// 		$clean["quantity"] = $this->request->post("products_list","","sanitizeAll");
// 
// 		$quantityArray = explode("|",$clean["quantity"]);
// 		
// 		foreach ($quantityArray as $q)
// 		{
// 			if (strcmp($q,"") !== 0 and strstr($q, ':'))
// 			{
// 				$temp = explode(":",$q);
// 				$this->m["CartModel"]->set($temp[0], $temp[1]);
// 			}
// 		}
// 		
// 		// Se sono JSON, stampa in output il carrello completo
// 		if (Output::$json)
// 			$this->index();
// 	}
}
