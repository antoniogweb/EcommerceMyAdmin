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

class Sendcloud extends Integrazione
{
	private $params = "";
	
	public function __construct($record)
	{
		$this->params = $record;
	}
	
	protected function send($data, $action = "")
	{
		$username = $this->params["secret_1"];
		$password = $this->params["secret_2"];
		
		$options_dett = array(
			"http" => array(
				"header"  => "Content-type: application/json\r\n".'Authorization: Basic '.base64_encode("$username:$password")."\r\n",
				"method"  => "POST",
				"content" => json_encode($data),
				"timeout"	=>	5,
				"ignore_errors"	=>	true
			),
			'ssl' => array(
				'verify_peer'       => false,
				'verify_peer_name'  => false,
			)
		);
		
		if ($action)
			$action = "/".ltrim($action,"/");
		
		$context_dett  = stream_context_create($options_dett);
		return @file_get_contents($this->params["api_endpoint"].$action, false, $context_dett);
	}
	
	public function inviaOrdine($idO)
	{
		$result = 0;
		$notice = "C'è stato un problema nell'invio a Sendcloud, si prega di riprovare più tardi";
		$idPiattaforma = "";
		
		$o = new OrdiniModel();
		
		$ordine = $o->selectId($idO);
		
		$ordine = htmlentitydecodeDeep($ordine);
		
// 		$quantita = $o->quantitaTotale($idO);
		$quantita = 1;
		
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
		
// 		print_r($parcel);
		
		$res = json_decode($this->send($parcel), true);
		
		if (isset($res["parcel"]["id"]) && $res["parcel"]["id"])
		{
			$idPiattaforma = $res["parcel"]["id"];
			$notice = "Ordine inviato correttamente a Sendcloud";
			$result = 1;
		}
		else if (isset($res["error"]["message"]))
		{
			$notice = "RISPOSTA SENDCLOUT - ".sanitizeHtml($res["error"]["message"]);
			$result = 0;
		}
		
		$result = array(
			"result"	=>	$result,
			"notice"	=>	$notice,
			"id"		=>	$idPiattaforma,
		);
		
// 		print_r($result);die();
		
		return $result;
	}
	
	public function configurato($record)
	{
		if (trim($record["secret_1"]) && trim($record["secret_2"]) && trim($record["api_endpoint"]) && $record["attivo"])
			return true;
		
		return false;
	}
}
