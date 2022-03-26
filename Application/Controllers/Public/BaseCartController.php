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

class BaseCartController extends BaseController
{
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);

		$this->load('header');
		$this->load('footer','last');
		
		$data['title'] = Parametri::$nomeNegozio . ' - Carrello';
		
		$data["arrayLingue"] = array();
		
		$this->append($data);
		
		IvaModel::getAliquotaEstera();
	}

	public function index($pageView = "full")
	{
		if (!v("ecommerce_online"))
			$this->redirect("");
		
		$data["pageView"] = sanitizeAll($pageView);
		
		$data["headerClass"] = "";
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/carrello/vedi";
		}
		
		if (strcmp($pageView,"partial") === 0)
		{
			$this->clean();
		}
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$data["pages"] = $this->m["CartModel"]->getProdotti();
		
		$this->append($data);
		
		if (strcmp($pageView,"partial") !== 0)
		{
			$this->load("top_cart");
		}
		
		$this->load('main');
	}

	//HTML del carrello aggiornato via ajax
	public function ajax()
	{
		$data["pageView"] = "partial";
		
		$this->clean();
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$data["pages"] = $this->m["CartModel"]->clear()->select("cart.*,pages.id_page")->inner("pages")->using("id_page")->where(array("cart_uid"=>$clean["cart_uid"]))->orderBy("id_cart ASC")->send();
		
		$this->append($data);
		
		$this->load('ajax_cart');
	}
	
	public function add($id_page = 0, $quantity = 1, $id_c = 0, $id_p = 0, $id_cart = 0)
	{
		if (v("carrello_monoprodotto"))
		{
			// Imposto la quantità a 1
			$quantity = 1;
			
			// Se non è una modifica del carrello svuoto il carrello
			if (!$id_cart)
				$this->m["CartModel"]->emptyCart();
		}
		
		$result = "KO";
		$idCart = 0;
		$errore = "";
		$contentsFbk = $contentsGtm = "";
		
		if (!v("ecommerce_online"))
		{
			echo json_encode(array(
				"result"	=>	$result,
				"idCart"	=>	$idCart,
				"errore"	=>	gtext("Il negozio è offline, ci scusiamo per il disguido."),
				"contens_fbk"	=>	"",
				"contens_gtm"	=>	"",
			));
			
			die();
		}
		
		$clean["id_page"] = (int)$id_page;
		$clean["quantity"] = (int)$quantity;
		$clean["id_c"] = (int)$id_c;
		$clean["id_p"] = (int)$id_p;
		$clean["id_cart"] = (int)$id_cart;
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		$clean["json_pers"] = $this->request->post("json_pers","");
		
		$c = new CombinazioniModel();
		
		$arrayPers = array();
		
		if ($clean["json_pers"])
			$arrayPers = json_decode($clean["json_pers"], true);
		
		// Cerco la combinazione principale
		if ((int)$clean["id_c"] === 0)
		{
			$principale = $c->combinazionePrincipale($clean["id_page"]);
			
			if (!empty($principale))
				$clean["id_c"] = (int)$principale["id_c"];
		}
		
		$recordCart = array();
		
		// Se sto modificando dal carrello
		if ((int)$clean["id_cart"] !== 0)
		{
			$recordCart = $this->m["CartModel"]->getCart($clean["id_cart"]);
			
			if (!empty($recordCart))
			{
				if ($this->m["CartModel"]->checkQta($clean["id_c"], ($clean["quantity"] - $recordCart["quantity"])))
					$this->m["CartModel"]->delete($clean["id_cart"]);
			}
		}
		
		$this->clean();
		
		if ($clean["quantity"] >= 0)
		{
			$giacenza = $c->qta($clean["id_c"]);
			
			if (!v("attiva_giacenza") || $clean["quantity"] <= $giacenza)
			{
				// OK giacenza
				$idCart = $this->m["CartModel"]->add($clean["id_page"], $clean["quantity"], $clean["id_c"], $clean["id_p"], $arrayPers);
				
				if ($idCart)
				{
					if ($idCart == -1)
					{
						$errore = gtext("Attenzione, hai già inserito nel carrello tutti i pezzi presenti a magazzino", false);
					}
					else
					{
						if (!empty($recordCart))
						{
							$this->m["CartModel"]->setValues(array(
								"id_order"	=>	$recordCart["id_order"],
							));
							
							$this->m["CartModel"]->update(null, "id_cart = " . (int)$idCart . " AND cart_uid = '" . $clean["cart_uid"] . "'");
						}
						
						$result = "OK";
						
						$rcu = $this->m["CartModel"]->getCart($idCart);
						
						if (!empty($rcu))
						{
							$contentsFbk = array(
								"value"	=>	v("prezzi_ivati_in_carrello") ? $rcu["price_ivato"] : $rcu["price"],
								"currency"	=>	"EUR",
								"content_type"	=>	"product",
								"content_name"	=>	sanitizeJs(htmlentitydecode($rcu["title"])),
								"contents"	=>	array(
									array(
										"id"		=>	$rcu["id_page"],
										"quantity"	=>	$clean["quantity"],
									)
								),
							);
							
							$contentsGtm = array(array(
								"id"	=>	$rcu["id_page"],
								"name"	=>	sanitizeJs(htmlentitydecode($rcu["title"])),
								"quantity"	=>	$clean["quantity"],
								"price"		=>	v("prezzi_ivati_in_carrello") ? $rcu["price_ivato"] : $rcu["price"],
							));
						}
					}
				}
				else
					$errore = gtext("Si prega di selezionare la variante", false);
			}
			else
			{
				// KO giacenza
				if ((int)$giacenza === 0)
					$errore = gtext("Attenzione, prodotto esaurito", false);
				else if ((int)$giacenza == 1)
					$errore = gtext("Attenzione, è rimasto un solo prodotto in magazzino", false);
				else if ((int)$giacenza > 1)
				{
					$errore = gtext("Attenzione, sono rimasti solo [N] prodotti in magazzino", false);
					$errore = str_replace("[N]", $giacenza, $errore);
				}
			}
		}
		else
		{
			$errore = gtext("Si prega di indicare una quantità maggiore di zero", false);
		}
		
		echo json_encode(array(
			"result"	=>	$result,
			"idCart"	=>	$idCart,
			"errore"	=>	$errore,
			"contens_fbk"	=>	$contentsFbk,
			"contens_gtm"	=>	$contentsGtm,
		));
	}
	
	public function delete($id_cart)
	{
		if (!v("ecommerce_online"))
			$this->redirect("");
		
		$clean["id_cart"] = (int)$id_cart;
		
		$this->clean();
		
		if ($id_cart && $this->m["CartModel"]->delete($clean["id_cart"]))
		{
			$result = "OK";
		}
		else
		{
			$result = "KO";
		}
		
		echo $result;
	}
	
	//$quantita: id_page:quantity|id_page:quantity|...
	public function update()
	{
		if (!v("ecommerce_online"))
			$this->redirect("");
		
		$this->clean();
		$clean["quantity"] = $this->request->post("products_list","","sanitizeAll");

		$quantityArray = explode("|",$clean["quantity"]);
		
		$arrayIdErroriQta = array();
		$arrayIdQuantity = array();
		
		foreach ($quantityArray as $q)
		{
			if (strcmp($q,"") !== 0 and strstr($q, ':'))
			{
				$temp = explode(":",$q);
				
				if (!$this->m["CartModel"]->checkQtaFinale($temp[0], $temp[1]))
					$arrayIdErroriQta[] = $temp[0];
				
				$arrayIdQuantity[] = array($temp[0], $temp[1]);
			}
		}
		
		if ((int)count($arrayIdErroriQta) === 0)
		{
			foreach ($arrayIdQuantity as $temp)
			{
				$this->m["CartModel"]->set($temp[0], $temp[1]);
			}
		}
		
		echo json_encode($arrayIdErroriQta);
	}
}
