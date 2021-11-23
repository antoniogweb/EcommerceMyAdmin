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

class CartModel extends GenericModel {
	
	public static $ordinamento = 0;
	public static $deletedExpired = false;
	public static $checkCart = false;
	
	public function __construct() {
		$this->_tables='cart';
		$this->_idFields='id_cart';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'cart.id_order';
		$this->_lang = 'It';
		
		parent::__construct();

		$this->deleteExpired();
		$this->checkCart();
	}
	
	public function relations() {
        return array(
			'pagina' => array("BELONGS_TO", 'PagineModel', 'id_page',null,"CASCADE","Si prega di selezionare la pagina"),
        );
    }
    
	public function deleteExpired()
	{
		if (!self::$deletedExpired)
		{
			$limit = time() - Parametri::$durataCarrello; 
			$this->db->del('cart','creation_time < '.$limit);
			self::$deletedExpired = true;
		}
	}
	
	public function checkCart()
	{
		if (!self::$checkCart)
		{
			$daEliminare = $this->db->query("SELECT id_cart,combinazioni.id_c FROM `cart` left join combinazioni on cart.id_c = combinazioni.id_c where combinazioni.id_c is null");
			
			if (count($daEliminare) > 0)
			{
				$daEliminare = $this->getList($daEliminare, "cart.id_cart");
				$daEliminareWhere = implode(",", $daEliminare);
				
				$this->db->query("delete from cart where id_cart in ($daEliminareWhere)");
			}
			
			self::$checkCart = true;
		}
	}
	
	// Totale scontato
	public function totaleScontato($conSpedizione = false)
	{
// 		IvaModel::getAliquotaEstera();
		
		$cifre = v("cifre_decimali");
		
		if (hasActiveCoupon())
		{
			$p = new PromozioniModel();
			
			$coupon = $p->getCoupon(User::$coupon);
			
			$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
			
			$res = $this->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->send();
			
			$total = 0;
			
			if (count($res) > 0)
			{
				foreach ($res as $r)
				{
					$prezzo = number_format($r["cart"]["price"],$cifre,".","");
					
					if (in_array($r["cart"]["id_page"], User::$prodottiInCoupon))
					{
						$prezzo = number_format($prezzo - $prezzo*($coupon["sconto"]/100),$cifre,".","");
					}
					
					$total = $total + number_format($prezzo * $r["cart"]["quantity"],$cifre,".","");
				}
			}
			
			if ($conSpedizione)
				$total += number_format(getSpedizioneN(), $cifre,".","");
			
			return $total;
		}
		else
			return $this->total($conSpedizione);
	}
	
	//get the total from the cart
	public function total($conSpedizione = false)
	{
// 		IvaModel::getAliquotaEstera();
		
		$cifre = v("cifre_decimali");
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
// 		$res = $this->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->send();
		
		$res2 = $this->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->send();
		
		$total = 0;
		
		$trovata = false;
		
		if (count($res2) > 0)
		{
			foreach ($res2 as $r)
			{
				$prezzo = number_format($r["cart"]["price"],$cifre,".","");
				
				$total = $total + number_format($prezzo * $r["cart"]["quantity"],$cifre,".","");
// 				$total = $total + number_format($r["aggregate"]["SOMMA"],2,".","");
			}
		}
		
		if ($conSpedizione)
			$total += number_format(getSpedizioneN(), $cifre,".","");
		
		return $total;
	}
	
	public static function getAliquotaIvaSpedizione()
	{
// 		IvaModel::getAliquotaEstera();
		
		$ivaSped = self::getMaxIva();
		
		// Controllo l'aliquota estera
		if (isset(IvaModel::$aliquotaEstera))
			$ivaSped = IvaModel::$aliquotaEstera;
		
		return $ivaSped;
	}
	
	// Totale iva dal carrello
	public function iva($conSpedizione = true, $pieno = false)
	{
		$cifre = v("cifre_decimali");
		
// 		IvaModel::getAliquotaEstera();
		
		$sconto = 0;
		if (hasActiveCoupon() && !$pieno)
		{
			$p = new PromozioniModel();
			$coupon = $p->getCoupon(User::$coupon);
			$sconto = $coupon["sconto"];
		}
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$res = $this->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->send();
		
// 		$res2 = $this->clear()->select("*,sum((price - (price * ".(float)$sconto."/100)) * quantity) as SOMMA")->where(array("cart_uid"=>$clean["cart_uid"]))->groupBy("cart.iva")->orderBy("cart.iva")->send();
		
// 		echo "<pre>";
// 		print_r($res2);
// 		echo "</pre>";
		
		$total = 0;
		
		$trovata = false;
		
		$arraySubtotale = $arrayIva = array();
		
		if (count($res) > 0)
		{
			foreach ($res as $r)
			{
				$prezzo = number_format($r["cart"]["price"],$cifre,".","");
					
				if (in_array($r["cart"]["id_page"], User::$prodottiInCoupon))
				{
					$prezzo = number_format($prezzo - $prezzo*($sconto/100),$cifre,".","");
				}
				
				$subtotale = number_format($prezzo * $r["cart"]["quantity"],$cifre,".","");
				
				$ivaRiga = number_format($r["cart"]["iva"],$cifre,".","");
				
				// Controllo l'aliquota estera
				if (isset(IvaModel::$aliquotaEstera))
					$ivaRiga = IvaModel::$aliquotaEstera;
				
				if (isset($arraySubtotale[$ivaRiga]))
					$arraySubtotale[$ivaRiga] += $subtotale;
				else
					$arraySubtotale[$ivaRiga] = $subtotale;
				
				$iva = $subtotale*($ivaRiga/100);
				$total += number_format($iva,$cifre,".","");
			}
		}
		
		if ($conSpedizione)
		{
			$ivaSped = self::getAliquotaIvaSpedizione();
			
			$ivaSped = number_format($ivaSped,$cifre,".","");
			
			if (isset($arraySubtotale[$ivaSped]))
				$arraySubtotale[$ivaSped] += number_format(getSpedizioneN(),$cifre,".","");
			else
				$arraySubtotale[$ivaSped] = number_format(getSpedizioneN(),$cifre,".","");
		}
		
		foreach ($arraySubtotale as $iva => $sub)
		{
			$arrayIva[$iva] = number_format($sub*$iva/100,$cifre,".","");
		}
		
// 		if (isset($_GET["dev"]))
// 			print_r($arrayIva);
		
// 		$subtotale = number_format(getSpedizioneN(),2,".","");
// 		$iva = $subtotale*(Parametri::$iva/100);
// 		$total += number_format($iva,2,".","");
		
		$total = array_sum($arrayIva);
		
		return $total;
	}
	
	public static function getIdIvaSpedizione()
	{
		$idIvaSped = self::getMaxIva("id_iva");
		
		// Controllo l'aliquota estera
		if (isset(IvaModel::$idIvaEstera))
			$idIvaSped = IvaModel::$idIvaEstera;
		
		return $idIvaSped;
	}
	
	public static function getMaxIva($field = "iva")
	{
		$c = new CartModel();
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$record = $c->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->orderBy("iva desc")->limit(1)->record();
		
		if (!empty($record))
			return $record[$field];
		
		$i = new IvaModel();
		
		$record = $i->clear()->orderBy("id_iva")->limit(1)->record();
		
		if (!empty($record))
		{
			if ($field == "iva")
				$field = "valore";
			
			return $record[$field];
		}
		
		return Parametri::$iva;
	}
	
// 	// Totale scontato
// 	public function totaleScontato()
// 	{
// 		if (hasActiveCoupon())
// 		{
// 			$p = new PromozioniModel();
// 			
// 			$coupon = $p->getCoupon(User::$coupon);
// 			
// 			$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
// 			
// 			$res = $this->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->send();
// 			
// 			$total = 0;
// 			
// 			if (count($res) > 0)
// 			{
// 				foreach ($res as $r)
// 				{
// 					$priceScontato = $r["cart"]["price"] - ($r["cart"]["price"] * ($coupon["sconto"] / 100));
// 					$total = $total + ($priceScontato * $r["cart"]["quantity"]);
// 				}
// 			}
// 			
// 			return $total;
// 		}
// 		else
// 			return $this->total();
// 	}
// 	
// 	//get the total from the cart
// 	public function total()
// 	{
// 		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
// 		
// 		$res = $this->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->send();
// 		
// 		$total = 0;
// 		
// 		if (count($res) > 0)
// 		{
// 			foreach ($res as $r)
// 			{
// 				$total = $total + ($r["cart"]["price"] * $r["cart"]["quantity"]);
// 			}
// 		}
// 		
// 		return $total;
// 	}
// 	
// 	// Totale iva dal carrello
// 	public function iva()
// 	{
// 		$sconto = 0;
// 		if (hasActiveCoupon())
// 		{
// 			$p = new PromozioniModel();
// 			$coupon = $p->getCoupon(User::$coupon);
// 			$sconto = $coupon["sconto"];
// 		}
// 		
// 		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
// 		
// 		$res = $this->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->send();
// 		
// 		$total = 0;
// 		
// 		if (count($res) > 0)
// 		{
// 			foreach ($res as $r)
// 			{
// 				$priceScontato = $r["cart"]["price"] - ($r["cart"]["price"] * ($sconto / 100));
// 				$total = $total + ($priceScontato * ($r["cart"]["iva"] / 100) * $r["cart"]["quantity"]);
// 			}
// 		}
// 		
// 		return $total;
// 	}
	
	public function getPesoTotale()
	{
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$res = $this->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->send();
		
		$total = 0;
		
		if (count($res) > 0)
		{
			foreach ($res as $r)
			{
				$total = $total + ($r["cart"]["peso"] * $r["cart"]["quantity"]);
			}
		}
		
		return $total;
	}
	
	public function emptyCart()
	{
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$this->del(null, "cart_uid = '" . $clean["cart_uid"] . "'");
	}
	
	public function correggiPrezzi()
	{
		if (!v("prezzi_ivati_in_carrello"))
			return;
		
		if (!v("prezzi_ivati_in_prodotti"))
			return;
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		if (v("usa_transactions"))
			$this->db->beginTransaction();
		
		$righe = $this->clear()->where(array(
			"cart_uid"	=>	$clean["cart_uid"],
		))->send(false);
		
		foreach ($righe as $r)
		{
			$aliquota = isset(IvaModel::$aliquotaEstera) ? IvaModel::$aliquotaEstera : $r["iva"];
			
			$nuovoPrezzoUnitarioIvato = number_format(($r["price_ivato"] / (1 + ($r["iva"] / 100))) * (1 + ($aliquota / 100)), 2, ".", "");
			
			$nuovoPrezzoUnitario = number_format($nuovoPrezzoUnitarioIvato / (1 + ($aliquota / 100)), v("cifre_decimali"), ".", "");
			
			if ($r["prezzo_intero_ivato"] > 0)
				$rapporto = $r["price_ivato"] / $r["prezzo_intero_ivato"];
			else
				$rapporto = 1;
			
			$nuovoPrezzoUnitarioIntero = number_format($nuovoPrezzoUnitario / $rapporto, v("cifre_decimali"), ".", "");
			
			if ($nuovoPrezzoUnitario != $r["price"] || $nuovoPrezzoUnitarioIntero != $r["prezzo_intero"])
			{
				$this->setValues(array(
					"price"	=>	$nuovoPrezzoUnitario,
					"prezzo_intero"	=>	$nuovoPrezzoUnitarioIntero,
				));
				
				$this->update($r["id_cart"]);
			}
		}
		
		if (v("usa_transactions"))
			$this->db->commit();
	}
	
	public function set($id_cart, $quantity)
	{
		$clean["id_cart"] = (int)$id_cart;
		$clean["quantity"] = abs((int)$quantity);
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		if ($clean["quantity"] === 0)
		{
			$this->delete($clean["id_cart"]);
		}
		else
		{
			$cart = $this->selectId($clean["id_cart"]);
			
			if (!empty($cart))
			{
				// Controllo la quantità
				$qtaPresente = $cart["quantity"];
				$qtaAggiunta = ($clean["quantity"] - $qtaPresente);
				
				if (!$this->checkQta($cart["id_c"], $qtaAggiunta))
					return -1;
				
				$this->values = array(
					"quantity" => $clean["quantity"],
				);
				
				$this->values["price"] = $this->calcolaPrezzoFinale($cart["id_page"], $cart["prezzo_intero"], $clean["quantity"], true, true);
				
				if (v("prezzi_ivati_in_prodotti"))
					$this->values["price_ivato"] = $this->calcolaPrezzoFinale($cart["id_page"], $cart["prezzo_intero_ivato"], $clean["quantity"], true, true);
				
				if (number_format($this->values["price"],2,".","") != number_format($cart["prezzo_intero"],2,".",""))
					$this->values["in_promozione"] = "Y";
				else
					$this->values["in_promozione"] = "N";
				
				$this->sanitize();
				$this->update(null, "id_cart = " . $clean["id_cart"] . " AND cart_uid = '" . $clean["cart_uid"] . "'");
			}
		}
	}
	
	public function delete($id_cart)
	{
		$clean["id_cart"] = (int)$id_cart;
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		return $this->del(null, "(id_cart = " . $clean["id_cart"] . " OR id_p = " . $clean["id_cart"] . ") AND cart_uid = '" . $clean["cart_uid"] . "'");
	}
	
	public function calcolaPrezzoFinale($idPage, $prezzoIntero, $qty = 1, $checkPromo = true, $checkUser = true)
	{
		$clean["id_page"] = (int)$idPage;
		
		$p = new PagesModel();
		$sc = new ScaglioniModel();
		$cl = new ClassiscontoModel();
		
		$page = $p->selectId($clean["id_page"]);
		
		$arraySconti = array();
		$arrayScontiDescrizione = array();
		
		$prezzoFinale = $prezzoIntero;
		
		if (!empty($page))
		{
			//sconto classe di sconto
			if ($checkUser && User::$logged && User::$classeSconto)
			{
				if (in_array($page["id_c"], User::$categorieInClasseSconto))
				{
					$classeSconto = User::$classeSconto;
					
	// 				$classeSconto = $cl->selectId(User::$dettagli["id_classe"]);
					
					if (!empty(User::$classeSconto) && User::$classeSconto["sconto"] > 0 && User::$classeSconto["sconto"] < 100)
					{
						$arraySconti[] = User::$classeSconto["sconto"];
						$arrayScontiDescrizione[] = "Classe di sconto ".User::$classeSconto["titolo"].", sconto ".User::$classeSconto["sconto"]."  %";
					}
				}
			}
			
			//sconto promozione
			if ($checkPromo && $p->inPromozione($clean["id_page"]))
			{
				$arraySconti[] = $page["prezzo_promozione"];
				$arrayScontiDescrizione[] = "Prodotto in promozione, sconto ".$page["prezzo_promozione"]." %";
			}
			
			//sconto scaglionamento
			if ((int)$qty > 1)
			{
				$scontoScaglionamento = $sc->getSconto($clean["id_page"], $qty);
				
				if ($scontoScaglionamento > 0)
				{
					$arraySconti[] = $scontoScaglionamento;
					$arrayScontiDescrizione[] = "Sconto quantit&agrave; ".$qty." pezzi, sconto: ".$scontoScaglionamento."  %";
				}
			}
			
			foreach ($arraySconti as $sconto)
			{
				$prezzoFinale = $prezzoFinale - ($prezzoFinale * $sconto/100);
			}
			
			$this->values["json_sconti"] = json_encode($arrayScontiDescrizione);
		}
		
		return $prezzoFinale;
	}
	
	public function checkQtaFull()
	{
		if (!v("attiva_giacenza"))
			return true;
		
		$prodotti = $this->getProdotti();
		
		foreach ($prodotti as $p)
		{
			$idCart = $p["cart"]["id_cart"];
			$qty = $p["cart"]["quantity"];
			
			if (!$this->checkQtaFinale($idCart, $qty))
				return false;
		}
		
		return true;
	}
	
	public function checkQta($id_c = 0, $qtyDaAggiungere = 0)
	{
		if (!v("attiva_giacenza"))
			return true;
		
		$clean["id_c"] = (int)$id_c;
		
		$c = new CombinazioniModel();
		$giacenza = $c->qta($clean["id_c"]);
		
		$qtaCarrello = $this->qta($clean["id_c"]);
		$qtaFinale = $qtyDaAggiungere + $qtaCarrello;
		
		if ($giacenza >= $qtaFinale)
			return true;
		
		return false;
	}
	
	public function checkQtaFinale($id_cart = 0, $qtaFinale = 0)
	{
		$cart = $this->getCart((int)$id_cart);
		
		if (!empty($cart))
			return $this->checkQta($cart["id_c"], ((int)$qtaFinale - $cart["quantity"]));
		
		return false;
	}
	
	public function qta($id_c = 0)
	{
		$clean["id_c"] = (int)$id_c;
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$res = $this->clear()->select("sum(quantity) as SOMMA")->where(array(
			"id_c"		=>	$clean["id_c"],
			"cart_uid"	=>	$clean["cart_uid"],
		))->send();
		
		if (count($res) > 0)
		{
			return $res[0]["aggregate"]["SOMMA"];
		}
		
		return 0;
	}
	
	public function add($id_page = 0, $quantity = 1, $id_c = 0, $id_p = 0, $jsonPers = array(), $prIntero = null, $prInteroIvato = null)
	{
		$clean["id_page"] = (int)$id_page;
		$clean["quantity"] = abs((int)$quantity);
		$clean["id_c"] = (int)$id_c;
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		$clean["id_p"] = (int)$id_p;
		
		$p = new PagesModel();
		$comb = new CombinazioniModel();
		$sc = new ScaglioniModel();
		$iva = new IvaModel();
		$pers = new PersonalizzazioniModel();
		
		if ($clean["quantity"] <= 0)
		{
			return false;
		}
		
		$rPage = $p->clear()->where(array("id_page"=>$clean["id_page"],"attivo"=>"Y"))->send();
		
		if (count($rPage) > 0)
		{
			$combinazioni = $comb->clear()->where(array("id_page"=>$clean["id_page"]))->toList("id_c")->send();
			
			$datiCombinazione = null;
			
			$this->clear()->where(array(
				"id_page"	=>	$clean["id_page"],
				"cart_uid"	=>	$clean["cart_uid"],
				"id_p"		=>	$clean["id_p"],
			));
			
			if (count($combinazioni) > 0)
			{
				if (!in_array($clean["id_c"],$combinazioni))
				{
					return false;
				}
				
				//estraggo tutti i dati della combinazione
				$datiCombinazione = $comb->clear()->where(array("id_c"=>$clean["id_c"]))->send();
				
				$this->aWhere(array("id_c"=>$clean["id_c"]));
			}

			$res = $this->send();
			
			$prodottoParent = $this->clear()->where(array(
				"id_cart"	=>	$clean["id_p"],
			))->record();
			
			// Controllo la quantità
			if (!$this->checkQta($clean["id_c"], $clean["quantity"]))
				return -1;
			
			if (count($res) > 0)
			{
				$this->values["quantity"] = (int)$res[0]["cart"]["quantity"]  + $clean["quantity"];
				
				$this->values["price"] = $this->calcolaPrezzoFinale($clean["id_page"], $res[0]["cart"]["prezzo_intero"], $this->values["quantity"], true, true);
				
				if (v("prezzi_ivati_in_prodotti"))
					$this->values["price_ivato"] = $this->calcolaPrezzoFinale($clean["id_page"], $res[0]["cart"]["prezzo_intero_ivato"], $this->values["quantity"], true, true);
				
				if (number_format($this->values["price"],2,".","") != number_format($res[0]["cart"]["prezzo_intero"],2,".",""))
					$this->values["in_promozione"] = "Y";
				
				// Recupero i valori della personalizzazione
				if ($clean["id_c"])
				{
					$attributiArray = array();
					
					$combAttr = $comb->getStringa($clean["id_c"]);
					if ($combAttr)
						$attributiArray[] = $combAttr;
					
					$persAttr = $pers->getStringa($jsonPers);
					if ($persAttr)
						$attributiArray[] = $persAttr;
					
					$this->values["attributi"] = implode("<br />",$attributiArray);
					$this->values["json_attributi"] = $comb->getStringa($clean["id_c"],"",true);
					$this->values["json_personalizzazioni"] = $pers->getStringa($jsonPers,"",true);
				}
				
				$this->sanitize();
				$this->update($res[0]["cart"]["id_cart"]);
				
				return $res[0]["cart"]["id_cart"];
			}
			else
			{
				if (isset($datiCombinazione))
				{
					$this->values["codice"] = $datiCombinazione[0]["combinazioni"]["codice"];
					$this->values["immagine"] = $datiCombinazione[0]["combinazioni"]["immagine"];
					$this->values["peso"] = $datiCombinazione[0]["combinazioni"]["peso"];
				}
				else
				{
					$this->values["codice"] = $rPage[0]["pages"]["codice"];
					$this->values["immagine"] = getFirstImage($clean["id_page"]);
					$this->values["peso"] = $rPage[0]["pages"]["peso"];
				}
				
				$this->values["id_iva"] = $rPage[0]["pages"]["id_iva"];
				$this->values["iva"] = $iva->getValore($rPage[0]["pages"]["id_iva"]);
				
				$this->values["title"] = $rPage[0]["pages"]["title"];
				
				$attributiArray = array();
				
				$combAttr = $comb->getStringa($clean["id_c"]);
				if ($combAttr)
					$attributiArray[] = $combAttr;
				
				$persAttr = $pers->getStringa($jsonPers);
				if ($persAttr)
					$attributiArray[] = $persAttr;
				
				$this->values["quantity"] = $clean["quantity"];
				$this->values["id_page"] = $clean["id_page"];
				$this->values["id_c"] = $clean["id_c"];
				$this->values["id_p"] = $clean["id_p"];
				$this->values["attributi"] = implode("<br />",$attributiArray);
				$this->values["json_attributi"] = $comb->getStringa($clean["id_c"],"",true);
				$this->values["json_personalizzazioni"] = $pers->getStringa($jsonPers,"",true);
				$this->values["cart_uid"] = $clean["cart_uid"];
				$this->values["creation_time"] = $this->getCreationTime();
				
				if ($p->inPromozioneTot($clean["id_page"]))
				{
					$this->values["in_promozione"] = "Y";
				}
				
				if ($clean["id_p"] && !empty($prodottoParent))
				{
					$this->values["id_order"] = $prodottoParent["id_order"];
				}
				
				if (isset($prIntero))
				{
					$prezzoIntero = $this->values["prezzo_intero"] = number_format($prIntero,v("cifre_decimali"),".","");
					
					if (isset($prInteroIvato))
						$prezzoInteroIvato = $this->values["prezzo_intero_ivato"] = number_format($prInteroIvato,2,".","");
					else
						$prezzoInteroIvato = $this->values["prezzo_intero_ivato"] = number_format($prezzoIntero * (1 + ($this->values["iva"] / 100)),2,".","");
				}
				else if (isset($datiCombinazione))
				{
					$prezzoIntero = $this->values["prezzo_intero"] = $datiCombinazione[0]["combinazioni"]["price"];
					
					if (v("prezzi_ivati_in_prodotti"))
						$prezzoInteroIvato = $this->values["prezzo_intero_ivato"] = $datiCombinazione[0]["combinazioni"]["price_ivato"];
					
					if (User::$nazione)
					{
						$prezzoIntero = $this->values["prezzo_intero"] = $comb->getPrezzoListino($datiCombinazione[0]["combinazioni"]["id_c"], User::$nazione, $prezzoIntero);
						
						if (v("prezzi_ivati_in_prodotti"))
							$prezzoInteroIvato = $this->values["prezzo_intero_ivato"] = $comb->getPrezzoListino($datiCombinazione[0]["combinazioni"]["id_c"], User::$nazione, $prezzoInteroIvato, "price_ivato");
					}
				}
				else
				{
					$prezzoIntero = $this->values["prezzo_intero"] = $rPage[0]["pages"]["price"];
					
					if (v("prezzi_ivati_in_prodotti"))
						$prezzoInteroIvato = $this->values["prezzo_intero_ivato"] = $rPage[0]["pages"]["price_ivato"];
				}
				
				//calcolo lo sconto dovuto allo scaglionamento
				$this->values["price"] = $this->calcolaPrezzoFinale($clean["id_page"], $prezzoIntero, $this->values["quantity"], true, true);
				
				if (v("prezzi_ivati_in_prodotti"))
					$this->values["price_ivato"] = $this->calcolaPrezzoFinale($clean["id_page"], $prezzoInteroIvato, $this->values["quantity"], true, true);
				
				if (number_format($this->values["price"],2,".","") != number_format($this->values["prezzo_intero"],2,".",""))
					$this->values["in_promozione"] = "Y";
				
				$this->sanitize();
				$this->insert();
				
				return $this->lastId();
			}
		}
		
		return 0;
	}
	
	public function getCreationTime()
	{
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$res = $this->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->send();
		
		if (count($res) > 0)
		{
			return $res[0]["cart"]["creation_time"];
		}
		
		return time();
	}
	
	public function recordExists($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$res = $this->clear()->where(array("id_page"=>$clean["id_page"],"cart_uid"=>$clean["cart_uid"]))->send();
		
		if (count($res) > 0)
		{
			return true;
		}
		return false;
	}
	
	//numero prodotti nel carrello
	public function numberOfItems()
	{
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$res = $this->clear()->select("sum(quantity) as q")->inner(array("pagina"))->where(array("cart_uid"=>$clean["cart_uid"]))->groupBy("cart_uid")->send();
		
		if (count($res) > 0)
		{
			return $res[0]["aggregate"]["q"];
		}

		return "0";
	}
	
	public function getAttributoDaCarrello($idCart, $col, $idAcc = null, $field = "json_attributi")
	{
		$clean["id_cart"] = (int)$idCart;
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$this->clear()->sWhere("(id_cart = " . $clean["id_cart"] . " OR id_p = " . $clean["id_cart"] . ") AND cart_uid = '" . $clean["cart_uid"] . "'");
		
		if ($idAcc)
			$this->aWhere(array(
				"id_page"	=>	(int)$idAcc,
				"id_p"		=>	$clean["id_cart"],
			));
		else
			$this->aWhere(array(
				"id_p"		=>	0,
			));
		
		$record = $this->record();
		
		if (!empty($record) && $record[$field])
		{
			$jsonAttributi = json_decode($record[$field], true);
			
			foreach ($jsonAttributi as $j)
			{
				if ($j["col"] == $col)
					return $j["val"];
			}
		}
		
		return "";
	}
	
	public function getCart($idCart)
	{
		$clean["id_cart"] = (int)$idCart;
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		return $this->clear()->where(array("id_cart"=>$clean["id_cart"],"cart_uid"=>$clean["cart_uid"]))->record();
	}
	
	public function getQtaDaCarrello($idCart)
	{
		$clean["id_cart"] = (int)$idCart;
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		return (int)$this->clear()->select("quantity")->where(array("id_cart"=>$clean["id_cart"],"cart_uid"=>$clean["cart_uid"]))->field("quantity");
	}
	
	public function getAccessoriDaCarrello($idCart)
	{
		$clean["id_cart"] = (int)$idCart;
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		return $this->clear()->select("id_page")->sWhere("id_p = " . $clean["id_cart"] . " AND cart_uid = '" . $clean["cart_uid"] . "'")->toList("id_page")->send();
	}
	
	public function accessorioInCarrello($idCart, $idAcc)
	{
		$accessori = $this->getAccessoriDaCarrello($idCart);
		
		return in_array($idAcc, $accessori) ? true : false;
	}
	
	public function idCarrelloEsistente($idCart)
	{
		$clean["id_cart"] = (int)$idCart;
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		return $this->clear()->where(array("id_cart"=>$clean["id_cart"],"cart_uid"=>$clean["cart_uid"]))->rowNumber();
	}
	
	public function getProdotti()
	{
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		return $this->clear()->select("cart.*,pages.*,contenuti_tradotti.*")->inner("pages")->using("id_page")
			->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")
			->where(array("cart_uid"=>$clean["cart_uid"]))->orderBy("cart.id_order ASC, id_cart ASC")->send();
	}
	
	public function insert()
	{
		if (isset($this->values["json_personalizzazioni"]) && !trim($this->values["json_personalizzazioni"]))
			$this->values["json_personalizzazioni"] = "[]";
		
		if (isset(Params::$lang))
			$this->values["lingua"] = Params::$lang;
		
		return parent::insert();
	}
	
	public function titolocompleto($record)
	{
		$titolo = $record["cart"]["title"];
		
		if ($record["cart"]["attributi"])
			$titolo .= "<br />".$record["cart"]["attributi"];
		
		return $titolo;
	}
	
	public function thumb($record)
	{
		if ($record["cart"]["immagine"])
			return "<img width='70px' src='".Url::getFileRoot()."thumb/contenuto/".$record["cart"]["immagine"]."' />";
		
		return "";
	}
	
	public function datiutente($record)
	{
		if ($record["cart"]["email"])
		{
			$html = $record["cart"]["email"];
			
			if ($record["regusers"]["username"])
				$html = "<a class='iframe' href='".Url::getRoot()."regusers/form/update/".$record["regusers"]["id_user"]."?partial=Y&nobuttons=Y'>$html</a>";
			
			return $html;
		}
		
		return "";
	}
	
	// Recupera dati nel carrello da cliente loggato
	public function collegaDatiCliente($prodottiInCart = array())
	{
		if( !session_id() )
			session_start();
		
		$clean["email"] = null;
		
		if (v("recupera_dati_carrello_da_post"))
		{
			if (isset($_SESSION["email_carrello"]) && checkMail($_SESSION["email_carrello"]))
				$clean["email"] = $_SESSION["email_carrello"];
			else
			{
				foreach ($prodottiInCart as $p)
				{
					if ($p["cart"]["email"] && checkMail($p["cart"]["email"]))
					{
						$clean["email"] = $_SESSION["email_carrello"] = $p["cart"]["email"];
						break;
					}
				}
			}
		}
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		if (User::$logged)
		{
			$numeroDaCollegare = $this->clear()->where(array(
				"OR"	=>	array(
					"id_user"	=>	0,
					"email"		=>	"",
				),
				"cart_uid"	=>	$clean["cart_uid"],
			))->rowNumber();
			
			if ($numeroDaCollegare > 0)
			{
				$this->setValues(array(
					"id_user"		=>	User::$id,
					"email"			=>	User::$dettagli["username"],
					"creation_time"	=>	time(),
				));
				
				$this->update(null, "cart_uid = '".$clean["cart_uid"]."'");
			}
		}
		else if (v("recupera_dati_carrello_da_post"))
		{
			if ($clean["email"] && checkMail($clean["email"]))
			{
				$numeroDaCollegare = $this->clear()->where(array(
					"email"		=>	"",
					"cart_uid"	=>	$clean["cart_uid"],
				))->rowNumber();
				
				if ($numeroDaCollegare > 0)
				{
					$this->setValues(array(
						"email"			=>	$clean["email"],
						"creation_time"	=>	time(),
					));
					
					$this->update(null, "cart_uid = '".$clean["cart_uid"]."'");
				}
			}
		}
	}
}
