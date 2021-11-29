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

class Sendcloud
{
	private $params = "";
	
	public function __construct($record)
	{
		$this->params = $record;
	}
	
	public function inviaOrdine($idO)
	{
		$o = new OrdiniModel();
		
		$ordine = $o->selectId($idO);
		
		$ordine = htmlentitydecodeDeep($ordine);
		
		$quantita = $o->quantitaTotale($idO);
		
		$parcel = array(
			"parcel"	=>	array(
				"name"=>$ordine["nome"]." ".$ordine["cognome"],
				"company_name"=>$ordine["ragione_sociale"],
				"address"=>$ordine["indirizzo_spedizione"],
				"house_number"=>" ",
				"city"=>$ordine["citta_spedizione"],
				"postal_code"=>$ordine["cap_spedizione"],
				"telephone"=>$ordine["telefono_spedizione"],
				"request_label"=>false,
				"email"=>$ordine["email"],
				"data"=>[],
				"country"=>$ordine["nazione_spedizione"],
				"order_number" =>  $ordine["id_o"],
				"total_order_value_currency" => "EUR",
				"total_order_value" =>  $ordine["total"],
				"quantity" =>  $quantita,
			),
		);
		
		return $parcel;
	}
	
	public function configurato($record)
	{
		if (trim($record["secret_1"]) && trim($record["secret_2"]) && trim($record["api_endpoint"]) && $record["attivo"])
			return true;
		
		return false;
	}
}
