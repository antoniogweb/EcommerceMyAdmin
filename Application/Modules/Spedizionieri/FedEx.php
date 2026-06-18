<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2025  Antonio Gallo (info@laboratoriolibero.com)
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

class FedEx extends Spedizioniere
{
	protected $condizioniCampi = array(
		"lunghezzaMax"	=>	array(
			"ragione_sociale"	=>	35,
			"indirizzo"			=>	35,
			"citta"				=>	35,
			"cap"				=>	10,
// 			"provincia"			=>	2,
			"codice_bda"		=>	11,
			"contrassegno"		=>	10,
			"email"				=>	80,
			"telefono"			=>	15
		),
	);
	
	protected function condizioniSpecificheCorriere(SpedizioninegozioModel $spedizione)
	{
		$nazione = Params::$arrayToValidate["nazione"] ?? $_POST["nazione"] ?? "IT";
		
		if ($nazione != "IT")
			$spedizione->addStrongCondition("update",'checkNotEmpty|',"descrizione_generica_merce");
	}
	
	public function gCodiceClienteLabel()
	{
		return "Api Key";
	}
	
	public function gPasswordLabel()
	{
		return "Secret Key";
	}
	
	public function gCodiceContrattoLabel()
	{
		return "Account Number";
	}
	
	public function isAttivo()
	{
		if ($this->params["attivo"] && trim($this->params["codice_cliente"]) && trim($this->params["password_cliente"]) && trim($this->params["codice_contratto"]))
			return true;
		
		return false;
	}
	
	public function gCampiForm()
	{
		return 'titolo,modulo,attivo,usa_piattaforma_sandbox,codice_cliente,password_cliente,codice_contratto,ragione_sociale_cliente,persona_riferimento_cliente,telefono_cliente,indirizzo_cliente,citta,provincia_cliente,cap_cliente,nazione_cliente,descrizione_generica_merce';
	}
	
	public function gCampiSpedizione()
	{
		return array('tipo_servizio', 'modalita_ritiro', 'codice_pagamento_contrassegno', 'formato_etichetta_pdf', 'descrizione_generica_merce');
	}
	
	public function gCampiIndirizzo()
	{
		return array('ragione_sociale_2');
	}
	
	public function gModalitaRitiro()
	{
		return OpzioniModel::codice("FEDEX_MOD_RITIRO");
	}
	
	public function gTipoServizio()
	{
		return OpzioniModel::codice("FEDEX_TIPO_SERVIZIO");
	}
	
	public function gCodiciPagamentoContrassegno()
	{
		return OpzioniModel::codice("FEDEX_CODICE_PAGAMENTO");
	}
	
	public function gLabelCodicePagamento($valore)
	{
		return OpzioniModel::label("FEDEX_CODICE_PAGAMENTO", $valore);
	}
	
	public function gFormatiEtichetta()
	{
		return ['PAPER_4X6', 'PAPER_4X8', 'PAPER_4X9', 'PAPER_4X675', 'PAPER_7X47', 'PAPER_LETTER'];
	}
	
	// Inserisci i valori di default del corriere
	public function inserisciValoriDefaultCorriere(SpedizioninegozioModel $spedizione)
	{
		$campiSpedizione = $this->gCampiSpedizione();
		
		$spedizione->values = array();
		$spedizione->values["tipo_servizio"] = OpzioniModel::primoCodice("FEDEX_TIPO_SERVIZIO");
		$spedizione->values["modalita_ritiro"] = OpzioniModel::primoCodice("FEDEX_MOD_RITIRO");
		$spedizione->values["codice_pagamento_contrassegno"] = OpzioniModel::primoCodice("FEDEX_CODICE_PAGAMENTO");
		$spedizione->values["formato_etichetta_pdf"] = $this->gFormatiEtichetta()[0];
		$spedizione->values["descrizione_generica_merce"] = sanitizeDb($this->getParam('descrizione_generica_merce'));
	}
	
	public function eliminaSpedizione($idS, ?SpedizioninegozioModel $spedizione = null)
	{
		if ($this->isAttivo())
		{
			$jsonArray = $this->getStrutturaSpedizione($idS, "delete");

			if (SpedizioninegozioinfoModel::g(false)->getCodice($idS, "createResponse") != "")
			{
				list($accessToken, $errore) = $this->getSavedToken();
				
				if ($accessToken && !$errore)
				{
					if (!empty($jsonArray))
					{
						$result = $this->requestJson('/ship/v1/shipments/cancel', 'PUT', $jsonArray, $accessToken);
						
						// print_r($result);die();
						// Salvo il log dell'input e dell'output
						SpedizioninegozioinfoModel::g(false)->inserisci($idS, "deleteRequest", $this->oscuraPassword(json_encode($jsonArray)), "JSON");
						SpedizioninegozioinfoModel::g(false)->inserisci($idS, "deleteResponse", json_encode($result), "JSON");
						
						$errore = $this->getError($result);
						
						if (!trim($errore))
							return true;
						else
							$this->settaNoticeModel($spedizione, $errore);
					}
					else
						return true;
				}
				else
					$this->settaNoticeModel($spedizione, "Errore, API non funzionante: ".$errore);
			}
			else
				return true;
		}
		
		return false;
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
			
			// print_r($colli);die();
			
			$params = htmlentitydecodeDeep($this->params);
			
			$account = array(
				"userID"	=>	$params["codice_cliente"],
				"password"	=>	$params["password_cliente"],
			);
			
			if ($tipo == "create")
			{
				$jsonArrayColli = array();
				$pesoTotale = 0;
				
				foreach ($colli as $collo)
				{
					$pesoTotale += number_format($collo["peso"],1,".","");
					
					$tempCollo = [
						'weight' => [
							'units' => 'KG',
							'value' => number_format($collo["peso"],1,".",""),
						],
					];
					
					if ($collo['lunghezza'] > 0 && $collo['profondita'] > 0 && $collo['altezza'] > 0)
					{
						$tempCollo['dimensions'] = [
							'length' => (int)ceil($collo['lunghezza']),
							'width' => (int)ceil($collo['profondita']),
							'height' => (int)ceil($collo['altezza']),
							'units' => 'CM',
						];
					}
					
					$jsonArrayColli[] = $tempCollo;
				}
				
				$jsonArray = [
					'labelResponseOptions' => 'LABEL',
					'accountNumber' => [
						'value' => $this->getParam("codice_contratto"),
					],
					'requestedShipment' => [
						'shipDatestamp' => date('Y-m-d'),
						'pickupType' => $record["modalita_ritiro"],
						'serviceType' => $record["tipo_servizio"],
						'packagingType' => 'YOUR_PACKAGING',
						'totalPackageCount' => count($colli),
						'totalWeight' => $pesoTotale,
						'shipper' => [
							'contact' => [
								'personName' => $this->getParam("persona_riferimento_cliente"),
								'companyName' => $this->getParam("ragione_sociale_cliente"),
								'phoneNumber' => $this->getParam("telefono_cliente"),
							],
							'address' => [
								'streetLines' => [$this->getParam("indirizzo_cliente")],
								'city' => $this->getParam("citta"),
								'stateOrProvinceCode' => $this->getParam("provincia_cliente"),
								'postalCode' => $this->getParam("cap_cliente"),
								'countryCode' => $this->getParam("nazione_cliente"),
							],
						],
						'recipients' => [[
							'contact' => [
								// 'personName' => $record["ragione_sociale_2"],
								'companyName' => $record["ragione_sociale_2"],
								'phoneNumber' => $record["telefono"],
							],
							'address' => [
								'streetLines' => [$record["indirizzo"]],
								'city' => $record["citta"],
								'stateOrProvinceCode' => $record["nazione"] == "IT" ? $record["provincia"] : "",
								'postalCode' => $record["cap"],
								'countryCode' => $record["nazione"],
							],
						]],
						'shippingChargesPayment' => [
							'paymentType' => 'SENDER',
						],
						'labelSpecification' => [
							'imageType' => 'PDF',
							'labelStockType' => $record["formato_etichetta_pdf"],
						],
						'requestedPackageLineItems' => $jsonArrayColli,
					],
				];
				
				if ($record['nazione'] != $this->getParam('nazione_cliente')) {
					$jsonArray['requestedShipment']['customsClearanceDetail'] = [
						'commodities' => [[
							'description' => trim($record['descrizione_generica_merce']) ? $record['descrizione_generica_merce'] : $this->getParam('descrizione_generica_merce'),
						]],
					];
				}
			}
			else if ($tipo == "delete")
			{
				if ($record["numero_spedizione"])
				{
					$jsonArray = [
						'accountNumber' => [
							'value' => $this->getParam("codice_contratto"),
						],
						'trackingNumber' =>	$record["numero_spedizione"],
					];
				}
				else
					return [];
			}
			
			return $jsonArray;
		}
		
		return [];
	}
	
	protected function estraiTrckingNumber(array $response): string
    {
        return $response['output']['transactionShipments'][0]['masterTrackingNumber'] ?? '';
    }
	
	// Pronoto la spedizione al corriere per avere il numero di spedizione e l'etichetta
	public function prenotaSpedizione($idS, ?SpedizioninegozioModel $spedizione = null)
	{
		if ($this->isAttivo())
		{
			$record = SpedizioninegozioModel::g(false)->selectId((int)$idS);
			
			if (!empty($record) && !$this->contrassegnoZero($record["contrassegno"]))
			{
				$this->settaNoticeModel($spedizione, "FedEx non supporta spedizioni in contrassegno");
				
				return false;
			}
			
			if ($this->eliminaSpedizione($idS, $spedizione))
			{
				list($accessToken, $errore) = $this->getSavedToken();
				
				if ($accessToken && !$errore)
				{
					$jsonArray = $this->getStrutturaSpedizione($idS);
					
					$result = $this->requestJson('/ship/v1/shipments', 'POST', $jsonArray, $accessToken);
					
 					// Salvo il log dell'invio e dell'output
					SpedizioninegozioinfoModel::g(false)->inserisci($idS, "createRequest", $this->oscuraPassword(json_encode($jsonArray)), "JSON");
					SpedizioninegozioinfoModel::g(false)->inserisci($idS, "createResponse", json_encode($result), "JSON");
					
					$errore = $this->getError($result);
					$trackingNumber = trim($this->estraiTrckingNumber($result));
					
					if (!trim($errore) && $trackingNumber)
					{
						return new Data_Spedizioni_Result($trackingNumber, "");
					}
					else
						$this->settaNoticeModel($spedizione, $errore);
				}
				else
					$this->settaNoticeModel($spedizione, "Errore, API non funzionante: ".$errore);
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
			$errore = "";
			
			$this->scriviLogConfermata((int)$id);
			$risultati[$id] = new Data_Spedizioni_Result("",$errore);
		}
		
		return $risultati;
	}
	
	// Imposta la spedizione come confermata anche se la conferma è andata in errore
	public function impostaConfermatoAncheSeErrore()
	{
		return false;
	}
	
	// Stampa o genera il segnacollo della spedizione
	// $returnPath se impostato su 1 restituisce il PDF del path del PDF
	public function segnacollo($idSpedizione, $returnPath = false)
	{
		$createResponse = SpedizioninegozioinfoModel::g(false)->getCodice($idSpedizione, "createResponse");
		
		if ($createResponse)
		{
			$createResponse = json_decode($createResponse, true);
			
			// print_r($createResponse);
			$pathSpedizione = $this->getLogPath((int)$idSpedizione)."/Pdf";
			
			if (!file_exists($pathSpedizione))
				return;
			
			$pdfFilesToMerge = [];
			
			foreach ($createResponse['output']['transactionShipments'][0]['pieceResponses'] as $label)
			{
				foreach ($label["packageDocuments"] as $documento)
				{
					if ($documento["docType"] != "PDF")
						continue;
					
					$pathPdf = $pathSpedizione."/".$label["trackingNumber"].".pdf";
					
					$pdfDaMergiare[] = $pathPdf;
					
					FilePutContentsAtomic($pathPdf, base64_decode($documento["encodedLabel"]));
				}
			}
			
			$tipoOutput = $returnPath ? "F" : "I";
			
			if (Pdf::merge($pdfDaMergiare, "$pathSpedizione/Etichetta.pdf", $tipoOutput))
				return "$pathSpedizione/Etichetta.pdf";
		}
		
		return "";
	}
	
	// Stampa il pdf del borderò dell'invio $id
	public function reportPdf($idInvio = 0)
	{
		$this->genericReportPdf($idInvio);
	}
	
	public function getUrlTracking($idSpedizione)
	{
		
	}
	
	// Chiama i server del corriere e salva le informazioni del tracking nella spedizione
	public function getInfo($idSpedizione)
	{
		
	}
	
	public function consegnata($idSpedizione)
	{
		
	}
	
	public function getDataConsegna($idSpedizione)
	{
		
	}
	
	public function getLabelSpedizioniere($trackingInfo, $campo = "Stato")
	{
		
		
		return "";
	}
	
	public function decodeOutput($output)
	{
		return json_encode(json_decode($output, true),JSON_PRETTY_PRINT);
	}
	
	public function permettiDimensioni()
	{
		return true;
	}
	
	protected function getUrl()
	{
		$sandbox = (int)$this->getParam("usa_piattaforma_sandbox");
		
		return $sandbox ? v("url_api_fedex_sandbox") : v("url_api_fedex");
	}
	
	protected function requestJson(string $path, string $method, array $payload, string $accessToken): array
    {
		$ch = curl_init($this->getUrl() . $path);

		curl_setopt_array($ch, [
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_ENCODING => '',
			CURLOPT_HTTPHEADER => [
				'Content-Type: application/json',
				'Accept: application/json',
				'Authorization: Bearer ' . $accessToken,
				'x-customer-transaction-id: test-' . date('YmdHis'),
			],
			CURLOPT_POSTFIELDS => json_encode($payload),
		]);

		$body = curl_exec($ch);
		$curlError = curl_error($ch);
		$httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($body === false) {
			throw new RuntimeException('Errore cURL FedEx: ' . $curlError);
		}

		$response = json_decode($body, true);

		if (!is_array($response)) {
			throw new RuntimeException('Risposta FedEx non JSON. HTTP ' . $httpCode . ': ' . $body);
		}

		$response['_http_code'] = $httpCode;

		return $response;
    }
	
	protected function getSavedToken(): array
	{
		if (v("fedex_get_token_saved_json"))
		{
			$tokenResponse = json_decode(htmlentitydecode(v("fedex_get_token_saved_json")), true);
			
			if (
				is_array($tokenResponse) &&
				!empty($tokenResponse['access_token']) &&
				(int)($tokenResponse['expires_at'] ?? 0) > time()
			)
			{
				return array($tokenResponse['access_token'], "");
			}
		}
		
		return $this->getToken();
	}
	
	protected function getToken(): array
	{
		$apiKey = $this->getParam("codice_cliente");
		$secretKey = $this->getParam("password_cliente");

		$ch = curl_init($this->getUrl() . '/oauth/token');

		curl_setopt_array($ch, [
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTPHEADER => [
				'Content-Type: application/x-www-form-urlencoded',
			],
			CURLOPT_POSTFIELDS => http_build_query([
				'grant_type' => 'client_credentials',
				'client_id' => $apiKey,
				'client_secret' => $secretKey,
			]),
		]);

		$body = curl_exec($ch);
		$curlError = curl_error($ch);
		$httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		if ($body === false)
			return array("", 'Errore cURL FedEx OAuth: ' . $curlError);
		
		$response = json_decode($body, true);
		
		// print_r($response);die();
		
		if (!is_array($response))
			return array("", 'Risposta FedEx OAuth non JSON. HTTP ' . $httpCode . ': ' . $body);
		
		// if ($httpCode < 200 || $httpCode >= 300)
		// 	return array("", 'Errore FedEx OAuth HTTP ' . $httpCode . ': ' . json_encode($response));
		
		if (empty($response['access_token']))
		{
			if (isset($response["errors"]))
			{
				$errore = $this->getError($response);
				
				return array("", sanitizeHtml($errore));
			}
			else
				return array("", 'Token non presente nella risposta FedEx: ' . json_encode($response));
		}
		
		// print_r($response);
		
		$response['created_at'] = time();
		$response['expires_at'] = time() + (int)($response['expires_in'] ?? 3600) - 120;
		
		VariabiliModel::setValore("fedex_get_token_saved_json", json_encode($response));
		
		return array($response['access_token'], "");
	}
	
	protected function getError($response)
	{
		if (isset($response["errors"]))
		{
			$erroreArray = array();
			
			foreach ($response["errors"] as $error)
			{
				$erroreArray[] = ($error["code"] ?? "").": ".($error["message"] ?? "");
			}
			
			return implode("<br />", $erroreArray);
		}
		
		return "";
	}
	
	protected function contrassegnoZero($valore)
	{
		$valore = trim((string)$valore);
		
		if ($valore === "")
			return true;
		
		return preg_match('/^0+(?:[,.]0+)?$/', $valore);
	}
	
	public function oscuraPassword($input)
	{
		return str_replace($this->getParam("codice_contratto"),"XXXX", $input);
	}
}
