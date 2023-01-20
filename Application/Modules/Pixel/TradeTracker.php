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

class TradeTracker extends Pixel
{
	public function gCampiForm()
	{
		return 'titolo,attivo,key_1,key_2';
	}
	
	public function isAttivo()
	{
		if ($this->params["attivo"] && trim($this->params["key_1"]) && trim($this->params["key_2"]))
			return true;
		
		return false;
	}
	
	public function editFormStruct($model, $record)
	{
		$model->formStruct["entries"]["key_1"]["labelString"] = "ID Campagna";
		
		$model->formStruct["entries"]["key_2"]["labelString"] = "ID Prodotto";
	}
	
	public function getPurchaseScript($idOrdine, $info = array(), $script = true)
	{
		$evento = $this->getEvento("PURCHASE", $idOrdine, "orders");
		
		if ($script && !empty($evento))
		{
			self::$eventoInviato[] = $this->params["id_pixel"];
			
			return "";
		}
		
		$strutturaOrdine = $this->infoOrdine((int)$idOrdine);
		
		if (!$this->checkData($strutturaOrdine))
			return "";
		
		$jsonArray = array(
			"type"			=>	"sales",
			"campaignID"	=>	$this->params["key_1"],
			"productID"		=>	$this->params["key_2"],
			"transactionID"	=>	$strutturaOrdine["id_o"],
			"transactionAmount"	=>	$strutturaOrdine["totale_prodotti_non_ivato"],
			"quantity"		=>	$strutturaOrdine["numero_prodotti"],
			"descrMerchant"	=>	"Ordine ".$strutturaOrdine["id_o"]." del ".date("d/m/Y", strtotime($strutturaOrdine["data_creazione"])),
			"descrAffiliate"=>	OrdiniModel::getNominativo($strutturaOrdine),
			"currency"		=>	v("codice_valuta"),
			"trackingGroupID"	=>	"",
			"vc"			=>	$strutturaOrdine["codice_promozione"],
		);
		
		ob_start();
		
		if ($script)
		{
			include(tpf(ElementitemaModel::p("TRADETRACKER_PURCHASE","", array(
				"titolo"	=>	"Codice JS per evento purchase di TradeTracker",
				"percorso"	=>	"Elementi/Pixel/Purchase/TradeTracker/Script",
			))));
			
			$this->salvaEvento("PURCHASE", $idOrdine, "orders");
		}
		else if (!in_array($this->params["id_pixel"], self::$eventoInviato))
			include(tpf(ElementitemaModel::p("TRADETRACKER_PURCHASE_NS","", array(
				"titolo"	=>	"Codice noscript per evento purchase di TradeTracker",
				"percorso"	=>	"Elementi/Pixel/Purchase/TradeTracker/NoScript",
			))));
		
		return ob_get_clean();
	}
	
	public function getPurchaseNoScript($idOrdine, $info = array())
	{
		return $this->getPurchaseScript($idOrdine, $info, false);
	}
}
