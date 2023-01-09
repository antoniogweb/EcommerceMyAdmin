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

class CartModel extends GenericModel {
	
	public static $ordinamento = 0;
	public static $deletedExpired = false;
	public static $checkCart = false;
	public static $cartRows = null;
	
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
			'elementi' => array("HAS_MANY", 'CartelementiModel', 'id_cart', null, "CASCADE"),
			'pagina' => array("BELONGS_TO", 'PagineModel', 'id_page',null,"CASCADE","Si prega di selezionare la pagina"),
        );
    }
    
	public function deleteExpired()
	{
		if (!self::$deletedExpired)
		{
			$limit = time() - Parametri::$durataCarrello;
			
			$this->del(null, array(
				'creation_time < ?',
				array(
					$limit,
				)
			));
			
			$this->notice = "";
			$this->queryResult = false;
			
			self::$deletedExpired = true;
		}
	}
	
	public function checkCart()
	{
		if (!self::$checkCart)
		{
			$daEliminare = $this->db->query("SELECT id_cart,combinazioni.id_c FROM `cart` left join combinazioni on cart.id_c = combinazioni.id_c and combinazioni.acquistabile = 1 where combinazioni.id_c is null");
			
			if (count($daEliminare) > 0)
			{
				$daEliminare = $this->getList($daEliminare, "cart.id_cart");
// 				$daEliminareWhere = implode(",", $daEliminare);
				
				$this->db->query(array("delete from cart where id_cart in (".$this->placeholdersFromArray($daEliminare).")",$daEliminare));
// 				$this->db->query("delete from cart where id_cart in ($daEliminareWhere)");
			}
			
			self::$checkCart = true;
		}
	}
	
	// Totale scontato
	public function totaleScontato($conSpedizione = false, $pieno = false)
	{
// 		IvaModel::getAliquotaEstera();
		
		$cifre = v("cifre_decimali");
		
		if (!$pieno && hasActiveCoupon())
		{
			$p = new PromozioniModel();
			
			$coupon = $p->getCoupon(User::$coupon);
			
			$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
			
			$res = $this->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->send();
			
			$total = 0;
			$totaleIvato = 0;
			
			if (count($res) > 0)
			{
				foreach ($res as $r)
				{
					$prezzo = number_format($r["cart"]["price"],$cifre,".","");
					
// 					if ($coupon["tipo_sconto"] == "PERCENTUALE" && in_array($r["cart"]["id_page"], User::$prodottiInCoupon))
					if ($coupon["tipo_sconto"] == "PERCENTUALE" && PromozioniModel::checkProdottoInPromo($r["cart"]["id_page"]))
						$prezzo = number_format($prezzo - $prezzo*($coupon["sconto"]/100),$cifre,".","");
					
					$total = $total + number_format($prezzo * $r["cart"]["quantity"],$cifre,".","");
					
					$ivaRiga = $this->gIvaRiga($r);
					
					$totaleIvato = $totaleIvato + number_format($prezzo * $r["cart"]["quantity"] * (1 + ($ivaRiga / 100)),$cifre,".","");
				}
			}
			
			if ($conSpedizione)
			{
				$totaleSpedizione = number_format(getSpedizioneN(), $cifre,".","");
				$totalePagamento = number_format(getPagamentoN(), $cifre,".","");
				
				$total += ($totaleSpedizione + $totalePagamento);
				
				$ivaSped = number_format(self::getAliquotaIvaSpedizione(),2,".","");
				
				$totaleIvato = $totaleIvato + number_format(($totaleSpedizione + $totalePagamento) * (1 + ($ivaSped / 100)),$cifre,".","");
			}
			
			// Coupon assoluto
			if ($coupon["tipo_sconto"] == "ASSOLUTO")
			{
				$numeroEuroRimasti = PromozioniModel::gNumeroEuroRimasti($coupon["id_p"]);
				
				if ($numeroEuroRimasti > 0)
				{
					$valoreSconto = ($numeroEuroRimasti >= $totaleIvato) ? $totaleIvato : $numeroEuroRimasti;
					
					$ivaSped = number_format(self::getAliquotaIvaSpedizione(),2,".","");
					
					$total = $total - number_format($valoreSconto / (1 + ($ivaSped/100)),$cifre,".","");
				}
			}
			
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
		{
			$total += number_format(getSpedizioneN(true), $cifre,".","");
			$total += number_format(getPagamentoN(true), $cifre,".","");
		}
		
		return $total;
	}
	
	public static function getAliquotaIvaSpedizione()
	{
// 		IvaModel::getAliquotaEstera();
		
		if (IvaModel::getIvaSpedizione("valore"))
			$ivaSped = IvaModel::getIvaSpedizione("valore");
		else
			$ivaSped = self::getMaxIva();
		
		// Controllo l'aliquota estera
		if (isset(IvaModel::$aliquotaEstera))
			$ivaSped = IvaModel::$aliquotaEstera;
		
		return $ivaSped;
	}
	
	public function gIvaRiga($r)
	{
		$ivaRiga = $r["cart"]["iva"];
		
		// Controllo l'aliquota estera
		if (isset(IvaModel::$aliquotaEstera))
			$ivaRiga = IvaModel::$aliquotaEstera;
		
		return number_format($ivaRiga,2,".","");
	}
	
	// Totale iva dal carrello
	public function iva($conSpedizione = true, $pieno = false)
	{
		$cifre = v("cifre_decimali");
		
// 		IvaModel::getAliquotaEstera();
		
		$sconto = 0;
		$tipoSconto = "PERCENTUALE";
		$idPromo = 0;
		if (!$pieno && hasActiveCoupon())
		{
			$p = new PromozioniModel();
			$coupon = $p->getCoupon(User::$coupon);
			
			$tipoSconto = $coupon["tipo_sconto"];
			$sconto = $coupon["sconto"];
			$idPromo = $coupon["id_p"];
		}
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$res = $this->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->send();
		
		$total = 0;
		
		$trovata = false;
		
		$arraySubtotale = $arrayIva = array();
		
		$totaleIvato = 0;
		
		if (count($res) > 0)
		{
			foreach ($res as $r)
			{
				$prezzo = number_format($r["cart"]["price"],$cifre,".","");
				
// 				if ($tipoSconto == "PERCENTUALE" && in_array($r["cart"]["id_page"], User::$prodottiInCoupon))
				if ($tipoSconto == "PERCENTUALE" && PromozioniModel::checkProdottoInPromo($r["cart"]["id_page"]))
				{
					$prezzo = number_format($prezzo - $prezzo*($sconto/100),$cifre,".","");
				}
				
				$subtotale = number_format($prezzo * $r["cart"]["quantity"],$cifre,".","");
				
				$ivaRiga = $this->gIvaRiga($r);
// 				$ivaRiga = $r["cart"]["iva"];
// 				
// 				// Controllo l'aliquota estera
// 				if (isset(IvaModel::$aliquotaEstera))
// 					$ivaRiga = IvaModel::$aliquotaEstera;
// 				
// 				$ivaRiga = number_format($ivaRiga,2,".","");
				
				if (isset($arraySubtotale[$ivaRiga]))
					$arraySubtotale[$ivaRiga] += $subtotale;
				else
					$arraySubtotale[$ivaRiga] = $subtotale;
				
				$iva = $subtotale*($ivaRiga/100);
				$total += number_format($iva,$cifre,".","");
				
				$totaleIvato += $subtotale * (1 + ($ivaRiga / 100));
			}
		}
		
		if ($conSpedizione)
		{
			$ivaSped = self::getAliquotaIvaSpedizione();
			
			$ivaSped = number_format($ivaSped,2,".","");
			
			$pienoSpedizione = $pieno ? true : null;
			
			$totaleSpedizione = number_format(getSpedizioneN($pienoSpedizione),$cifre,".","");
			$totalePagamento = number_format(getPagamentoN(),$cifre,".","");
			
			if (isset($arraySubtotale[$ivaSped]))
				$arraySubtotale[$ivaSped] += ($totaleSpedizione + $totalePagamento);
			else
				$arraySubtotale[$ivaSped] = ($totaleSpedizione + $totalePagamento);
			
			$totaleIvato += ($totaleSpedizione + $totalePagamento) * (1 + ($ivaSped / 100));
		}
		
		// Sconto assoluto
		if ($sconto > 0 && $tipoSconto == "ASSOLUTO")
		{
			$numeroEuroRimasti = PromozioniModel::gNumeroEuroRimasti($idPromo);
			
			if ($numeroEuroRimasti > 0)
			{
				$valoreSconto = ($numeroEuroRimasti >= $totaleIvato) ? $totaleIvato : $numeroEuroRimasti;
				
				$ivaSped = number_format(self::getAliquotaIvaSpedizione(),2,".","");
				
				if (isset($arraySubtotale[$ivaSped]))
					$arraySubtotale[$ivaSped] -= number_format($valoreSconto / (1 + ($ivaSped/100)),$cifre,".","");
				else
					$arraySubtotale[$ivaSped] = (-1)*number_format($valoreSconto / (1 + ($ivaSped/100)),$cifre,".","");
			}
		}
		
// 		print_r($arraySubtotale);
		
		foreach ($arraySubtotale as $iva => $sub)
		{
			$arrayIva[$iva] = number_format($sub*$iva/100,$cifre,".","");
		}
		
// 		if (isset($_GET["dev"]))
// 		echo "NUMERO ".count($arrayIva)."<br />"; print_r($arrayIva);
		
		$total = array_sum($arrayIva);
		
		return $total;
	}
	
	public static function getIdIvaSpedizione()
	{
		if (IvaModel::getIvaSpedizione("id_iva"))
			$idIvaSped = IvaModel::getIvaSpedizione("id_iva");
		else
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
		
// 		$this->del(null, "cart_uid = '" . $clean["cart_uid"] . "'");
		$this->del(null, array(
			"cart_uid"	=>	$clean["cart_uid"],
		));
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
			if ($r["price_ivato"] <= 0)
				continue;
			
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
				
				$this->values["price"] = $this->calcolaPrezzoFinale($cart["id_page"], $cart["prezzo_intero"], $clean["quantity"], true, true, $cart["id_c"]);
				
				if (v("prezzi_ivati_in_prodotti"))
					$this->values["price_ivato"] = $this->calcolaPrezzoFinale($cart["id_page"], $cart["prezzo_intero_ivato"], $clean["quantity"], true, true, $cart["id_c"]);
				
				if (number_format($this->values["price"],2,".","") != number_format($cart["prezzo_intero"],2,".",""))
					$this->values["in_promozione"] = "Y";
				else
					$this->values["in_promozione"] = "N";
				
				$this->sanitize();
				
				$this->update(null, array(
					"id_cart"	=>	$clean["id_cart"],
					"cart_uid"	=>	$clean["cart_uid"],
				));
				
// 				$this->update(null, "id_cart = " . $clean["id_cart"] . " AND cart_uid = '" . $clean["cart_uid"] . "'");
			}
		}
	}
	
	public function delete($id_cart)
	{
		$clean["id_cart"] = (int)$id_cart;
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		return $this->del(null, array("(id_cart = ? OR id_p = ?) AND cart_uid = ?", array(
			$clean["id_cart"],
			$clean["id_cart"],
			$clean["cart_uid"]
		)));
		
// 		return $this->del(null, "(id_cart = " . $clean["id_cart"] . " OR id_p = " . $clean["id_cart"] . ") AND cart_uid = '" . $clean["cart_uid"] . "'");
	}
	
	public function calcolaPrezzoFinale($idPage, $prezzoIntero, $qty = 1, $checkPromo = true, $checkUser = true, $idC = 0)
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
				$scontoPromo = PagesModel::getPercSconto($page, $idC);
// 				if ($page["tipo_sconto"] == "PERCENTUALE")
// 					$arraySconti[] = $page["prezzo_promozione"];
// 				else if ($page["price"] > 0)
// 					$arraySconti[] = (($page["price"] - $page["prezzo_promozione_ass"]) / $page["price"]) * 100;
				
				$arraySconti[] = $scontoPromo;
				
				$arrayScontiDescrizione[] = "Prodotto in promozione, sconto ".$scontoPromo." %";
			}
			
			//sconto scaglionamento
			if ((int)$qty >= 1)
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
		
		if (v("attiva_gift_card"))
		{
			$combinazione = $c->selectId($clean["id_c"]);
			
			if (!empty($combinazione) && ProdottiModel::isGiftCart($combinazione["id_page"]))
				return true;
		}
		
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
	
	public function add($id_page = 0, $quantity = 1, $id_c = 0, $id_p = 0, $jsonPers = array(), $prIntero = null, $prInteroIvato = null, $prScontato = null, $idRif = null)
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
				$this->values["quantity"] = (int)$res[0]["cart"]["quantity"] + $clean["quantity"];
				
				$this->values["price"] = $this->calcolaPrezzoFinale($clean["id_page"], $res[0]["cart"]["prezzo_intero"], $this->values["quantity"], true, true, $res[0]["cart"]["id_c"]);
				
				if (v("prezzi_ivati_in_prodotti"))
					$this->values["price_ivato"] = $this->calcolaPrezzoFinale($clean["id_page"], $res[0]["cart"]["prezzo_intero_ivato"], $this->values["quantity"], true, true, $res[0]["cart"]["id_c"]);
				
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
				$idCombinazione = null;
				
				if (isset($datiCombinazione))
				{
					$this->values["codice"] = $datiCombinazione[0]["combinazioni"]["codice"];
					
					$this->values["immagine"] = ProdottiModel::immagineCarrello($clean["id_page"], $datiCombinazione[0]["combinazioni"]["id_c"], $datiCombinazione[0]["combinazioni"]["immagine"]);
					
					$this->values["peso"] = $datiCombinazione[0]["combinazioni"]["peso"];
					
					$idCombinazione = $datiCombinazione[0]["combinazioni"]["id_c"];
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
				$this->values["gift_card"] = $rPage[0]["pages"]["gift_card"];
				
				if (isset($idRif))
					$this->values["id_rif"] = (int)$idRif;
				
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
				
				// prezzo scontato
// 				if (isset($prScontato))
// 					$this->values["price"] = number_format($prScontato,v("cifre_decimali"),".","");
// 				else
					$this->values["price"] = $this->calcolaPrezzoFinale($clean["id_page"], $prezzoIntero, $this->values["quantity"], true, true, $idCombinazione);
				
				if (v("prezzi_ivati_in_prodotti"))
					$this->values["price_ivato"] = $this->calcolaPrezzoFinale($clean["id_page"], $prezzoInteroIvato, $this->values["quantity"], true, true, $idCombinazione);
				
				if (number_format($this->values["price"],2,".","") != number_format($this->values["prezzo_intero"],2,".",""))
					$this->values["in_promozione"] = "Y";
				else
					$this->values["in_promozione"] = "N";
				
// 				echo number_format($this->values["price"],2,".","")." ".number_format($this->values["prezzo_intero"],2,".","");
// 				
// 				echo $this->values["in_promozione"];
// 				die();
				
				$this->sanitize();
				$this->insert();
				
				return $this->lastId();
			}
		}
		
		return 0;
	}
	
	// Restituisce le righe del proprio carrello
	public function getRigheCart($sWhere = "")
	{
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$c = new CartModel();
		
		$res = $c->clear()->where(array(
			"cart_uid"	=>	$clean["cart_uid"],
		));
		
		if ($sWhere)
			$c->sWhere($sWhere);
		
		return $c->send(false);
	}
	
	public function aggiornaElementi($elementiPost = array())
	{
		if (!v("attiva_gift_card"))
			return;
		
		if (v("usa_transactions"))
			$this->db->beginTransaction();
		
		$ce = new CartelementiModel();
		
		$righeCarrello = $this->getRigheCart("gift_card = 1");
		
		foreach ($righeCarrello as $riga)
		{
			if ($riga["gift_card"])
			{
// 				$numeroElementiCarrello = $ce->clear()->where(array(
// 					"id_cart"	=>	(int)$riga["id_cart"],
// 				))->rowNumber();
				
				$elementiRigaCarrello = CartelementiModel::getElementiCarrello((int)$riga["id_cart"]);
				
				if ((int)count($elementiRigaCarrello) !== (int)$riga["quantity"] || count($elementiPost) > 0)
				{
					$ce->del(null, array(
						"id_cart"	=>	(int)$riga["id_cart"]
					));
					
					for ($i = 0; $i < $riga["quantity"]; $i++)
					{
						$email = $testo = "";
						
						if (count($elementiPost) > 0)
						{
							$email = isset($elementiPost["CART-".$riga["id_cart"]][$i]["email"]) ? $elementiPost["CART-".$riga["id_cart"]][$i]["email"] : "";
							$testo = isset($elementiPost["CART-".$riga["id_cart"]][$i]["testo"]) ? $elementiPost["CART-".$riga["id_cart"]][$i]["testo"] : "";
						}
						else if (isset($elementiRigaCarrello[$i]))
						{
							$email = htmlentitydecode($elementiRigaCarrello[$i]["email"]);
							$testo = htmlentitydecode($elementiRigaCarrello[$i]["testo"]);
						}
						
						$ce->sValues(array(
							"email"		=>	trim($email),
							"testo"		=>	$testo,
							"id_cart"	=>	$riga["id_cart"],
						));
						
						$ce->insert();
					}
				}
			}
		}
		
		if (v("usa_transactions"))
			$this->db->commit();
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
		
// 		$this->clear()->sWhere("(id_cart = " . $clean["id_cart"] . " OR id_p = " . $clean["id_cart"] . ") AND cart_uid = '" . $clean["cart_uid"] . "'");
		
		$this->clear()->sWhere(array("(id_cart = ? OR id_p = ?) AND cart_uid = ?",array($clean["id_cart"], $clean["id_cart"], $clean["cart_uid"])));
		
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
		
		return $this->clear()->select("id_page")->sWhere(array("id_p = ? AND cart_uid = ?",array($clean["id_cart"], $clean["cart_uid"])))->toList("id_page")->send();
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
			->addJoinTraduzione(null, "contenuti_tradotti", false, (new PagesModel()))
			->where(array("cart_uid"=>$clean["cart_uid"]))->orderBy("cart.id_order ASC,id_cart ASC")->send();
	}
	
	public function insert()
	{
		if (isset($this->values["json_personalizzazioni"]) && !trim($this->values["json_personalizzazioni"]))
			$this->values["json_personalizzazioni"] = "[]";
		
		if (isset(Params::$lang))
			$this->values["lingua"] = Params::$lang;
		
		return parent::insert();
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
				
				$this->update(null, array(
					"cart_uid"	=>	$clean["cart_uid"],
				));
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
					
					$this->update(null, array(
						"cart_uid"	=>	$clean["cart_uid"],
					));
				}
			}
		}
	}
	
	public static function attivaDisattivaSpedizione($idOrdine = null)
	{
		if (CartModel::soloProdottiSenzaSpedizione($idOrdine))
			VariabiliModel::$valori["attiva_spedizione"] = 0;
	}
	
	// restituisce true se il carrello ha solo prodotti senza spedizione
	public static function soloProdottiSenzaSpedizione($idOrdine = null, $checkLista = true, $checkGiftCard = true)
	{
		if (v("attiva_liste_regalo") && $checkLista)
		{
			if (isset($idOrdine))
			{
				$idLista = OrdiniModel::g()->whereId((int)$idOrdine)->field("id_lista_regalo");
				
				if ($idLista)
					return true;
			}
			
			ListeregaloModel::getCookieIdLista();
			
			if (User::$idLista)
				return true;
		}
		
		if (v("attiva_gift_card") && $checkGiftCard)
		{
			$c = new CartModel();
			
			if ($c->numberOfItems() > 0)
			{
				$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
				
				$numeroNoGiftCard = $c->clear()->where(array(
					"cart_uid"	=>	$clean["cart_uid"],
					"gift_card"	=>	0,
				))->rowNumber();
				
				if ((int)$numeroNoGiftCard === 0)
					return true;
			}
		}
		
		return false;
	}
	
	public static function numeroGifCartInCarrello($idCart = 0)
	{
		if (!v("attiva_gift_card"))
			return 0;
		
		$c = new CartModel();
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$c->clear()->select("sum(quantity) as SOMMA")->where(array(
			"cart_uid"	=>	$clean["cart_uid"],
			"gift_card"	=>	1,
		));
		
		if ($idCart)
			$c->aWhere(array(
				"id_cart"	=>	(int)$idCart,
			));
		
		$res = $c->send();
		
		if (count($res) > 0)
			return $res[0]["aggregate"]["SOMMA"];
		
		return 0;
	}
	
	public function getRighePerOrdine()
	{
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		return  $this->clear()->select("cart.*,pages.id_page")->inner("pages")->using("id_page")->where(array("cart_uid"=>$clean["cart_uid"]))->orderBy("id_cart ASC")->send();
	}
}
