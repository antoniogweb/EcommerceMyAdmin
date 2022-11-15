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

class CombinazionilistiniModel extends GenericModel {

	public function __construct() {
		$this->_tables='combinazioni_listini';
		$this->_idFields='id_combinazione_listino';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'combinazione' => array("BELONGS_TO", 'CombinazioniModel', 'id_c',null,"CASCADE","Si prega di selezionare la comnbinazione"),
        );
    }
    
	public static function elencoListini()
	{
		$cl = new CombinazionilistiniModel();
		
		return $cl->clear()->select("distinct nazione")->toList("nazione")->send();
	}
	
	public function setPriceNonIvato($idPage = 0)
	{
		if (v("prezzi_ivati_in_prodotti") && (isset($this->values["price_ivato"]) || isset($this->values["price_scontato_ivato"])))
		{
			$p = new PagesModel();
			$valore = $p->getIva($idPage);
			
			if (isset($this->values["price_ivato"]))
				$this->values["price"] = number_format(setPrice($this->values["price_ivato"]) / (1 + ($valore / 100)), v("cifre_decimali"),".","");
			
			if (isset($this->values["price_scontato_ivato"]))
				$this->values["price_scontato"] = number_format(setPrice($this->values["price_scontato_ivato"]) / (1 + ($valore / 100)), v("cifre_decimali"),".","");
		}
	}
	
	public function insert()
	{
		if (isset($this->values["id_c"]))
		{
			$c = new CombinazioniModel();
			$comb = $c->selectId($this->values["id_c"]);
			
			if (!empty($comb))
				$this->setPriceNonIvato($comb["id_page"]);
		}
		
		$this->settaCifreDecimali();
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$res = $this->clear()->select("combinazioni.id_page")->inner(array("combinazione"))->where(array(
			"id_combinazione_listino"	=>	(int)$id,
		))->send();
		
		if (count($res) > 0)
			$this->setPriceNonIvato($res[0]["combinazioni"]["id_page"]);
		
		$this->settaCifreDecimali();
		
		if (parent::update($id, $where))
			return true;
		
		return false;
	}
	
	public function getPrezzoListino($idC, $nazione)
	{
		$listino = $this->clear()->where(array(
			"nazione"	=>	sanitizeAll($nazione),
			"id_c"		=>	(int)$idC,
		))->record();
		
		list($campoPrice, $campoPriceScontato) = CombinazioniModel::campiPrezzo();
		
// 		$campoPrice = "price";
// 		$campoPriceScontato = "price_scontato";
// 		
// 		if (v("prezzi_ivati_in_prodotti"))
// 		{
// 			$campoPrice = "price_ivato";
// 			$campoPriceScontato = "price_scontato_ivato";
// 		}
		
		if (empty($listino))
		{
			$c = new CombinazioniModel();
			
			$combinazione = $c->selectId((int)$idC);
			
			if (!empty($combinazione))
			{
				$this->setValues(array(
					"nazione"	=>	$nazione,
					"id_c"		=>	$idC,
					"$campoPrice"	=>	$combinazione[$campoPrice],
					"$campoPriceScontato"	=>	$combinazione[$campoPriceScontato],
				));
				
				if ($this->insert())
					return array($this->lId,$combinazione[$campoPrice],$combinazione[$campoPriceScontato]);
			}
		}
		else
			return array($listino["id_combinazione_listino"],$listino[$campoPrice],$listino[$campoPriceScontato]);
		
		return array(0,null,null);
	}
	
	public static function listinoEsistente($nazione)
	{
		$cl = new CombinazionilistiniModel();
		
		return $cl->clear()->where(array(
			"nazione"	=>	sanitizeDb($nazione),
		))->rowNumber();
	}
}
