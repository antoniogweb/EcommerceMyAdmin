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

class Brt extends Spedizioniere
{
	protected $condizioniCampi = array(
		"lunghezzaMax"	=>	array(
			"ragione_sociale"	=>	70, // consigneeContactName
			"ragione_sociale_2"	=>	35, // consigneeCompanyName
			"indirizzo"			=>	35,
			"citta"				=>	35,
			"cap"				=>	9,
			"provincia"			=>	2,
			"contrassegno"		=>	10,
			"importo_assicurazione"	=>	10,
			"nazione"			=>	2,
			"riferimento_mittente_numerico"	=>	15,
			"riferimento_mittente_alfa"		=>	15,
			"codice_pagamento_contrassegno"	=>	2,
			"codice_tariffa"	=>	3,
		),
	);
	
	public function isAttivo()
	{
		if ($this->params["attivo"] && trim($this->params["codice_cliente"]) && trim($this->params["password_cliente"]) && trim($this->params["codice_sede"]))
			return true;
		
		return false;
	}
	
	public function gCampiForm()
	{
		return 'titolo,modulo,attivo,codice_cliente,password_cliente,codice_sede';
	}
	
	public function gCampiSpedizione()
	{
		return array('tipo_servizio', 'codice_tariffa', 'codice_pagamento_contrassegno', 'riferimento_mittente_numerico', 'riferimento_mittente_alfa', 'importo_assicurazione');
	}
	
	public function gCampiIndirizzo()
	{
		return array('ragione_sociale_2');
	}
	
// 	// Chiama i server del corriere e salva le informazioni del tracking nella spedizione
// 	public function getInfo($idSpedizione)
// 	{
// 		$this->scriviLogInfoTracking((int)$idSpedizione);
// 	}
// 	
// 	public function consegnata($idSpedizione)
// 	{
// 		if (true)
// 			$this->scriviLogConsegnata((int)$idSpedizione);
// 		
// 		return true;
// 	}
// 	
// 	// Recupera le ultime informazioni del tracking salvate e verifica se la spedizione è stata impostata in errore
// 	public function inErrore($idSpedizione)
// 	{
// 		if (true)
// 			$this->scriviLogInErrore((int)$idSpedizione);
// 		
// 		return true;
// 	}

	public function gCodiciPagamentoContrassegno()
	{
		return OpzioniModel::codice("BRT_CODICE_PAGAMENTO");
	}
	
	public function gTipoServizio()
	{
		return OpzioniModel::codice("BRT_TIPO_SERVIZIO");
	}
	
	public function gCodiceTariffa()
	{
		return OpzioniModel::codice("BRT_CODICE_TARIFFA");
	}
	
	// Inserisci i valori di default del corriere
	public function inserisciValoriDefaultCorriere(SpedizioninegozioModel $spedizione)
	{
		$campiSpedizione = $this->gCampiSpedizione();
		
		$spedizione->values = array();
		$spedizione->values["tipo_servizio"] = OpzioniModel::primoCodice("BRT_TIPO_SERVIZIO");
		$spedizione->values["codice_tariffa"] = OpzioniModel::primoCodice("BRT_CODICE_TARIFFA");
		$spedizione->values["codice_pagamento_contrassegno"] = OpzioniModel::primoCodice("BRT_CODICE_PAGAMENTO");
	}
	
	// Restituisce la spedizione pronta per essere inviata al corriere come array associativo
	public function getStrutturaSpedizione($idS)
	{
		$spModel = new SpedizioninegozioModel();
			
		$record = $spModel->selectId($idS);
		
		if (!empty($record))
		{
			$record = htmlentitydecodeDeep($record);
			
			$colli = $spModel->getColli([(int)$idS]);
			$peso = $spModel->peso([(int)$idS]);
			
			$params = htmlentitydecodeDeep($this->params);
			
			$jsonArray = array(
				"account"	=>	array(
					"userID"	=>	$params["codice_cliente"],
					"password"	=>	$params["password_cliente"],
				),
				"createData"	=>	array(
					"network"			=>	" ",
					"departureDepot"	=>	$params["codice_sede"],
					"senderCustomerCode"=>	$params["codice_cliente"],
					"deliveryFreightTypeCode"	=>	"DAP",
					"serviceType"			=>	$record["tipo_servizio"],
					"consigneeCompanyName"	=>	$record["ragione_sociale"],
					"consigneeAddress"		=>	$record["indirizzo"],
					"consigneeZIPCode"		=>	$record["cap"],
					"consigneeCountryAbbreviationISOAlpha2"	=>	$record["nazione"],
					"consigneeCity"			=>	$record["citta"],
					"consigneeProvinceAbbreviation"	=>	$record["provincia"],
					"consigneeContactName"	=>	$record["ragione_sociale_2"],
					"consigneeTelephone"	=>	$record["telefono"],
					"consigneeEMail"		=>	$record["email"],
					"pricingConditionCode"	=>	$record["codice_tariffa"],
					"insuranceAmount"		=>	number_format($record["importo_assicurazione"],2,".",""),
					"insuranceAmountCurrency"	=>	"EUR",
					"notes"					=>	$record["note_interne"],
					"numericSenderReference"=>	$record["riferimento_mittente_numerico"],
					"alphanumericSenderReference"=>	$record["riferimento_mittente_alfa"],
					"numberOfParcels"		=>	count($colli),
					"weightKG"				=>	number_format($peso,1,".",""),
				),
				"isLabelRequired"		=>	"1",
				"labelParameters"		=>	array(
					"outputType"		=>	'PDF',
					"offsetX"			=>	"0",
					"offsetY"			=>	"0",
					"isBorderRequired"	=>	"0",
					"isLogoRequired"	=>	"0",
					"isBarcodeControlRowRequired"	=>	"0",
				),
			);
			
// 			if ($record["contrassegno"] > 0)
// 			{
				$jsonArray["createData"]["cashOnDelivery"] = number_format($record["contrassegno"],2,".","");
				$jsonArray["createData"]["isCODMandatory"] = $record["contrassegno"] > 0 ? 1 : 0;
				$jsonArray["createData"]["codPaymentType"] = $record["contrassegno"] > 0 ? $record["codice_pagamento_contrassegno"] : "";
// 			}
			
			
			return $jsonArray;
		}
		
		return [];
	}
	
	// Pronoto la spedizione al corriere per avere il numero di spedizione e l'etichetta
	public function prenotaSpedizione($idS, SpedizioninegozioModel $spedizione = null)
	{
		if ($this->isAttivo())
		{
			$jsonArray = $this->getStrutturaSpedizione($idS);
			
			$result = $this->send("/shipments/shipment", "POST", $jsonArray);
			
			print_r($result);
			
// 			var_dump($result);
			die();
// 			
// 			$xml = aToX($xmlArray, "", true, true);
// 			
// 			$infoLabel = $this->AddParcel($xml);
// 			
// 			$xmlObj = simplexml_load_string($infoLabel);
// 			
// 			// Salvo il log dell'invio e dell'output
// 			SpedizioninegozioinfoModel::g(false)->inserisci($idS, "XMLInfoParcel", $xml, "XML");
// 			SpedizioninegozioinfoModel::g(false)->inserisci($idS, "InfoLabel", $infoLabel, "XML");
// 			
// 			if (isset($xmlObj->Parcel))
// 				return new Data_Spedizioni_Result($xmlObj->Parcel[0]->NumeroSpedizione, "");
		}
		else
			$this->settaNoticeModel($spedizione, "Attenzione, il modulo spedizioniere ".$this->params["titolo"]. " non è attivo");
		
		return false;
	}
	
	public function send($url, $method = "POST", $valori = array())
	{
		$url = v("url_rest_api_brt")."/".ltrim($url,"/");
		
		$ch = curl_init($url);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Accept: application/json']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		if (!empty($valori))
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($valori));                                                                                                                 
		
		$result = curl_exec($ch);
		
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);
		
		return json_decode($result, true);
	}
}
