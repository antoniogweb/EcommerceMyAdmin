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

class RigheModel extends GenericModel {

	public function __construct() {
		$this->_tables='righe';
		$this->_idFields='id_r';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'righe.id_order';
		$this->_lang = 'It';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'elementi' => array("HAS_MANY", 'RigheelementiModel', 'id_r', null, "CASCADE"),
        );
    }
    
	public function insert()
	{
		$res = parent::insert();
		
		if ($res && v("attiva_giacenza") && v("scala_giacenza_ad_ordine") && isset($this->values["quantity"]) && isset($this->values["id_c"]))
			CombinazioniModel::g()->movimenta($this->values["id_c"], $this->values["quantity"]);
		
		return $res;
	}
	
	// imposta il campo "movimentato"
	public function setMovimentato($idR, $valore = 1)
	{
		$this->sValues(array(
			"movimentato"	=>	$valore,
		));
		
		$this->pUpdate((int)$idR);
	}
	
	public function setPriceNonIvato($id = 0)
	{
		if (v("prezzi_ivati_in_prodotti") && (isset($this->values["price_ivato"]) || isset($this->values["prezzo_intero_ivato"])))
		{
			$valore = (float)RigheModel::g()->whereId((int)$id)->field("iva");
			
			if (isset($this->values["price_ivato"]))
				$this->values["price"] = number_format(setPrice($this->values["price_ivato"]) / (1 + ($valore / 100)), v("cifre_decimali"),".","");
			
			if (isset($this->values["prezzo_intero_ivato"]))
				$this->values["prezzo_intero"] = number_format(setPrice($this->values["prezzo_intero_ivato"]) / (1 + ($valore / 100)), v("cifre_decimali"),".","");
		}
	}
	
	public function update($id = null, $where = null)
	{
		$old = $this->selectId($id);
		
		$this->setPriceNonIvato($id);
		
		if (parent::update($id, $where))
		{
			$new = $this->selectId($id);
			
			if (v("attiva_giacenza") && v("scala_giacenza_ad_ordine") && $new["quantity"] != $old["quantity"] && $old["movimentato"])
				CombinazioniModel::g()->movimenta($new["id_c"], ($new["quantity"] - $old["quantity"]));
			
			return true;
		}
		
		return false;
	}
	
	public function del($id = null, $where = null)
	{
		$old = $this->selectId($id);
		
		if (parent::del($id, $where))
		{
			if (v("attiva_giacenza") && v("scala_giacenza_ad_ordine") && $old["movimentato"])
				CombinazioniModel::g()->movimenta($old["id_c"], (-1)*$old["quantity"]);
			
			$_SESSION["aggiorna_totali_ordine"] = true;
			
			return true;
		}
		
		return false;
	}
	
	public function aggiornaTotaliOrdine($idRiga)
	{
		if (!App::$isFrontend)
		{
			$idOrdine = (int)RigheModel::g()->whereId((int)$idRiga)->field("id_o");
			
			if ($idOrdine && OrdiniModel::tipoOrdine($idOrdine) != "W")
				OrdiniModel::g()->aggiornaTotali($idOrdine);
		}
	}
	
	public function titolocompleto($record)
	{
		$titolo = $record["pages"]["title"] ? $record["pages"]["title"] : $record[$this->_tables]["title"];
		
// 		if ($record[$this->_tables]["attributi"])
// 			$titolo .= "<br />".$record[$this->_tables]["attributi"];
		
		return $titolo;
	}
	
	public static function regalati($idLista, $idC = 0)
	{
		$r = RigheModel::g()->inner("orders")->on("orders.id_o = righe.id_o")->where(array(
			"orders.id_lista_regalo"	=>	(int)$idLista,
		));
		
		if ($idC)
			$r->aWhere(array(
				"righe.id_c"	=>	(int)$idC
			));
		
		return $r;
	}
	
	private function getPrezzoCampo($record, $field)
	{
		return v("prezzi_ivati_in_prodotti") ? setPriceReverse(setPrice($record["righe"][$field."_ivato"])) : number_format(setPrice($record["righe"][$field]),v("cifre_decimali"),".","");
	}
	
	public function prezzoInteroCrud($record)
	{
		$prezzo = $this->getPrezzoCampo($record, "prezzo_intero");
		
// 		if (OrdiniModel::g()->isDeletable($record["righe"]["id_o"]))
// 			return "<input id-riga='".$record["righe"]["id_r"]."' style='max-width:90px;' class='form-control' name='prezzo_intero' value='".$prezzo."' />";
// 		else
			return $prezzo;
	}
	
	public function prezzoScontatoCrud($record)
	{
		$prezzo = $this->getPrezzoCampo($record, "price");
		
		return $prezzo;
// 		return "<input id-riga='".$record["righe"]["id_r"]."' style='max-width:90px;' class='form-control' name='price' value='".$prezzo."' />";
	}
	
	public function prezzoFinaleCrud($record)
	{
		$prezzo = $this->getPrezzoCampo($record, "prezzo_finale");
		
		if (OrdiniModel::g()->isDeletable($record["righe"]["id_o"]))
			return "<input id-riga='".$record["righe"]["id_r"]."' style='max-width:90px;' class='form-control' name='prezzo_finale' value='".$prezzo."' />";
		else
			return $prezzo;
	}
	
	public function quantitaCrud($record)
	{
		if (OrdiniModel::g()->isDeletable($record["righe"]["id_o"]))
			return "<input id-riga='".$record["righe"]["id_r"]."' style='max-width:60px;' class='form-control' name='quantity' value='".$record["righe"]["quantity"]."' />";
		else
			return $record["righe"]["quantity"];
	}
	
	public function attributiCrud($record)
	{
		if ($record["righe"]["attributi"])
			return $record["righe"]["attributi"];
		
		return "--";
	}
	
	public function deletable($id)
	{
		$idOrdine = (int)$this->whereId($id)->field("id_o");
		
		return OrdiniModel::g()->isDeletable($idOrdine);
	}
}
