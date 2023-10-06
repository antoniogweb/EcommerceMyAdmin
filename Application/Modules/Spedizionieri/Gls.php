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

class Gls extends Spedizioniere
{
	protected $condizioniCampi = array(
		"lunghezzaMax"	=>	array(
			"ragione_sociale"	=>	35,
			"indirizzo"			=>	35,
			"citta"				=>	30,
			"cap"				=>	5,
			"provincia"			=>	2,
			"codice_bda"		=>	11,
			"contrassegno"		=>	10,
			"importo_assicurazione"	=>	11,
			"note_interne"		=>	70,
			"assicurazione_integrativa"	=>	1,
		),
	);
	
	public function isAttivo()
	{
		if ($this->params["attivo"] && trim($this->params["codice_cliente"]) && trim($this->params["password_cliente"]) && trim($this->params["codice_sede"]) && trim($this->params["codice_contratto"]))
			return true;
		
		return false;
	}
	
	public function gCampiForm()
	{
		return 'titolo,ragione_sociale_cliente,modulo,attivo,codice_cliente,password_cliente,codice_sede,codice_contratto';
	}
	
	public function gCampiSpedizione()
	{
		return array('codice_pagamento_contrassegno', 'codice_bda', 'importo_assicurazione', 'assicurazione_integrativa', 'formato_etichetta_pdf');
	}
	
	public function gCodiciPagamentoContrassegno()
	{
		return OpzioniModel::codice("GLS_CODICE_PAGAMENTO");
	}
	
	public function gLabelCodicePagamento($valore)
	{
		return OpzioniModel::label("GLS_CODICE_PAGAMENTO", $valore);
	}
	
	public function gFormatiEtichetta()
	{
		return ['A6', 'A5'];
	}
	
	public function gAssicurazioneIntegrativa()
	{
		return OpzioniModel::codice("GLS_ASSICURAZIONE_INTEGRATIVA");
	}
	
	public function gSelectServizi()
	{
		$op = new OpzioniModel();
		
		return $op->clear()->select("valore,concat(valore,' - ',titolo) as label")->where(array(
			"codice"	=>	"GLS_SERVIZI_AGGIUNTIVI",
			"attivo"	=>	1,
		))->toList("valore", "aggregate.label")->findAll();
		
// 		return OpzioniModel::codice("GLS_SERVIZI_AGGIUNTIVI");
	}
	
	// Inserisci i valori di default del corriere
	public function inserisciValoriDefaultCorriere(SpedizioninegozioModel $spedizione)
	{
		$campiSpedizione = $this->gCampiSpedizione();
		
		$spedizione->values = array();
		$spedizione->values["codice_pagamento_contrassegno"] = OpzioniModel::primoCodice("GLS_CODICE_PAGAMENTO");
		$spedizione->values["assicurazione_integrativa"] = OpzioniModel::primoCodice("GLS_ASSICURAZIONE_INTEGRATIVA");
		$spedizione->values["formato_etichetta_pdf"] = $this->gFormatiEtichetta()[0];
	}
	
	// Restituisce la spedizione pronta per essere inviata al corriere come array associativo
	public function getStrutturaSpedizione(array $idSpedizioni, $closeWorkDate = false)
	{
		$spModel = new SpedizioninegozioModel();
		$spnsModel = new SpedizioninegozioserviziModel();
		
		$params = htmlentitydecodeDeep($this->params);
		
		$spedizioni = $spModel->clear()->where(array(
			"in"	=>	array(
				"id_spedizione_negozio"	=>	forceIntDeep($idSpedizioni),
			),
		))->send(false);
		
		if (count($spedizioni) > 0)
		{
			$parcelArray = [];
			
			foreach ($spedizioni as $record)
			{
				$record = htmlentitydecodeDeep($record);
				
				$colli = $spModel->getColli([(int)$record["id_spedizione_negozio"]]);
				
				$serviziAccessori = $spnsModel->gServiziSpedizione((int)$record["id_spedizione_negozio"]);
				
				$contrassegnoIndicato = $importoAssicurazioneIndicato = false;
				
				foreach ($colli as $collo)
				{
					$temp = array(
						"CodiceContrattoGls"	=>	$params["codice_contratto"],
						"RagioneSociale"		=>	$record["ragione_sociale"],
						"Indirizzo"				=>	$record["indirizzo"],
						"Localita"				=>	$record["citta"],
						"Zipcode"				=>	$record["cap"],
						"Provincia"				=>	$record["provincia"],
						"PesoReale"				=>	number_format($collo["peso"],1,",",""),
						"TipoPorto"				=>	"f",
						"FormatoPdf"			=>	$record["formato_etichetta_pdf"],
						"GeneraPdf"				=>	4,
						"ContatoreProgressivo"	=>	$collo["id_spedizione_negozio_collo"],
						"Colli"					=>	1,
						"TelefonoDestinatario"	=>	$record["telefono"],
					);
					
					if ($record["codice_bda"])
						$temp["Bda"] = $record["codice_bda"];
					
					if ($record["contrassegno"] > 0 && !$contrassegnoIndicato)
					{
						$valore = $record["contrassegno"];
						$temp["ImportoContrassegno"] = number_format($valore,2,",","");
						$temp["ModalitaIncasso"] = $record["codice_pagamento_contrassegno"];
						
						$contrassegnoIndicato = true;
					}
					
					if ($record["note_interne"])
						$temp["NoteSpedizione"] = $record["note_interne"];
					
					if ($record["importo_assicurazione"] > 0 && !$importoAssicurazioneIndicato)
					{
						$valore = $record["importo_assicurazione"];
						$temp["Assicurazione"] = number_format($valore,2,",","");
						
						$importoAssicurazioneIndicato = true;
					}
					
					if ($record["assicurazione_integrativa"])
						$temp["AssicurazioneIntegrativa"] = $record["assicurazione_integrativa"];
					
					if (count($serviziAccessori) > 0)
						$temp["ServiziAccessori"] = implode(",", $serviziAccessori);
					
					$parcelArray[] = $temp;
				}
			}
			
			$info = $this->getStrutturaDatiContratto();
			
			if ($closeWorkDate)
				$info["CloseWorkDayResult"] = "S";
			
			$info["Parcel"] = $parcelArray;
			
			$xmlArray = array(
				"Info"	=>	$info,
			);
			
			return $xmlArray;
		}
		
		return [];
	}
	
	private function getStrutturaDatiContratto()
	{
		$params = htmlentitydecodeDeep($this->params);
		
		return array(
			"SedeGls"				=>	$params["codice_sede"],
			"CodiceClienteGls"		=>	$params["codice_cliente"],
			"PasswordClienteGls"	=>	$params["password_cliente"],
		);
	}
	
	// Elimina la spedizione $idS
	public function eliminaSpedizione($idS)
	{
		$info = $this->getStrutturaDatiContratto();
		
		$spModel = new SpedizioninegozioModel();
		
		$spedizione = $spModel->selectId((int)$idS);
		
		$infoLabel = SpedizioninegozioinfoModel::g(false)->getCodice($idS, "InfoLabel");
		
		if ($infoLabel != "")
			$xmlObj = simplexml_load_string($infoLabel);
		
		if ($infoLabel != "" && !empty($spedizione) && isset($xmlObj) && isset($xmlObj->Parcel[0]->NumeroSpedizione))
		{
			$info["NumSpedizione"] = (string)$xmlObj->Parcel[0]->NumeroSpedizione;
			
			$client = $this->getClient();
			
			$res = $client->DeleteSped($info);
			
			$xml = aToX(array("InputDelete"=>$info), "", true, true);
			
			// Salvo il log dell'input e dell'output
			SpedizioninegozioinfoModel::g(false)->inserisci($idS, "InputDelete", $xml, "XML");
			SpedizioninegozioinfoModel::g(false)->inserisci($idS, "OutputDelete", $res->DeleteSpedResult->any, "XML");
		}
		
		return true;
	}
	
	// Pronota la spedizione al corriere per avere il numero di spedizione e l'etichetta
	public function prenotaSpedizione($idS, SpedizioninegozioModel $spedizione = null)
	{
		if ($this->isAttivo())
		{
			// Elimina le spedizioni
			$this->eliminaSpedizione($idS);
			
			$xmlArray = $this->getStrutturaSpedizione([$idS]);
			
			$xml = aToX($xmlArray, "", true, true);
			
			$infoLabel = $this->AddParcel($xml);
			
			$xmlObj = simplexml_load_string($infoLabel);
			
			// Salvo il log dell'invio e dell'output
			SpedizioninegozioinfoModel::g(false)->inserisci($idS, "XMLInfoParcel", $xml, "XML");
			SpedizioninegozioinfoModel::g(false)->inserisci($idS, "InfoLabel", $infoLabel, "XML");
			
			if (isset($xmlObj->Parcel))
			{
				$warning = (string)$xmlObj->Parcel[0]->DescrizioneSedeDestino == "GLS Check" ? $xmlObj->Parcel[0]->NoteSpedizione : "";
				
				$errore = (!isset($xmlObj->Parcel[0]->NumeroSpedizione)) ? $xmlObj->Parcel[0]->NoteSpedizione : "";
				
				$numeroSpedizione = isset($xmlObj->Parcel[0]->NumeroSpedizione) ? $xmlObj->Parcel[0]->NumeroSpedizione : "";
				
				return new Data_Spedizioni_Result($numeroSpedizione, $errore, $warning);
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
		$xmlArray = $this->getStrutturaSpedizione($idS, true);
		
		$xml = aToX($xmlArray, "", true, true);
		
		$listParcel = $this->CloseWorkDay($xml);
		
		$xmlObj = simplexml_load_string($listParcel);
		
		// Salvo il log dell'invio e dell'output
		SpedizioninegozioinfoModel::g(false)->inserisciinvio($idInvio, "XMLCloseInfoParcel", $xml, "XML");
		SpedizioninegozioinfoModel::g(false)->inserisciinvio($idInvio, "ListParcel", $listParcel, "XML");
		
		// Andare ad esaminare l'XML di output di GLS?
		$risultati = array();
		
		foreach ($idS as $id)
		{
			$risultati[$id] = new Data_Spedizioni_Result("","");
			
			$this->scriviLogConfermata((int)$id);
		}
		
		return $risultati;
	}
	
	// Restituisce il client SOAP
	public function getClient()
	{
		$headers = array(
			'connection_timeout' => 500000,
			'cache_wsdl' => WSDL_CACHE_BOTH,
			'keep_alive' => false,
		);
		
		$urlSoap = v("url_webservice_gls").'?wsdl';
		$client = new SoapClient($urlSoap, $headers);
		
		return $client;
	}
	
	public function AddParcel($XMLInfoParcel)
	{
		$client = $this->getClient();
		
		$variabile = array(
			"XMLInfoParcel"	=>	trim($XMLInfoParcel),
		);
		
		$infoLabel = $client->AddParcel($variabile);
		
		return $infoLabel->AddParcelResult->any;
	}
	
	public function CloseWorkDay($XMLInfoParcel)
	{
		$client = $this->getClient();
		
		$variabile = array(
			"XMLCloseInfoParcel"	=>	trim($XMLInfoParcel),
		);
		
		$result = $client->CloseWorkDay($variabile);
		
		return $result->CloseWorkDayResult->any;
	}
	
	// Stampa o genera il segnacollo della spedizione
	// $returnPath se impostato su 1 restituisce il PDF del path del PDF
	public function segnacollo($idSpedizione, $returnPath = false)
	{
		$infoLabel = SpedizioninegozioinfoModel::g(false)->getCodice($idSpedizione, "InfoLabel");
		
		if ($infoLabel)
		{
			$xmlObj = simplexml_load_string($infoLabel);
			
			$pathSpedizione = $this->getLogPath((int)$idSpedizione)."/Pdf";
			
			if (!file_exists($pathSpedizione))
				return;
			
			$pdfFilesToMerge = [];

			foreach ($xmlObj->Parcel as $p)
			{
				$pathPdf = $pathSpedizione."/".$p->ContatoreProgressivo.".pdf";
				
				$pdfDaMergiare[] = $pathPdf;
				
				FilePutContentsAtomic($pathPdf, base64_decode($p->PdfLabel));
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
		
		$params = htmlentitydecodeDeep($this->params);
		
		if (!empty($spedizione))
		{
			if ($spedizione["numero_spedizione"])
				$urlTracking = "https://www.gls-italy.com/index.php?option=com_gls&view=track_e_trace&mode=search&diretto=yes&locpartenza=".$params["codice_sede"]."&numsped=".$spedizione["numero_spedizione"];
			else if ($spedizione["codice_bda"])
				$urlTracking = "https://www.gls-italy.com/index.php?option=com_gls&view=track_e_trace&mode=search&diretto=yes&locpartenza=".$params["codice_sede"]."&numbda=".$spedizione["codice_bda"]."&tiporicerca=numbda&codice=".$params["codice_contratto"]."&cl=1";
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
		
		$params = htmlentitydecodeDeep($this->params);
		
		if (!empty($spedizione))
		{
			if ($spedizione["numero_spedizione"])
				$urlTracking = rtrim(v("url_tracking_gls"),"/")."/XML/get_xml_track.php?locpartenza=".$params["codice_sede"]."&NumSped=".$spedizione["numero_spedizione"]."&CodCli=".$params["codice_contratto"];
			else if ($spedizione["codice_bda"])
				$urlTracking = rtrim(v("url_tracking_gls"),"/")."/XML/get_xml_track.php?locpartenza=".$params["codice_sede"]."&bda=".$spedizione["codice_bda"]."&CodCli=".$params["codice_contratto"];
		}
		
		if (isset($urlTracking))
		{
			$trackingInfo = file_get_contents($urlTracking);
			
			$labelSpedizioniere = $this->getLabelSpedizioniere($trackingInfo);
			$codiceSpedizioniere = $this->getLabelSpedizioniere($trackingInfo, "Codice");
			
			$labelSpedizioniereFrontend = (string)$codiceSpedizioniere === (string)909 ? "" : $labelSpedizioniere;
			
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
			$labelSpedizioniere = $this->getLabelSpedizioniere($trackingInfo);
			$codiceSpedizioniere = $this->getLabelSpedizioniere($trackingInfo, "Codice");
			
			if ($labelSpedizioniere == "CONSEGNATA" || (int)$codiceSpedizioniere === 906)
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
			$dataConsegna = $this->getLabelSpedizioniere($trackingInfo, "Data");
			$oraConsegna = $this->getLabelSpedizioniere($trackingInfo, "Ora");
			
			if (preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{2,4}$/',(string)$dataConsegna) && preg_match('/^[0-9]{1,2}\:[0-9]{1,2}$/',(string)$oraConsegna))
			{
				$dateTime = DateTime::createFromFormat("d/m/y H:i", $dataConsegna." ".$oraConsegna);
			
				return $dateTime->format("Y-m-d H:i:s");
			}
		}
		
		return parent::getDataConsegna($idSpedizione);
	}
	
	public function getLabelSpedizioniere($trackingInfo, $campo = "Stato")
	{
		if ($trackingInfo)
		{
			$xmlObj = simplexml_load_string($trackingInfo);
			
			if (isset($xmlObj->SPEDIZIONE) && isset($xmlObj->SPEDIZIONE->TRACKING))
			{
				foreach ($xmlObj->SPEDIZIONE->TRACKING as $tr)
				{
					foreach ($tr->{$campo} as $stato)
					{
						return (string)$stato;
					}
				}
			}
		}
		
		return "";
	}
	
	public function decodeOutput($output)
	{
		$xmlObj = simplexml_load_string($output);
		
		$domXml = new DOMDocument('1.0');
		$domXml->preserveWhiteSpace = false;
		$domXml->formatOutput = true;
		$domXml->loadXML($xmlObj->asXML());
		$xmlString = $domXml->saveXML();
		return nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($xmlString)));
	}
}
