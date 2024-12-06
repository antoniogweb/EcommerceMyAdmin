<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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
	public static $cartellaCartUid = "CartUids"; // Cartella dove vengono salvati i file dei cart_uid
	
	public static $cifreCalcolo = 16;
	
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
	
	public static function skipCheckCart()
	{
		self::$checkCart = true;
	}
	
	public function checkCart()
	{
		if (!self::$checkCart)
		{
			if (isset(User::$cart_uid))
				$daEliminare = $this->query(array("SELECT id_cart,combinazioni.id_c FROM `cart` left join combinazioni on cart.id_c = combinazioni.id_c and combinazioni.acquistabile = 1 where combinazioni.id_c is null and cart.cart_uid = ?", array(sanitizeAll(User::$cart_uid))));
			else
				$daEliminare = $this->db->query("SELECT id_cart,combinazioni.id_c FROM `cart` left join combinazioni on cart.id_c = combinazioni.id_c and combinazioni.acquistabile = 1 where combinazioni.id_c is null");
			
			if (count($daEliminare) > 0)
			{
				$daEliminare = $this->getList($daEliminare, "cart.id_cart");
				
				$this->query(array("delete from cart where id_cart in (".$this->placeholdersFromArray($daEliminare).")",$daEliminare));
			}
			
			self::$checkCart = true;
		}
	}
	
	public static function getCifreCalcolo()
	{
		return v("prezzi_ivati_in_carrello") ? self::$cifreCalcolo : v("cifre_decimali");
	}
	
	// Totale scontato
	public function totaleScontato($conSpedizione = false, $pieno = false, $conCrediti = true, $conCouponAssoluto = true)
	{
// 		IvaModel::getAliquotaEstera();
		
// 		$cifre = v("cifre_decimali");
		$cifre = self::getCifreCalcolo();
		
		if (!$pieno && (hasActiveCoupon() || (v("attiva_crediti") && CreditiModel::gNumeroEuroRimasti(User::$id) > 0)))
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
					$prezzoFisso = number_format($r["cart"]["prezzo_fisso"],$cifre,".","");
					
// 					if ($coupon["tipo_sconto"] == "PERCENTUALE" && in_array($r["cart"]["id_page"], User::$prodottiInCoupon))
					if (!$r["cart"]["id_riga_tipologia"] && !empty($coupon) && $coupon["tipo_sconto"] == "PERCENTUALE" && PromozioniModel::checkProdottoInPromo($r["cart"]["id_page"]))
					{
						$prezzo = number_format($prezzo - $prezzo*($coupon["sconto"]/100),$cifre,".","");
						$prezzoFisso = number_format($prezzoFisso - $prezzoFisso*($coupon["sconto"]/100),$cifre,".","");
					}
					
					$total = $total + $prezzoFisso + number_format($prezzo * $r["cart"]["quantity"],$cifre,".","");
					
					$ivaRiga = $this->gIvaRiga($r);
					
					$totaleIvato = $totaleIvato + number_format($prezzoFisso * (1 + ($ivaRiga / 100)),$cifre,".","") + number_format($prezzo * $r["cart"]["quantity"] * (1 + ($ivaRiga / 100)),$cifre,".","");
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
			
			// CREDITI
			if ($conCrediti && v("attiva_crediti") && User::$id)
			{
				$numeroEuroRimasti = CreditiModel::gNumeroEuroRimasti(User::$id);
				
				if ($numeroEuroRimasti > 0)
				{
					$valoreSconto = ($numeroEuroRimasti >= $totaleIvato) ? $totaleIvato : $numeroEuroRimasti;
					
					$ivaSped = number_format(self::getAliquotaIvaSpedizione(),2,".","");
					
					$total = $total - number_format($valoreSconto / (1 + ($ivaSped/100)),$cifre,".","");
					
					$totaleIvato = $totaleIvato - number_format($valoreSconto,$cifre,".","");
				}
			}
			
			// Coupon assoluto
			if ($conCouponAssoluto && !empty($coupon) && $coupon["tipo_sconto"] == "ASSOLUTO")
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
		
// 		$cifre = v("cifre_decimali");
		$cifre = self::getCifreCalcolo();
		
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
				$prezzoFisso = number_format($r["cart"]["prezzo_fisso"],$cifre,".","");
				
				$total = $total + $prezzoFisso + number_format($prezzo * $r["cart"]["quantity"],$cifre,".","");
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
	public function iva($conSpedizione = true, $pieno = false, $conCrediti = true, $conCouponAssoluto = true)
	{
// 		$cifre = v("cifre_decimali");
		$cifre = self::getCifreCalcolo();
		
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
				$prezzoFisso = number_format($r["cart"]["prezzo_fisso"],$cifre,".","");
				
// 				if ($tipoSconto == "PERCENTUALE" && in_array($r["cart"]["id_page"], User::$prodottiInCoupon))
				if (!$r["cart"]["id_riga_tipologia"] && $tipoSconto == "PERCENTUALE" && PromozioniModel::checkProdottoInPromo($r["cart"]["id_page"]))
				{
					$prezzo = number_format($prezzo - $prezzo*($sconto/100),$cifre,".","");
					$prezzoFisso = number_format($prezzoFisso - $prezzoFisso*($sconto/100),$cifre,".","");
				}
				
				$subtotale = $prezzoFisso + number_format($prezzo * $r["cart"]["quantity"],$cifre,".","");
				
				$ivaRiga = $this->gIvaRiga($r);
				
				if (isset($arraySubtotale[$ivaRiga]))
					$arraySubtotale[$ivaRiga] += $subtotale;
				else
					$arraySubtotale[$ivaRiga] = $subtotale;
				
				$iva = $subtotale*($ivaRiga/100);
// 				$total += number_format($iva,$cifre,".","");
				
				$totaleIvato += $subtotale * (1 + ($ivaRiga / 100));
			}
		}
		
		// SPEDIZIONE
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
		
		// CREDITI
		if (v("attiva_crediti") && !$pieno && $conCrediti && User::$id)
		{
			$numeroEuroRimasti = CreditiModel::gNumeroEuroRimasti(User::$id);
			
			if ($numeroEuroRimasti > 0)
			{
				$valoreSconto = ($numeroEuroRimasti >= $totaleIvato) ? $totaleIvato : $numeroEuroRimasti;
				
				$ivaSped = number_format(self::getAliquotaIvaSpedizione(),2,".","");
				
				if (isset($arraySubtotale[$ivaSped]))
					$arraySubtotale[$ivaSped] -= number_format($valoreSconto / (1 + ($ivaSped/100)),$cifre,".","");
				else
					$arraySubtotale[$ivaSped] = (-1)*number_format($valoreSconto / (1 + ($ivaSped/100)),$cifre,".","");
					
				$totaleIvato -= $valoreSconto;
			}
		}
		
		// COUPON ASSOLUTO
		if ($sconto > 0 && $tipoSconto == "ASSOLUTO" && $conCouponAssoluto)
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
	
	// Restituisce l'aliquota iva minima nel carrello
	public static function getMaxIva($field = "iva")
	{
		$c = new CartModel();
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$record = $c->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->orderBy("iva")->limit(1)->record();
		
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
	
	public static function scorporaIvaPrezzoEstero($ordine = null)
	{
		if (!isset($ordine))
			$ordine = $_POST;
		
		if (v("scorpora_iva_prezzo_estero") || (v("scorpora_iva_prezzo_estero_azienda") && isset($ordine["tipo_cliente"]) && $ordine["tipo_cliente"] != "privato"))
			return true;
		
		return false;
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
// 			if ($r["price_ivato"] <= 0)
// 				continue;
			
			$aliquota = isset(IvaModel::$aliquotaEstera) ? IvaModel::$aliquotaEstera : $r["iva"];
			
			// if (v("scorpora_iva_prezzo_estero"))
			if (self::scorporaIvaPrezzoEstero())
			{
				$nuovoPrezzoUnitarioIvato = number_format(($r["price_ivato"] / (1 + ($r["iva"] / 100))) * (1 + ($aliquota / 100)), 2, ".", "");
				$nuovoPrezzoUnitarioInteroIvato = number_format(($r["prezzo_intero_ivato"] / (1 + ($r["iva"] / 100))) * (1 + ($aliquota / 100)), 2, ".", "");
			}
			else
			{
				$nuovoPrezzoUnitarioIvato = $r["price_ivato"];
				$nuovoPrezzoUnitarioInteroIvato = $r["prezzo_intero_ivato"];
			}
			
			$nuovoPrezzoUnitario = number_format($nuovoPrezzoUnitarioIvato / (1 + ($aliquota / 100)), v("cifre_decimali"), ".", "");
			$nuovoPrezzoUnitarioIntero = number_format($nuovoPrezzoUnitarioInteroIvato / (1 + ($aliquota / 100)), v("cifre_decimali"), ".", "");
			
// 			if ($r["prezzo_intero_ivato"] > 0)
// 				$rapporto = $r["price_ivato"] / $r["prezzo_intero_ivato"];
// 			else
// 				$rapporto = 1;
// 			
// 			$nuovoPrezzoUnitarioIntero = number_format($nuovoPrezzoUnitario / $rapporto, v("cifre_decimali"), ".", "");
			
// 			echo $nuovoPrezzoUnitarioIntero."-".$r["prezzo_intero"]."<br />";
			
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
	
	public function set($id_cart, $quantity, $forzaValori = array())
	{
		$clean["id_cart"] = (int)$id_cart;
		$clean["quantity"] = abs((int)$quantity);
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		if ($clean["quantity"] === 0)
		{
			
			return $this->delete($clean["id_cart"]);
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
				
				// prezzo
				$this->values["price"] = $this->calcolaPrezzoFinale($cart["id_page"], $cart["prezzo_intero"], $clean["quantity"], true, true, $cart["id_c"]);
				
				if (v("prezzi_ivati_in_prodotti"))
					$this->values["price_ivato"] = $this->calcolaPrezzoFinale($cart["id_page"], $cart["prezzo_intero_ivato"], $clean["quantity"], true, true, $cart["id_c"]);
				
				// prezzo fisso
				$this->values["prezzo_fisso"] = $this->calcolaPrezzoFinale($cart["id_page"], $cart["prezzo_fisso_intero"], 1, true, true, $cart["id_c"]);
				
				if (v("prezzi_ivati_in_prodotti"))
					$this->values["prezzo_fisso_ivato"] = $this->calcolaPrezzoFinale($cart["id_page"], $cart["prezzo_fisso_intero_ivato"], 1, true, true, $cart["id_c"]);
				
				if (number_format($this->values["price"],2,".","") != number_format($cart["prezzo_intero"],2,".",""))
					$this->values["in_promozione"] = "Y";
				else
					$this->values["in_promozione"] = "N";
				
				$this->setForzaValori($forzaValori);
				
				$this->sanitize();
				
				return $this->update(null, array(
					"id_cart"	=>	$clean["id_cart"],
					"cart_uid"	=>	$clean["cart_uid"],
				));
				
// 				$this->update(null, "id_cart = " . $clean["id_cart"] . " AND cart_uid = '" . $clean["cart_uid"] . "'");
			}
		}
		
		return false;
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
		
		if (isset(PagesModel::$preloadedPages[$clean["id_page"]]))
			$page = PagesModel::$preloadedPages[$clean["id_page"]]["pages"];
		else
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
	
	public function checkQta($id_c = 0, $qtyDaAggiungere = 0, $forza = false)
	{
		if (!v("attiva_giacenza") && !$forza)
			return true;
		
		$clean["id_c"] = (int)$id_c;
		
		$c = new CombinazioniModel();
		
		if (v("attiva_gift_card"))
		{
			$combinazione = $c->selectId($clean["id_c"]);
			
			if (!empty($combinazione) && ProdottiModel::isGiftCart($combinazione["id_page"]))
				return true;
		}
		
		if (v("attiva_prodotti_digitali"))
		{
			if (!isset($combinazione))
				$combinazione = $c->selectId($clean["id_c"]);
			
			if (!empty($combinazione) && ProdottiModel::isProdottoDigitale($combinazione["id_page"]))
				return true;
		}
		
		if (v("attiva_crediti"))
		{
			if (!isset($combinazione))
				$combinazione = $c->selectId($clean["id_c"]);
			
			if (!empty($combinazione) && ProdottiModel::isProdottoCrediti($combinazione["id_page"]))
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
			return (int)$res[0]["aggregate"]["SOMMA"];
		}
		
		return 0;
	}
	
	private function salvaDisponibilita($id_c, $qtyDaAggiungere)
	{
		if (VariabiliModel::mostraAvvisiGiacenzaCarrello())
		{
			$this->values = array();
			
			if ($this->checkQta($id_c, $qtyDaAggiungere, true))
				$this->values["disponibile"] = 1;
			else
				$this->values["disponibile"] = 0;
		}
		
	}
	
	// aggiorna la disponibilità per tutte le righe del carrello
	public function salvaDisponibilitaCarrello()
	{
		if (VariabiliModel::mostraAvvisiGiacenzaCarrello())
		{
			$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
			
			$cart = $this->getProdotti();
			
			if (v("usa_transactions"))
				$this->db->beginTransaction();
			
			foreach ($cart as $c)
			{
				$this->salvaDisponibilita($c["cart"]["id_c"], 0);
				
				if (isset($this->values["disponibile"]))
				{
					$this->update(null, array(
						"id_cart"	=>	(int)$c["cart"]["id_cart"],
						"cart_uid"	=>	$clean["cart_uid"],
					));
				}
			}
			
			if (v("usa_transactions"))
				$this->db->commit();
		}
	}
	
	// Controllo che non mettano nel carrello più prodotti di quelli di mancano da regalare
	public function controllaQuantitaProdottiListaInCarrello()
	{
		if (!v("carrello_monoprodotto") && v("attiva_liste_regalo"))
		{
			ListeregaloModel::getCookieIdLista();
			
			if (User::$idLista)
			{
				$cart = $this->getProdotti();
				
				$aggiornaElementi = false;
				
				foreach ($cart as $c)
				{
					$numeroRimastiDaRegalare = ListeregaloModel::numeroRimastiDaRegalare(User::$idLista, $c["cart"]["id_c"]);
					
					if ((int)$c["cart"]["quantity"] > $numeroRimastiDaRegalare)
					{
						$aggiornaElementi = true;
						$this->set((int)$c["cart"]["id_cart"], $numeroRimastiDaRegalare);
					}
				}
				
				if ($aggiornaElementi)
					$this->aggiornaElementi();
			}
		}
	}
	
	protected function aggiungiCampoAttributiBackend($id_c, $jsonPers)
	{
		$comb = new CombinazioniModel();
		$pers = new PersonalizzazioniModel();
		
		// Attributi in lingua backend (lingua default)
		$attributiBackendArray = array();
		
		$combAttrBackend = $comb->getStringa($id_c, "<br />", false, true);
		if ($combAttrBackend)
			$attributiBackendArray[] = $combAttrBackend;
		
		$persAttrBackend = $pers->getStringa($jsonPers, "<br />", false, true);
		if ($persAttrBackend)
			$attributiBackendArray[] = $persAttrBackend;
		
		$this->values["attributi_backend"] = implode("<br />",$attributiBackendArray);
	}
	
	private function setForzaValori($forzaValori = array())
	{
		if (isset($forzaValori["codice"]))
			$this->values["codice"] = sanitizeHtml($forzaValori["codice"]);
		
		if (isset($forzaValori["titolo"]))
			$this->values["title"] = $this->values["title_lingua"] = sanitizeHtml($forzaValori["titolo"]);
		
		if (isset($forzaValori["um"]))
			$this->values["um"] = sanitizeHtml($forzaValori["um"]);
		
		if (isset($forzaValori["note"]))
			$this->values["note"] = sanitizeHtml($forzaValori["note"]);
	}
	
	public function add($id_page = 0, $quantity = 1, $id_c = 0, $id_p = 0, $jsonPers = array(), $prIntero = null, $prInteroIvato = null, $prScontato = null, $prScontatoIvato = null, $idRif = null, $forzaRigheSeparate = false, $idIva = null, $forzaValori = array())
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
		
		if (isset($idRif) || isset($idIva))
			$rPage = $p->clear()->where(array("id_page"=>$clean["id_page"]))->send();
		else
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
			
			if (count($res) > 0 && !$forzaRigheSeparate)
			{
				$this->values["quantity"] = (int)$res[0]["cart"]["quantity"] + $clean["quantity"];
				
				// prezzo
				$this->values["price"] = $this->calcolaPrezzoFinale($clean["id_page"], $res[0]["cart"]["prezzo_intero"], $this->values["quantity"], true, true, $res[0]["cart"]["id_c"]);
				
				if (v("prezzi_ivati_in_prodotti"))
					$this->values["price_ivato"] = $this->calcolaPrezzoFinale($clean["id_page"], $res[0]["cart"]["prezzo_intero_ivato"], $this->values["quantity"], true, true, $res[0]["cart"]["id_c"]);
				
				// prezzo fisso
				$this->values["prezzo_fisso"] = $this->calcolaPrezzoFinale($clean["id_page"], $res[0]["cart"]["prezzo_fisso_intero"], 1, true, true, $res[0]["cart"]["id_c"]);
				
				if (v("prezzi_ivati_in_prodotti"))
					$this->values["prezzo_fisso_ivato"] = $this->calcolaPrezzoFinale($clean["id_page"], $res[0]["cart"]["prezzo_fisso_intero_ivato"], 1, true, true, $res[0]["cart"]["id_c"]);
				
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
					
					$this->aggiungiCampoAttributiBackend($clean["id_c"], $jsonPers);
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
					$this->values["gtin"] = $datiCombinazione[0]["combinazioni"]["gtin"];
					$this->values["mpn"] = $datiCombinazione[0]["combinazioni"]["mpn"];
					
					$this->values["immagine"] = ProdottiModel::immagineCarrello($clean["id_page"], $datiCombinazione[0]["combinazioni"]["id_c"], $datiCombinazione[0]["combinazioni"]["immagine"]);
					
					$this->values["peso"] = $datiCombinazione[0]["combinazioni"]["peso"];
					
					$idCombinazione = $datiCombinazione[0]["combinazioni"]["id_c"];
				}
				else
				{
					$this->values["codice"] = $rPage[0]["pages"]["codice"];
					$this->values["gtin"] = $rPage[0]["pages"]["gtin"];
					$this->values["mpn"] = $rPage[0]["pages"]["mpn"];
					$this->values["immagine"] = getFirstImage($clean["id_page"]);
					$this->values["peso"] = $rPage[0]["pages"]["peso"];
				}
				
				if (isset($idIva))
					$this->values["id_iva"] = (int)$idIva;
				else
					$this->values["id_iva"] = $rPage[0]["pages"]["id_iva"];
				
				$this->values["iva"] = $iva->getValore((int)$this->values["id_iva"]);
				
				$this->values["title"] = $rPage[0]["pages"]["title"];
				$this->values["title_lingua"] = PagesModel::getPageLocalizedTitle($clean["id_page"], $rPage[0]["pages"]["title"]);
				
				// forza valori
				$this->setForzaValori($forzaValori);
				
				// Attributi in lingua navigazione corrente
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
				$this->values["prodotto_digitale"] = $rPage[0]["pages"]["prodotto_digitale"];
				$this->values["prodotto_crediti"] = $rPage[0]["pages"]["prodotto_crediti"];
				$this->values["numero_crediti"] = $rPage[0]["pages"]["numero_crediti"];
				$this->values["prodotto_generico"] = $rPage[0]["pages"]["prodotto_generico"];
				
				$this->values["nazione_navigazione"] = sanitizeHtml(User::getNazioneNavigazione());
				
				$this->aggiungiCampoAttributiBackend($clean["id_c"], $jsonPers);
				
				// Traccio la sorgente
				if (v("traccia_sorgente_utente") && User::$sorgente && App::$isFrontend)
					$this->values["sorgente"] = sanitizeAll(User::$sorgente);
				
				if (isset($idRif))
				{
					$this->values["id_rif"] = (int)$idRif;
					
					$rModel = new RigheModel();
					
					$rigaOrdine = $rModel->selectId((int)$idRif);
					
					if (!empty($rigaOrdine))
					{
						$this->values["disponibile"] = $rigaOrdine["disponibile"]; //RigheModel::g()->whereId((int)$idRif)->field("disponibile");
						$this->values["id_riga_tipologia"] = $rigaOrdine["id_riga_tipologia"];
						
// 						if ($rigaOrdine["id_cart"])
// 							$this->values["id_cart"] = (int)$rigaOrdine["id_cart"];
// 						else if (isset($this->values["id_cart"]))
// 							unset($this->values["id_cart"]);
					}
				}
				
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
				
				// Prezzo scontato
				if (isset($prScontato))
				{
					$this->values["price"] = number_format($prScontato,v("cifre_decimali"),".","");
					
					if (isset($prScontatoIvato))
						$this->values["price_ivato"] = number_format($prScontatoIvato,2,".","");
					else
						$this->values["price_ivato"] = number_format($this->values["price"] * (1 + ($this->values["iva"] / 100)),2,".","");
				}
				else
				{
					$this->values["price"] = $this->calcolaPrezzoFinale($clean["id_page"], $prezzoIntero, $this->values["quantity"], true, true, $idCombinazione);
					
					if (v("prezzi_ivati_in_prodotti"))
						$this->values["price_ivato"] = $this->calcolaPrezzoFinale($clean["id_page"], $prezzoInteroIvato, $this->values["quantity"], true, true, $idCombinazione);
				}
				
				// Prezzo fisso
				$this->values["prezzo_fisso_intero"] = $rPage[0]["pages"]["prezzo_fisso"];
				
				$this->values["prezzo_fisso"] = $this->calcolaPrezzoFinale($clean["id_page"], $rPage[0]["pages"]["prezzo_fisso"], 1, true, true, $idCombinazione);
				
				if (v("prezzi_ivati_in_prodotti"))
				{
					$this->values["prezzo_fisso_intero_ivato"] = $rPage[0]["pages"]["prezzo_fisso_ivato"];
					
					$this->values["prezzo_fisso_ivato"] = $this->calcolaPrezzoFinale($clean["id_page"], $rPage[0]["pages"]["prezzo_fisso_ivato"], 1, true, true, $idCombinazione);
				}
				
				if (number_format($this->values["price"],2,".","") != number_format($this->values["prezzo_intero"],2,".",""))
					$this->values["in_promozione"] = "Y";
				else
					$this->values["in_promozione"] = "N";
				
// 				echo number_format($this->values["price"],2,".","")." ".number_format($this->values["prezzo_intero"],2,".","");
// 				
// 				echo $this->values["in_promozione"];
// 				die();
				
// 				print_r($this->values);
				
				$this->sanitize();
				$this->insert();
				
// 				echo $this->notice;
// 				echo $this->getQuery();
// 				echo $this->getError();
				return $this->lastId();
			}
		}
		
		return 0;
	}
	
	public static function operazioneCarrelloOk($res)
	{
		if ($res && $res !== "-1")
			return true;
		
		return false;
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
	
	public function aggiornaElementi($elementiPost = array(), $checkElementiPost = false)
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
							
							if ($checkElementiPost)
							{
								if (!$email && isset($elementiRigaCarrello[$i]))
									$email = htmlentitydecode($elementiRigaCarrello[$i]["email"]);
								
								if (!$testo && isset($elementiRigaCarrello[$i]))
									$testo = htmlentitydecode($elementiRigaCarrello[$i]["testo"]);
							}
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
		
		$res = $this->clear()->select("cart.*,pages.*,contenuti_tradotti.*")->inner("pages")->using("id_page")
			->addJoinTraduzione(null, "contenuti_tradotti", false, (new PagesModel()))
			->where(array("cart_uid"=>$clean["cart_uid"]))->orderBy("cart.id_order ASC,id_cart ASC")->send();
		
		return $res;
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
		
		if ((v("attiva_gift_card") || v("attiva_prodotti_digitali") || v("attiva_crediti")) && $checkGiftCard)
		{
			$c = new CartModel();
			
			if ($c->numberOfItems() > 0)
			{
				$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
				
				$numeroNoGiftCard = $c->clear()->where(array(
					"cart_uid"			=>	$clean["cart_uid"],
					"gift_card"			=>	0,
					"prodotto_digitale"	=>	0,
					"prodotto_crediti"	=>	0,
				))->rowNumber();
				
				if ((int)$numeroNoGiftCard === 0)
					return true;
			}
		}
		
		return false;
	}
	
	public static function numeroProdottiCreditiInCarrello()
	{
		if (!v("attiva_crediti"))
			return 0;
		
		$c = new CartModel();
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$res = $c->clear()->select("sum(quantity) as SOMMA")->where(array(
			"cart_uid"			=>	$clean["cart_uid"],
			"prodotto_crediti"	=>	1,
		))->send();
		
		if (isset($res[0]["aggregate"]["SOMMA"]) && $res[0]["aggregate"]["SOMMA"])
			return $res[0]["aggregate"]["SOMMA"];
		
		return 0;
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
	
	// Metodo usato per il remarketing, per mostrare l'elenco dei prodotti
	public function gElencoProdotti($lingua, $record)
	{
		if (!isset($record["cart_uid"]))
			return "";
		
		$prodottiCarrello = $this->clear()->where(array(
			"cart_uid"	=>	sanitizeAll($record["cart_uid"]),
		))->send(false);
		
		$righeOrdine = array();
		
		foreach ($prodottiCarrello as $p)
		{
			$righeOrdine[] = array(
				"righe"	=>	$p,
			);
		}
		
		$linguaUrl = $lingua ? "/$lingua/" : "/";
		
		ob_start();
		include tpf("/Elementi/Placeholder/elenco_prodotti.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	// Metodo usato per il remarketing, per mostrare il pulsante che manda al checkout
	public function gPulsanteConcludiOrdine($lingua, $record)
	{
		$linguaUrl = $lingua ? "/$lingua/" : "/";
		
		ob_start();
		include tpf("/Elementi/Placeholder/Carrello/concludi_ordine.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function checkCartUidCookie($cartUid)
	{
		if (!v("svuota_file_cookie_carrello_dopo_x_minuti") || App::$operazioneSchedulata)
			return true;
		
		self::deleteExpiredCartUidFiles();
		
		$clean["cart_uid"] = sanitizeAll($cartUid);
		
		$numeroInCarrello = $this->clear()->where(array(
			"cart_uid"	=>	$clean["cart_uid"],
		))->rowNumber();
		
		if ($numeroInCarrello > 0)
			return true;
		else
		{
			if (file_exists(ROOT."/Logs/".self::$cartellaCartUid."/".$clean["cart_uid"].".txt"))
				return true;
		}
		
		return false;
	}
	
	public static function deleteExpiredCartUidFiles()
	{
		if (!v("svuota_file_cookie_carrello_in_automatico"))
			return;
		
		$folder = Domain::$parentRoot."/Logs/".self::$cartellaCartUid;
		
		$path = $folder."/last_clean.txt";
		
		if (@is_dir($folder))
		{
			if (file_exists($path))
			{
				$time = (int)file_get_contents($path);
				
				if ((time() - $time) >= 60 * v("svuota_file_cookie_carrello_dopo_x_minuti"))
				{
					unlink($path);
					
					foreach (new DirectoryIterator($folder) as $fileInfo)
					{
						if ($fileInfo->isDot())
							continue;
						
						if ($fileInfo->getFilename() == "index.html" || $fileInfo->getFilename() == ".htaccess")
							continue;
						
						unlink($fileInfo->getRealPath());
					}
					
					FilePutContentsAtomic($path, time());
				}
			}
			else
				FilePutContentsAtomic($path, time());
		}
	}
	
	public function setCartUidCookie()
	{
		User::$cart_uid = md5(randString(10).microtime().uniqid(mt_rand(),true));
		$time = time() + v("durata_carrello_wishlist_coupon");
		setcookie("cart_uid",User::$cart_uid,$time,"/");
		
		if (v("svuota_file_cookie_carrello_dopo_x_minuti") && !App::$operazioneSchedulata)
		{
			createFolderFull("Logs/".self::$cartellaCartUid, ROOT);
			FilePutContentsAtomic(ROOT."/Logs/".self::$cartellaCartUid."/".User::$cart_uid.".txt", User::$cart_uid);
		}
	}
	
	public function setCartUid()
	{
		//set the cookie for the cart
		if (isset($_COOKIE["cart_uid"]) && $_COOKIE["cart_uid"] && (int)strlen($_COOKIE["cart_uid"]) === 32 && ctype_alnum((string)$_COOKIE["cart_uid"]) && $this->checkCartUidCookie($_COOKIE["cart_uid"]))
		{
			User::$cart_uid = sanitizeAll((string)$_COOKIE["cart_uid"]);
			
			$oModel = new OrdiniModel();
			
			if ($oModel->cartUidAlreadyPresent(User::$cart_uid))
			{
				$this->setCartUidCookie();
				
// 				User::$cart_uid = md5(randString(10).microtime().uniqid(mt_rand(),true));
// 				$time = time() + v("durata_carrello_wishlist_coupon");
// 				setcookie("cart_uid",User::$cart_uid,$time,"/");
			}
		}
		else
		{
			$this->setCartUidCookie();
			
// 			User::$cart_uid = md5(randString(10).microtime().uniqid(mt_rand(),true));
// 			$time = time() + v("durata_carrello_wishlist_coupon");
// 			setcookie("cart_uid",User::$cart_uid,$time,"/");
		}
		
		self::$checkCart = false;
	}
}
