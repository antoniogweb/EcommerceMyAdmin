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
		"notEmpty"	=>	array(
			"riferimento_mittente_numerico"
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
		return 'titolo,ragione_sociale_cliente,modulo,attivo,codice_cliente,password_cliente,codice_sede';
	}
	
	public function gCampiSpedizione()
	{
		return array('tipo_servizio', 'codice_tariffa', 'codice_pagamento_contrassegno', 'riferimento_mittente_numerico', 'riferimento_mittente_alfa', 'importo_assicurazione');
	}
	
	public function gCampiIndirizzo()
	{
		return array('ragione_sociale_2');
	}
	
	public function getLabelSpedizioniere($response, $campo = "descrizione")
	{
		if (isset($response["ttParcelIdResponse"]["lista_eventi"]))
		{
			foreach ($response["ttParcelIdResponse"]["lista_eventi"] as $evento)
			{
				if (isset($evento["evento"][$campo]))
					return $evento["evento"][$campo];
			}
		}
		else if (isset($response["return"]["LISTA_EVENTI"]))
		{
			$campo = strtoupper($campo);
			
			if (isset($response["return"]["LISTA_EVENTI"]["EVENTO"]))
			{
				$evento = $response["return"]["LISTA_EVENTI"]["EVENTO"];
				
				if (isset($evento["EVENTO"][$campo]))
					return $evento["EVENTO"][$campo];
			}
			else
			{
				foreach ($response["return"]["LISTA_EVENTI"] as $evento)
				{
					if (isset($evento["EVENTO"][$campo]))
						return $evento["EVENTO"][$campo];
				}
			}
		}
		
		return "";
	}
	
	// Chiama i server del corriere e salva le informazioni del tracking nella spedizione
	public function getInfo($idSpedizione)
	{
		$spnModel = new SpedizioninegozioModel();
		
		$spedizione = $spnModel->selectId((int)$idSpedizione);
		
		if (!$this->checkTimeInfo($spedizione))
			return;
		
		$params = htmlentitydecodeDeep($this->params);
		
		$trackingInfo = $labelSpedizioniere = $labelSpedizioniereFrontend = "";
		
		if (!empty($spedizione))
		{
			if ($spedizione["numero_spedizione"])
			{
				$urlTracking = rtrim(v("url_tracking_brt"),"/")."/".$spedizione["numero_spedizione"];
				
				// Create a stream
				$opts = [
					"http" => [
						"method" => "GET",
						"header" => "Accept-language: en\r\n" .
							"userID: ".$this->getParam("codice_cliente")."\r\n" . 
							"password: ".$this->getParam("password_cliente")."\r\n"
					]
				];

				// DOCS: https://www.php.net/manual/en/function.stream-context-create.php
				$context = stream_context_create($opts);

				// Open the file using the HTTP headers set above
				// DOCS: https://www.php.net/manual/en/function.file-get-contents.php
				$trackingInfo = file_get_contents($urlTracking, false, $context);
				
				$response = json_decode($trackingInfo, true);
				
				if (isset($response["ttParcelIdResponse"]["executionMessage"]["code"]) && $response["ttParcelIdResponse"]["executionMessage"]["code"] >= 0)
				{
					$labelSpedizioniere = $this->getLabelSpedizioniere($response);
					
					if ($labelSpedizioniere != "DATI SPEDIZ. TRASMESSI A BRT")
						$labelSpedizioniereFrontend = $labelSpedizioniere;
				}
			}
			else
			{
				$headers = array(
					'trace' =>true,
					'connection_timeout' => 500000,
					'cache_wsdl' => WSDL_CACHE_BOTH,
					'keep_alive' => false
				);
				
				$soap_url = 'http://wsr.brt.it:10041/web/GetIdSpedizioneByRMAService/GetIdSpedizioneByRMA?wsdl';
				$client = new SoapClient($soap_url, $headers);
				
				$var = array(
					"arg0"	=>	array(
						"CLIENTE_ID"	=>	$this->getParam("codice_cliente"),
						"RIFERIMENTO_MITTENTE_ALFABETICO"	=>	htmlentitydecode($spedizione["riferimento_mittente_alfa"]),
					)
				);
				
				$res = $client->GetIdSpedizioneByRMA($var);
				
				if (isset($res->return->SPEDIZIONE_ID) && $res->return->SPEDIZIONE_ID != 0)
				{
					$soap_url2 = 'http://wsr.brt.it:10041/web/BRT_TrackingByBRTshipmentIDService/BRT_TrackingByBRTshipmentID?wsdl';
					$client2 = new SoapClient($soap_url2, $headers);
					
					$var = array(
						"arg0"	=>	array(
							"SPEDIZIONE_BRT_ID"	=>	(string)$res->return->SPEDIZIONE_ID,
							"LINGUA_ISO639_ALPHA2"	=>	"",
							"SPEDIZIONE_ANNO"	=>	date("Y", strtotime($spedizione["data_spedizione"])),
						)
					);
					
					$res = $client2->brt_trackingbybrtshipmentid($var);
					
					if (isset($res->return))
					{
						$res = json_decode(json_encode($res), true);
						$trackingInfo = json_encode($res);
						
						$labelSpedizioniere = $this->getLabelSpedizioniere($res, "DESCRIZIONE");
						
						if ($labelSpedizioniere != "DATI SPEDIZ. TRASMESSI A BRT")
							$labelSpedizioniereFrontend = $labelSpedizioniere;
					}
				}
			}
			
			$spnModel->sValues(array(
				"struttura_info_tracking"			=>	$trackingInfo,
				"time_ultima_richiesta_tracking"	=>	time(),
				"label_spedizioniere"				=>	sanitizeHtml($labelSpedizioniere),
				"label_spedizioniere_frontend"		=>	sanitizeHtml($labelSpedizioniereFrontend),
			), "sanitizeDb");
			
			$spnModel->pUpdate((int)$idSpedizione);
			
			$this->scriviLogInfoTracking((int)$idSpedizione);
		}
	}
	
	public function consegnata($idSpedizione)
	{
		$spnModel = new SpedizioninegozioModel();
		
		$trackingInfo = $spnModel->getInfoTracking((int)$idSpedizione);
		
		if ($trackingInfo)
		{
			$trackingInfo = json_decode($trackingInfo, true);
			
			$labelSpedizioniere = $this->getLabelSpedizioniere($trackingInfo, "descrizione");
			
			if ($labelSpedizioniere == "CONSEGNATA")
			{
				$this->scriviLogConsegnata((int)$idSpedizione);
				
				return true;
			}
		}
		
		return false;
	}
	
	public function getDataConsegna($idSpedizione)
	{
		$spnModel = new SpedizioninegozioModel();
		
		$trackingInfo = $spnModel->getInfoTracking((int)$idSpedizione);
		
		if ($trackingInfo)
		{
			$trackingInfo = json_decode($trackingInfo, true);
			
			$dataConsegna = $this->getLabelSpedizioniere($trackingInfo, "data");
			$oraConsegna = $this->getLabelSpedizioniere($trackingInfo, "ora");
			
			if (preg_match('/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/',(string)$dataConsegna) && preg_match('/^[0-9]{1,2}\.[0-9]{1,2}$/',(string)$oraConsegna))
			{
				$dateTime = DateTime::createFromFormat("d.m.Y H.i", $dataConsegna." ".$oraConsegna);
			
				return $dateTime->format("Y-m-d H:i:s");
			}
		}
		
		return parent::getDataConsegna($idSpedizione);
	}
	
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
	
	public function gLabelCodicePagamento($valore)
	{
		return OpzioniModel::label("BRT_CODICE_PAGAMENTO", $valore);
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
	public function getStrutturaSpedizione($idS, $tipo = "create")
	{
		$spModel = new SpedizioninegozioModel();
			
		$record = $spModel->selectId($idS);
		
		if (!empty($record))
		{
			$record = htmlentitydecodeDeep($record);
			
			$colli = $spModel->getColli([(int)$idS]);
			$peso = $spModel->peso([(int)$idS]);
			
			$params = htmlentitydecodeDeep($this->params);
			
			$account = array(
				"userID"	=>	$params["codice_cliente"],
				"password"	=>	$params["password_cliente"],
			);
			
			if ($tipo == "create")
			{
				$jsonArray = array(
					"account"	=>	$account,
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
			}
			else if ($tipo == "delete")
			{
				$jsonArray = array(
					"account"	=>	$account,
					"deleteData"	=>	array(
						"senderCustomerCode"			=>	$params["codice_cliente"],
						"numericSenderReference"		=>	$record["riferimento_mittente_numerico"],
						"alphanumericSenderReference"	=>	$record["riferimento_mittente_alfa"],
					),
				);
			}
			else if ($tipo == "confirm")
			{
				$jsonArray = array(
					"account"	=>	$account,
					"confirmData"	=>	array(
						"senderCustomerCode"			=>	$params["codice_cliente"],
						"numericSenderReference"		=>	$record["riferimento_mittente_numerico"],
						"alphanumericSenderReference"	=>	$record["riferimento_mittente_alfa"],
					),
				);
			}
			
			return $jsonArray;
		}
		
		return [];
	}
	
	public function eliminaSpedizione($idS, SpedizioninegozioModel $spedizione = null)
	{
		if ($this->isAttivo())
		{
			$jsonArray = $this->getStrutturaSpedizione($idS, "delete");
			
			$result = true;
			
			if (SpedizioninegozioinfoModel::g(false)->getCodice($idS, "createResponse") != "")
			{
				$result = false;
				$response = $this->send("/shipments/delete", "PUT", $jsonArray);
			}
			
			if (
				$result === true
				||
				(
					isset($response["deleteResponse"]["executionMessage"]["code"]) &&
					(
						$response["deleteResponse"]["executionMessage"]["code"] >= 0 || 
						$response["deleteResponse"]["executionMessage"]["code"] == -151 ||
						strpos($response["deleteResponse"]["executionMessage"]["message"], "not found") !== false
					)
				)
			)
			{
				if (isset($response))
				{
					// Salvo il log dell'input e dell'output
					SpedizioninegozioinfoModel::g(false)->inserisci($idS, "deleteRequest", json_encode($jsonArray), "JSON");
					SpedizioninegozioinfoModel::g(false)->inserisci($idS, "deleteResponse", json_encode($response), "JSON");
				}
				
				return true;
			}
			else
			{
				$this->settaNoticeModel($spedizione, isset($response["deleteResponse"]) ? $response["deleteResponse"]["executionMessage"]["message"] : "Errore, API non funzionante");
				return false;
			}
		}
	}
	
	// Pronoto la spedizione al corriere per avere il numero di spedizione e l'etichetta
	public function prenotaSpedizione($idS, SpedizioninegozioModel $spedizione = null)
	{
		if ($this->isAttivo())
		{
			if ($this->eliminaSpedizione($idS, $spedizione))
			{
				$jsonArray = $this->getStrutturaSpedizione($idS);
				
				$result = $this->send("/shipments/shipment", "POST", $jsonArray);
				
				if (isset($result["createResponse"]) && $result["createResponse"]["executionMessage"]["code"] >= 0)
				{
					// Salvo il log dell'invio e dell'output
					SpedizioninegozioinfoModel::g(false)->inserisci($idS, "createRequest", json_encode($jsonArray), "JSON");
					SpedizioninegozioinfoModel::g(false)->inserisci($idS, "createResponse", json_encode($result), "JSON");
					
					if (isset($result["createResponse"]["labels"]["label"][0]))
						return new Data_Spedizioni_Result($result["createResponse"]["labels"]["label"][0]["trackingByParcelID"], "");
				}
				else
					$this->settaNoticeModel($spedizione, isset($result["createResponse"]) ? $result["createResponse"]["executionMessage"]["message"] : "Errore, API non funzionante");
			}
		}
		else
			$this->settaNoticeModel($spedizione, "Attenzione, il modulo spedizioniere ".$this->params["titolo"]. " non è attivo");
		
		return false;
	}
	
	// $idS array con gli ID delle spedizione da confermare
	// $idInvio id dell'invio
	public function confermaSpedizioni(array $idS, $idInvio)
	{
		$risultati = array();
		
		foreach ($idS as $id)
		{
			$jsonArray = $this->getStrutturaSpedizione($id, "confirm");
			
			$result = $this->send("/shipments/shipment", "PUT", $jsonArray);
			
			$errore = "";
			
			if (
				isset($result["confirmResponse"]) && 
				(
					$result["confirmResponse"]["executionMessage"]["code"] >= 0 ||
					strpos($result["confirmResponse"]["executionMessage"]["message"], "already been confirmed") !== false
				)
			)
			{
				// Salvo il log dell'invio e dell'output
				SpedizioninegozioinfoModel::g(false)->inserisci($id, "confirmRequest", json_encode($jsonArray), "JSON");
				SpedizioninegozioinfoModel::g(false)->inserisci($id, "confirmResponse", json_encode($result), "JSON");
				
				$this->scriviLogConfermata((int)$id);
			}
			else
				$errore = isset($result["confirmResponse"]["executionMessage"]["message"]) ? $result["confirmResponse"]["executionMessage"]["message"] : "Errore, API non funzionante";
			
			$risultati[$id] = new Data_Spedizioni_Result("",$errore);
		}
		
		return $risultati;
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
	
	public function getLabelNumeroSpedizione()
	{
		return gtext("ID collo BRT");
	}
	
	// Stampa o genera il segnacollo della spedizione
	// $returnPath se impostato su 1 restituisce il PDF del path del PDF
	public function segnacollo($idSpedizione, $returnPath = false)
	{
		$createResponse = SpedizioninegozioinfoModel::g(false)->getCodice($idSpedizione, "createResponse");
		
		if ($createResponse)
		{
			$createResponse = json_decode($createResponse, true);
			
			$pathSpedizione = $this->getLogPath((int)$idSpedizione)."/Pdf";
			
			if (!file_exists($pathSpedizione))
				return;
			
			$pdfFilesToMerge = [];

			foreach ($createResponse["createResponse"]["labels"]["label"] as $label)
			{
				$pathPdf = $pathSpedizione."/".$label["parcelID"].".pdf";
				
				$pdfDaMergiare[] = $pathPdf;
				
				FilePutContentsAtomic($pathPdf, base64_decode($label["stream"]));
			}
			
			$tipoOutput = $returnPath ? "F" : "I";
			
			if (Pdf::merge($pdfDaMergiare, "$pathSpedizione/Etichetta.pdf", $tipoOutput))
				return "$pathSpedizione/Etichetta.pdf";
		}
		
		return "";
	}
	
	// Verifica se le spedizioni di ID $ids sono confermabili
	public function spedizioniConfermabili(array $ids)
	{
		$date = new DateTime();
		
		$date->modify("-".v("minuti_attesa_bordero_brt")." minutes");
		
		$spnModel = new SpedizioninegozioModel();
		
		$spedizioniNonPronte = $spnModel->clear()->where(array(
			"in"	=>	array(
				"id_spedizione_negozio"	=>	forceIntDeep($ids),
			),
		))->sWhere(array(
			"data_pronta_invio > ?",
			array($date->format("Y-m-d H:i:s"))
		))->rowNumber();
		
		if ($spedizioniNonPronte > 0)
			return false;
		
		return true;
	}
	
	// Stampa il pdf del borderò dell'invio $id
	public function reportPdf($idInvio = 0)
	{
		$this->genericReportPdf($idInvio);
	}
	
	public function getCodiciFromToSegnacolli($idSpedizione)
	{
		$createResponse = SpedizioninegozioinfoModel::g(false)->getCodice($idSpedizione, "createResponse");
		
		if ($createResponse)
		{
			$createResponse = json_decode($createResponse, true);
			
			if (isset($createResponse["createResponse"]["parcelNumberFrom"]) && isset($createResponse["createResponse"]["parcelNumberTo"]))
				return array((int)$createResponse["createResponse"]["parcelNumberFrom"], (int)$createResponse["createResponse"]["parcelNumberTo"]);
		}
		
		return array("", "");
	}
	
	public function getUrlTracking($idSpedizione)
	{
		$spnModel = new SpedizioninegozioModel();
		
		$spedizione = $spnModel->selectId((int)$idSpedizione);
		
		$urlTracking = "";
		
		$params = htmlentitydecodeDeep($this->params);
		
		if (!empty($spedizione))
		{
			if ($spedizione["numero_spedizione"])
				return "https://vas.brt.it/vas/sped_det_show.hsm?ChiSono=".$spedizione["numero_spedizione"];
			else if ($spedizione["codice_bda"])
				return "https://vas.brt.it/vas/sped_RicDocMit_load.hsm?docmit=".$spedizione["riferimento_mittente_numerico"]."&rma=".$spedizione["riferimento_mittente_alfa"]."&ksu=".$params["codice_cliente"];
		}
		
		return $urlTracking;
	}
	
	public function decodeOutput($output)
	{
		return json_encode(json_decode($output, true),JSON_PRETTY_PRINT);
	}
}
