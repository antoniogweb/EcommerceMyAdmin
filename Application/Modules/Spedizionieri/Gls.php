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
		return 'titolo,modulo,attivo,codice_cliente,password_cliente,codice_sede,codice_contratto';
	}
	
	public function gCampiSpedizione()
	{
		return array('codice_pagamento_contrassegno', 'codice_bda', 'assicurazione_integrativa', 'importo_assicurazione', 'formato_etichetta_pdf');
	}
	
	// Chiama i server del corriere e salva le informazioni del tracking nella spedizione
	public function getInfo($idSpedizione)
	{
		$this->scriviLogInfoTracking((int)$idSpedizione);
	}
	
	public function consegnata($idSpedizione)
	{
		if (true)
			$this->scriviLogConsegnata((int)$idSpedizione);
		
		return true;
	}
	
	// Recupera le ultime informazioni del tracking salvate e verifica se la spedizione è stata impostata in errore
	public function inErrore($idSpedizione)
	{
		if (true)
			$this->scriviLogInErrore((int)$idSpedizione);
		
		return true;
	}
	
	public function gCodiciPagamentoContrassegno()
	{
		return OpzioniModel::codice("GLS_CODICE_PAGAMENTO");
	}
	
	public function gFormatiEtichetta()
	{
		return ['A6', 'A5'];
	}
	
	public function gAssicurazioneIntegrativa()
	{
		return OpzioniModel::codice("GLS_ASSICURAZIONE_INTEGRATIVA");
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
					);
					
					if ($record["codice_bda"])
						$temp["Bda"] = $record["codice_bda"];
					
					if ($record["contrassegno"] > 0)
					{
						$valore = $record["contrassegno"] / count($colli);
						$temp["ImportoContrassegno"] = number_format($valore,2,",","");
						$temp["ModalitaIncasso"] = $record["codice_pagamento_contrassegno"];
					}
					
					if ($record["note_interne"])
						$temp["NoteSpedizione"] = $record["note_interne"];
					
					if ($record["importo_assicurazione"] > 0)
					{
						$valore = $record["importo_assicurazione"] / count($colli);
						$temp["Assicurazione"] = number_format($valore,2,",","");
					}
					
					if ($record["assicurazione_integrativa"])
						$temp["AssicurazioneIntegrativa"] = $record["assicurazione_integrativa"];
					
					$parcelArray[] = $temp;
				}
			}
			
			$info = array(
				"SedeGls"				=>	$params["codice_sede"],
				"CodiceClienteGls"		=>	$params["codice_cliente"],
				"PasswordClienteGls"	=>	$params["password_cliente"],
			);
			
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
	
	// Pronoto la spedizione al corriere per avere il numero di spedizione e l'etichetta
	public function prenotaSpedizione($idS, SpedizioninegozioModel $spedizione = null)
	{
		if ($this->isAttivo())
		{
			$xmlArray = $this->getStrutturaSpedizione([$idS]);
			
			$xml = aToX($xmlArray, "", true, true);
			
			$infoLabel = $this->AddParcel($xml);
			
// 			echo $infoLabel;die();
			
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
	
	public function getUrlTracking($idSpedizione)
	{
		$spnModel = new SpedizioninegozioModel();
		
		$spedizione = $spnModel->selectId((int)$idSpedizione);
		
		$urlTracking = "";
		
		$params = htmlentitydecodeDeep($this->params);
		
		if (!empty($spedizione))
		{
			if ($spedizione["numero_spedizione"])
				return $urlTracking = "https://www.gls-italy.com/index.php?option=com_gls&view=track_e_trace&mode=search&diretto=yes&locpartenza=".$params["codice_sede"]."&numsped=".$spedizione["numero_spedizione"];
			else if ($spedizione["codice_bda"])
				return $urlTracking = "https://www.gls-italy.com/index.php?option=com_gls&view=track_e_trace&mode=search&diretto=yes&locpartenza=".$params["codice_sede"]."&numbda=".$spedizione["codice_bda"]."&tiporicerca=numbda&codice=".$params["codice_contratto"]."&cl=1";
		}
		
		return $urlTracking;
	}
}
