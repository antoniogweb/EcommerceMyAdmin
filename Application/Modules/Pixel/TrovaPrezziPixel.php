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

class TrovaPrezziPixel extends Pixel
{
	public function gCampiForm()
	{
		return 'titolo,attivo,key_1';
	}
	
	public function isAttivo()
	{
		if ($this->params["attivo"] && trim($this->params["key_1"]))
			return true;
		
		return false;
	}
	
	public function editFormStruct($model, $record)
	{
		$model->formStruct["entries"]["key_1"]["labelString"] = "Chiave Merchant";
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
		
		$righe = $strutturaOrdine["righe"];
		
		$jsonArray = array(
			"type"			=>	"sales",
			"campaignID"	=>	$this->params["key_1"],
			"productID"		=>	$this->params["key_2"],
			"transactionID"	=>	$strutturaOrdine["id_o"],
			"transactionAmount"	=>	number_format($strutturaOrdine["totale_prodotti_non_ivato"],2,".",""),
			"quantity"		=>	$strutturaOrdine["numero_prodotti"],
			"descrMerchant"	=>	"Ordine ".$strutturaOrdine["id_o"]." del ".date("d/m/Y", strtotime($strutturaOrdine["data_creazione"])),
			"descrAffiliate"=>	OrdiniModel::getNominativo($strutturaOrdine),
			"currency"		=>	v("codice_valuta"),
			"trackingGroupID"	=>	"",
			"vc"			=>	$strutturaOrdine["codice_promozione"],
		);
		
		if ($script)
		{
			ob_start();
			include(tpf(ElementitemaModel::p("TROVAPREZZI_PURCHASE","", array(
				"titolo"	=>	"Codice JS per evento purchase di TrovaPrezzi",
				"percorso"	=>	"Elementi/Pixel/Purchase/TrovaPrezzi/Script",
			))));
			$res = ob_get_clean();
		}
		else if (!in_array($this->params["id_pixel"], self::$eventoInviato))
			$res = "";
		
		if ($script)
			$this->salvaEvento("PURCHASE", $idOrdine, "orders", $res);
		else if (!in_array($this->params["id_pixel"], self::$eventoInviato))
			$this->aggiornaEvento("PURCHASE", $idOrdine, "orders", array(
				"codice_evento_noscript"	=>	$res,
			));
		
		return $res;
	}
	
	public function getPurchaseNoScript($idOrdine, $info = array())
	{
		return $this->getPurchaseScript($idOrdine, $info, false);
	}
}
