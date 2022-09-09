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

class PromozioniModel extends GenericModel {

	public $lId = 0;
	
	public function __construct() {
		$this->_tables='promozioni';
		$this->_idFields='id_p';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'promozioni.dal desc,promozioni.al desc';
		$this->_lang = 'It';
		
// 		$this->_popupItemNames = array(
// 			'attivo'	=>	'attivo',
// 		);
// 
// 		$this->_popupLabels = array(
// 			'attivo'	=>	'PROMOZIONE ATTIVA?',
// 		);
// 
// 		$this->_popupFunctions = array(
// 			'attivo'=>	'getYesNo',
// 		);
		
// 		$this->addStrongCondition("both",'checkMatch|/^[0-9]{1,8}$/',"sconto|Si prega di verificare che il campo <i>Sconto promozione (in %)</i> contenga solo numeri");
		
		parent::__construct();

	}
	
	public function setFormStruct($id = 0)
	{
		$labelSconto = 'Valore sconto';
		
		if (!v("attiva_promo_sconto_assoluto"))
		{
			$tipo = $this->clear()->where(array("id_p"=>(int)$id))->field("tipo_sconto");
			
			$labelSconto .= ($tipo == "ASSOLUTO") ? " (in €)" : " (in %)";
		}
			
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'titolo'		=>	array(
					'labelString'=>	'Titolo',
				),
				'codice'		=>	array(
					'labelString'=>	'Codice della promozione',
				),
				'dal'		=>	array(
					'labelString'=>	'Attiva dal',
					'className'	=>	'data_field form-control',
				),
				'al'		=>	array(
					'labelString'=>	'Attiva fino al',
					'className'	=>	'data_field form-control',
				),
				'sconto'		=>	array(
					'labelString'=>	$labelSconto,
				),
				'attivo'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Promozione attiva?',
					'options'	=>	array('sì'=>'Y','no'=>'N'),
				),
				'numero_utilizzi'		=>	array(
					'labelString'=>	'Numero massimo di utilizzi',
				),
// 				'tipo_sconto'	=>	array(
// 					'type'		=>	'Select',
// 					'labelString'=>	'Tipo coupon',
// 					'options'	=>	array('PERCENTUALE'=>'In percentuale','ASSOLUTO'=>'Assoluto'),
// 					'reverse'	=>	'yes',
// 				),
				'id_p'	=>	array(
					'type'		=>	'Hidden'
				),
			),
		);
	}
	
	public function relations() {
        return array(
			'categorie' => array("HAS_MANY", 'PromozionicategorieModel', 'id_p', null, "CASCADE"),
			'pagine' => array("HAS_MANY", 'PromozionipagineModel', 'id_p', null, "CASCADE"),
        );
    }
    
	public function insert()
	{
		if (strcmp($this->values["codice"],"") === 0)
		{
			$this->values["codice"] = md5(randString(22).microtime().uniqid(mt_rand(),true));
		}
		
		$this->values["dal"] = reverseData($this->values["dal"]);
		$this->values["al"] = reverseData($this->values["al"]);
		
		if (isDateFull($this->values["dal"]) and isDateFull($this->values["al"]))
		{
			$res = $this->clear()->where(array("codice"=>$this->values["codice"]))->send();
			
			if (count($res) === 0)
			{
				parent::insert();
			}
			else
			{
				$this->notice = "<div class='alert'>Attenzione: il codice promozione è già presente, si prega di selezionarne un altro</div>";
				$this->result = false;
			}
		}
		else
		{
			$this->notice = "<div class='alert'>Si prega di ricontrollare il formato delle date di validità della promozione</div>";
			$this->result = false;
		}
		
		$this->lId = $this->lastId();
	}
	
	public function update($id = null, $where = null)
	{
		$clean["id"] = (int)$id;
		$this->values["dal"] = reverseData($this->values["dal"]);
		$this->values["al"] = reverseData($this->values["al"]);
		
		if (isDateFull($this->values["dal"]) and isDateFull($this->values["al"]))
		{
			$res = $this->clear()->where(array(
					"codice"	=>	$this->values["codice"],
					"ne"	=>	array("id_p" => $clean["id"]),
				))->send();
			
			if (count($res) === 0)
			{
				parent::update($id);
			}
			else
			{
				$this->notice = "<div class='alert'>Attenzione: il codice promozione è già presente, si prega di selezionarne un altro</div>";
				$this->result = false;
			}
		}
		else
		{
			$this->notice = "<div class='alert'>Si prega di ricontrollare il formato delle date di validità della promozione</div>";
			$this->result = false;
		}
	}
	
	public function isActive($id_p)
	{
		$clean["id_p"] = (int)$id_p;
		
		$field = $this->clear()->selectId($clean["id_p"]);
		
		if (count($field) > 0 and $field["attivo"] === "Y")
		{
			return true;
		}
		return false;
	}
	
	public function recordExists($id_p)
	{
		$clean["id_p"] = (int)$id_p;
		
		$res = $this->clear()->where(array("id_p"=>$clean["idp"]))->send();
		
		if (count($res) > 0)
		{
			return true;
		}
		return false;
	}

	//controllo che il coupon sia attivo
	public function isActiveCoupon($codice, $ido = null)
	{
		$clean["codice"] = sanitizeAll($codice);
		
		$res = $this->clear()->where(array("codice"=>$clean["codice"],"attivo"=>"Y"))->send();
		
		if (count($res) > 0)
		{
			$dal = getTimeStampComplete($res[0]["promozioni"]["dal"]);
			$al = getTimeStampComplete($res[0]["promozioni"]["al"]) + 86400;
			
			$now = time();
			
			if ($now >= $dal and $now <= $al)
			{
				$numeroUtilizzi = (int)$res[0]["promozioni"]["numero_utilizzi"];
				$numeroVolteUsata = (int)$this->getNUsata($res[0]["promozioni"]["id_p"], $ido);
				
				if ($numeroUtilizzi > $numeroVolteUsata)
				{
					$cart = new CartModel();
					$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
					$prodottiCarrello = $cart->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->toList("id_page")->send();
					
					if (count($prodottiCarrello) > 0)
					{
						$prodottiPromozione = $this->elencoProdottiPromozione($clean["codice"]);
						
						if ((int)count($prodottiPromozione) === 0)
							return false;
						
						foreach ($prodottiCarrello as $idPage)
						{
							if (in_array($idPage, $prodottiPromozione))
								return true;
						}
					}
					
					return false;
				}
				else
					return false;
			}
		}
		
		return false;
	}
	
	// Restituisce il coupon attivo
	public static function getCouponAttivo($ido = null)
	{
		$p = new PromozioniModel();
		
		if (isset(User::$coupon) && $p->isActiveCoupon(User::$coupon, $ido))
			return $p->getCoupon(User::$coupon);
		
		return array();
	}
	
	//restituisce tutti i dati del coupon
	public function getCoupon($codice)
	{
		$clean["codice"] = sanitizeAll($codice);
		
		$res = $this->clear()->where(array("codice"=>$clean["codice"]))->send();
		
		if (count($res) > 0)
		{
			return $res[0]["promozioni"];
		}
		return array();
	}
	
	public function getNUsata($id_p, $ido = null)
	{
		$clean["id_p"] = (int)$id_p;
		
		$res = $this->clear()->where(array("id_p"=>$clean["id_p"]))->send();
		
		if (count($res) > 0)
		{
			$clean['coupon'] = sanitizeAll($res[0]["promozioni"]["codice"]);
			
			$o = new OrdiniModel();
			
			if ($ido)
				$res2 = $o->clear()->where(array("codice_promozione"=>$clean['coupon']))->sWhere("id_o != ".(int)$ido)->send();
			else
				$res2 = $o->clear()->where(array("codice_promozione"=>$clean['coupon']))->send();
			
			return (count($res2));
		}
		
		return 0;
	}
	
	// Estrae l'elenco di tuti i prodotti nella promozione
	public function elencoProdottiPromozione($coupon)
	{
		$clean["codice"] = sanitizeAll($coupon);
		
		$promozione = $this->clear()->where(array("codice"=>$clean["codice"]))->record();
		
		$pc = new PromozionicategorieModel();
		$pp = new PromozionipagineModel();
		$p = new PagesModel();
		$c = new CategoriesModel();
		
		$idCs = $pc->clear()->where(array(
			"id_p"	=>	(int)$promozione["id_p"],
		))->toList("id_c")->send();
		
		$idPages = $pp->clear()->inner(array("pagina"))->where(array(
			"id_p"	=>	(int)$promozione["id_p"],
		))->toList("id_page")->send();
		
		foreach ($idCs as $idC)
		{
			$children = $c->children((int)$idC, true);

			$pages = $p->clear()->select("id_page")->where(array(
				"attivo" => "Y",
				"principale"=>"Y",
				"in" => array("-id_c" => $children),
			))->toList("id_page")->send();
			
			$idPages = array_merge($idPages, $pages);
		}
		
		$idPages = array_unique($idPages);
		
		if (count($idPages) === 0)
		{
			$idC = $c->clear()->where(array("section"=>Parametri::$nomeSezioneProdotti))->field("id_c");
			
			$children = $c->children((int)$idC, true);
			
			$idPages = $p->clear()->select("id_page")->where(array(
				"attivo" => "Y",
				"principale"=>"Y",
				"in" => array("-id_c" => $children),
			))->toList("id_page")->send();
		}
		
		return $idPages;
	}
	
	public function sconto($record)
	{
		$valore = $record["promozioni"]["sconto"];
		
		if ($record["promozioni"]["tipo_sconto"] == "ASSOLUTO")
			$valore .= " €";
		else
			$valore .= " %";
		
		return $valore;
	}
}
