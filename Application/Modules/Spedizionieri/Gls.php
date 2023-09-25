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
	public function getStrutturaSpedizione($idS)
	{
		$spModel = new SpedizioninegozioModel();
			
		$record = $spModel->selectId($idS);
		
		if (!empty($record))
		{
			$record = htmlentitydecodeDeep($record);
			
			$colli = $spModel->getColli([(int)$idS]);
			
			$parcelArray = [];
			
			$params = htmlentitydecodeDeep($this->params);
			
			$contatore = 1;
			
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
					"ContatoreProgressivo"	=>	$contatore,
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
				
				$contatore++;
			}
			
			$xmlArray = array(
				"Info"	=>	array(
					"SedeGls"				=>	$params["codice_sede"],
					"CodiceClienteGls"		=>	$params["codice_cliente"],
					"PasswordClienteGls"	=>	$params["password_cliente"],
					"Parcel"	=>	$parcelArray,
				),
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
// 			$xmlArray = $this->getStrutturaSpedizione($idS);
// 			
// 			$xml = aToX($xmlArray, "", true, true);
// 			
// 			$infoLabel = $this->AddParcel($xml);
// 			
// 			$path = $this->getLogPath($idS);
// 			
// 			FilePutContentsAtomic($path."/InfoLabel.XML", $infoLabel);
			
			return true;
		}
		else
			$this->settaNoticeModel($spedizione, "Attenzione, il modulo spedizioniere ".$this->params["titolo"]. " non è attivo");
		
		return false;
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
		
		$var = array(
			"XMLInfoParcel"	=>	trim($XMLInfoParcel),
		);
		
		$infoLabel = $client->AddParcel($var);
		
		return $infoLabel->AddParcelResult->any;
	}
}
