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
		
		$spedizione->addStrongCondition("update",'checkNotEmpty|',"codice_cliente");
	}
	
	public function gCodiceClienteLabel()
	{
		return "Api Key (Ship API)";
	}
	
	public function gPasswordLabel()
	{
		return "Secret Key (Ship API)";
	}
	
	public function gCodiceContrattoLabel()
	{
		return "Account Number (Ship API)";
	}
	
	public function gWrapCodiceContratto()
	{
		return array(
			null,
			null,
			"<div class='form_notice'>".gtext("Se più di uno, divedere i codici da virgola, senza spazi")."(Ex: 123456789,987654321)</div>"
		);
	}
	
	public function isAttivo()
	{
		if ($this->params["attivo"] && trim($this->params["codice_cliente"]) && trim($this->params["password_cliente"]) && trim($this->params["codice_contratto"]))
			return true;
		
		return false;
	}
	
	public function gCampiForm()
	{
		return 'titolo,modulo,attivo,usa_piattaforma_sandbox,codice_cliente,password_cliente,codice_contratto,api_key_track,api_secret_track,ragione_sociale_cliente,persona_riferimento_cliente,telefono_cliente,indirizzo_cliente,citta,provincia_cliente,cap_cliente,nazione_cliente,descrizione_generica_merce';
	}
	
	public function gCampiSpedizione()
	{
		return array('codice_cliente', 'tipo_servizio', 'modalita_ritiro', 'formato_etichetta_pdf', 'descrizione_generica_merce');
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
	
	public function gCodiceCliente()
	{
		return $this->getParam('codice_contratto');
	}
	
	protected function gPrimoCodiceContratto()
	{
		$codici = $this->getParam('codice_contratto');
		$codiciArray = explode(",", $codici);
		
		if (count($codiciArray) > 0)
			return $codiciArray[0];
		
		return "";
	}
	
	public function gFormatiEtichetta()
	{
		return ['PAPER_4X6', 'PAPER_4X8', 'PAPER_4X9', 'PAPER_4X675', 'PAPER_7X47', 'PAPER_LETTER', 'PAPER_85X11_TOP_HALF_LABEL'];
	}
	
	// Inserisci i valori di default del corriere
	public function inserisciValoriDefaultCorriere(SpedizioninegozioModel $spedizione)
	{
		$campiSpedizione = $this->gCampiSpedizione();
		
		$spedizione->values = array();
		$spedizione->values["tipo_servizio"] = OpzioniModel::primoCodice("FEDEX_TIPO_SERVIZIO");
		$spedizione->values["modalita_ritiro"] = OpzioniModel::primoCodice("FEDEX_MOD_RITIRO");
		// $spedizione->values["codice_pagamento_contrassegno"] = OpzioniModel::primoCodice("FEDEX_CODICE_PAGAMENTO");
		$spedizione->values["formato_etichetta_pdf"] = $this->gFormatiEtichetta()[0];
		$spedizione->values["descrizione_generica_merce"] = sanitizeDb($this->getParam('descrizione_generica_merce'));
		$spedizione->values["codice_cliente"] = sanitizeDb($this->gPrimoCodiceContratto());
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
				$valoreTotale = 0;
				
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
					
					if ($collo['valore'] > 0)
					{
						$valore = number_format($collo["valore"],2,".","");
						$valoreTotale += $valore;
						
						$tempCollo["declaredValue"] = array(
							"amount"	=>	$valore,
							"currency"	=>	v("codice_valuta"),
						);
					}
					
					$jsonArrayColli[] = $tempCollo;
				}
				
				$jsonArray = [
					'labelResponseOptions' => 'LABEL',
					'accountNumber' => [
						'value' => $record["codice_cliente"],
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
					
					$jsonArray['requestedShipment']['shipmentSpecialServices'] = [
						'specialServiceTypes' => [
							'ELECTRONIC_TRADE_DOCUMENTS',
						],
					];
				}
				
				if ($valoreTotale > 0)
				{
					$jsonArray["totalDeclaredValue"] = array(
						"amount"	=>	number_format($valoreTotale,2,".",""),
						"currency"	=>	v("codice_valuta"),
					);
				}
			}
			else if ($tipo == "delete")
			{
				if ($record["numero_spedizione"])
				{
					$jsonArray = [
						'accountNumber' => [
							'value' => $record["codice_cliente"],
						],
						'trackingNumber' =>	$record["numero_spedizione"],
					];
				}
				else
					return [];
			}
			else if ($tipo == "quote")
			{
				$jsonArrayColli = array();
				$pesoTotale = 0;
				$valoreTotale = 0;
				
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
					
					if ($collo['valore'] > 0)
					{
						$valore = number_format($collo["valore"],2,".","");
						$valoreTotale += $valore;
						
						$tempCollo["declaredValue"] = array(
							"amount"	=>	$valore,
							"currency"	=>	v("codice_valuta"),
						);
					}
					
					$jsonArrayColli[] = $tempCollo;
				}
				
				$jsonArray = [
					'accountNumber' => [
						'value' => $record["codice_cliente"],
					],
					'rateRequestControlParameters' => [
						'returnTransitTimes' => true,
					],
					'requestedShipment' => [
						'shipDateStamp' => date('Y-m-d'),
						'pickupType' => $record["modalita_ritiro"],
						'serviceType' => $record["tipo_servizio"],
						'packagingType' => 'YOUR_PACKAGING',
						'totalPackageCount' => count($colli),
						'totalWeight' => $pesoTotale,
						'rateRequestType' => [
							'ACCOUNT',
							'LIST',
						],
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
						'recipient' => [
							'contact' => [
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
						],
						'shippingChargesPayment' => [
							'paymentType' => 'SENDER',
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
					
					$jsonArray['requestedShipment']['shipmentSpecialServices'] = [
						'specialServiceTypes' => [
							'ELECTRONIC_TRADE_DOCUMENTS',
						],
					];
				}
				
				if ($valoreTotale > 0)
				{
					$jsonArray["requestedShipment"]["totalDeclaredValue"] = array(
						"amount"	=>	number_format($valoreTotale,2,".",""),
						"currency"	=>	v("codice_valuta"),
					);
				}
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
	
	protected function estraiCostoStimato($result)
	{
		return 10;
	}
	
	public function richiediCosto($idS, ?SpedizioninegozioModel $spedizione = null)
	{
		if ($this->isAttivo())
		{
			list($accessToken, $errore) = $this->getSavedToken();
			
			if ($accessToken && !$errore)
			{
				$jsonArray = $this->getStrutturaSpedizione($idS, "quote");
				
				$result = $this->requestJson('/rate/v1/rates/quotes', 'POST', $jsonArray, $accessToken);
				
				SpedizioninegozioinfoModel::g(false)->inserisci($idS, "quotesRequest", $this->oscuraPassword(json_encode($jsonArray)), "JSON");
				SpedizioninegozioinfoModel::g(false)->inserisci($idS, "quotesResponse", json_encode($result), "JSON");
				
				$errore = $this->getError($result);
				
				if (!trim($errore))
				{
					return trim($this->estraiCostoStimato($result));
				}
				else
					$this->settaNoticeModel($spedizione, $errore);
			}
			$this->settaNoticeModel($spedizione, "Errore, API non funzionante: ".$errore);
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
		$spnModel = new SpedizioninegozioModel();
		
		$spedizione = $spnModel->selectId((int)$idSpedizione);
		
		$urlTracking = "";
		
		if (!empty($spedizione) && $spedizione["numero_spedizione"])
		{
			$lingua = strtolower($spedizione["lingua"] ?: "it");
			$nazione = strtoupper($spedizione["nazione"] ?: "IT");
			$nazioneCliente = strtolower($this->getParam("nazione_cliente") ?: "IT");
			
			$urlTracking = "https://www.fedex.com/wtrk/track/?action=track"
				. "&tracknumbers=" . urlencode($spedizione["numero_spedizione"])
				. "&locale=" . urlencode($lingua . "_" . $nazione)
				. "&cntry_code=" . urlencode($nazioneCliente);
		}
		
		return $urlTracking;
	}
	
	// Chiama i server del corriere e salva le informazioni del tracking nella spedizione
	public function getInfo($idSpedizione)
	{
		$spnModel = new SpedizioninegozioModel();
		
		$spedizione = $spnModel->selectId((int)$idSpedizione);
		
		if (!$this->checkTimeInfo($spedizione))
			return;
		
		$trackingInfo = $labelSpedizioniere = $labelSpedizioniereFrontend = "";
		
		if (!empty($spedizione) && $spedizione["numero_spedizione"])
		{
			if ((int)$this->getParam("usa_piattaforma_sandbox"))
			{
				$trackingInfo = htmlentitydecode(v("sandbox_json_tracking_response_example"));
				$response = json_decode($trackingInfo, true);
			}
			else
			{
				list($accessToken, $errore) = $this->getSavedToken("fedex_get_token_saved_json_track_api", "api_key_track", "api_secret_track");
				
				if (!$accessToken || $errore)
					return;
				
				$jsonArray = [
					"includeDetailedScans" => true,
					"trackingInfo" => [[
						"trackingNumberInfo" => [
							"trackingNumber" => $spedizione["numero_spedizione"],
						],
					]],
				];
				
				$response = $this->requestJson('/track/v1/trackingnumbers', 'POST', $jsonArray, $accessToken);
				$trackingInfo = json_encode($response);
			}
			
			if (isset($response) && is_array($response))
			{
				$labelSpedizioniere = $this->getLabelSpedizioniere($response);
				$codiceSpedizioniere = $this->getLabelSpedizioniere($response, "code");
				
				if (!in_array($codiceSpedizioniere, $this->codiciTrackingDaNascondereFrontend(), true))
					$labelSpedizioniereFrontend = $labelSpedizioniere;
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
			$codiceSpedizioniere = $this->getLabelSpedizioniere($trackingInfo, "code");
			$codiceDerivatoSpedizioniere = $this->getLabelSpedizioniere($trackingInfo, "derivedCode");
			$codiceEventoSpedizioniere = $this->getLabelSpedizioniere($trackingInfo, "eventType");
			$codiceStatoEventoSpedizioniere = $this->getLabelSpedizioniere($trackingInfo, "derivedStatusCode");
			
			if (in_array("DL", array($codiceSpedizioniere, $codiceDerivatoSpedizioniere, $codiceEventoSpedizioniere, $codiceStatoEventoSpedizioniere), true))
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
			
			if (is_array($trackingInfo))
			{
				$trackResult = $trackingInfo["output"]["completeTrackResults"][0]["trackResults"][0] ?? [];
				
				if (isset($trackResult["dateAndTimes"]) && is_array($trackResult["dateAndTimes"]))
				{
					foreach ($trackResult["dateAndTimes"] as $dateAndTime)
					{
						if (($dateAndTime["type"] ?? "") == "ACTUAL_DELIVERY" && !empty($dateAndTime["dateTime"]))
						{
							$dateTime = new DateTime($dateAndTime["dateTime"]);
							
							return $dateTime->format("Y-m-d H:i:s");
						}
					}
				}
				
				if (isset($trackResult["scanEvents"]) && is_array($trackResult["scanEvents"]))
				{
					foreach ($trackResult["scanEvents"] as $evento)
					{
						if (($evento["eventType"] ?? "") == "DL" && !empty($evento["date"]))
						{
							$dateTime = new DateTime($evento["date"]);
							
							return $dateTime->format("Y-m-d H:i:s");
						}
					}
				}
			}
		}
		
		return parent::getDataConsegna($idSpedizione);
	}
	
	protected function codiciTrackingDaNascondereFrontend()
	{
		return ["OC"];
	}
	
	public function getLabelSpedizioniere($trackingInfo, $campo = "eventDescription")
	{
		if (is_string($trackingInfo))
			$trackingInfo = json_decode($trackingInfo, true);
		
		if (!is_array($trackingInfo))
			return "";
		
		$trackResult = $trackingInfo["output"]["completeTrackResults"][0]["trackResults"][0] ?? [];
		
		if (isset($trackResult["scanEvents"]) && is_array($trackResult["scanEvents"]))
		{
			foreach ($trackResult["scanEvents"] as $evento)
			{
				if (isset($evento[$campo]) && trim((string)$evento[$campo]) !== "")
					return (string)$evento[$campo];
			}
		}
		
		if (isset($trackResult["latestStatusDetail"][$campo]))
			return (string)$trackResult["latestStatusDetail"][$campo];
		
		if (isset($trackResult[$campo]))
			return (string)$trackResult[$campo];
		
		if ($campo == "eventDescription" && isset($trackResult["latestStatusDetail"]["description"]))
			return (string)$trackResult["latestStatusDetail"]["description"];
		
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
	
	public function permettiValoreCollo()
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
	
	protected function getSavedToken($cacheVariable = "fedex_get_token_saved_json", $apiKeyParam = "codice_cliente", $secretKeyParam = "password_cliente"): array
	{
		if (v($cacheVariable))
		{
			$tokenResponse = json_decode(htmlentitydecode(v($cacheVariable)), true);
			
			if (
				is_array($tokenResponse) &&
				!empty($tokenResponse['access_token']) &&
				(int)($tokenResponse['expires_at'] ?? 0) > time()
			)
			{
				return array($tokenResponse['access_token'], "");
			}
		}
		
		return $this->getToken($apiKeyParam, $secretKeyParam, $cacheVariable);
	}
	
	protected function getToken($apiKeyParam = "codice_cliente", $secretKeyParam = "password_cliente", $cacheVariable = "fedex_get_token_saved_json"): array
	{
		$apiKey = $this->getParam($apiKeyParam);
		$secretKey = $this->getParam($secretKeyParam);

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
		
		if (!is_array($response))
			return array("", 'Risposta FedEx OAuth non JSON. HTTP ' . $httpCode . ': ' . $body);
		
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
		
		$response['created_at'] = time();
		$response['expires_at'] = time() + (int)($response['expires_in'] ?? 3600) - 120;
		
		VariabiliModel::setValore($cacheVariable, json_encode($response));
		
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
	
	public function permettiRichiestaSpese()
	{
		return true;
	}
}
