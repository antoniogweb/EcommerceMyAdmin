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

class Brt extends Spedizioniere
{
	protected $condizioniCampi = array(
		"lunghezzaMax"	=>	array(
			"ragione_sociale"	=>	70,
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
	
	public function gCampiForm()
	{
		return 'titolo,modulo,attivo,codice_cliente,password_cliente,codice_sede';
	}
	
	public function gCampiSpedizione()
	{
		return array('tipo_servizio', 'codice_tariffa', 'codice_pagamento_contrassegno', 'riferimento_mittente_numerico', 'riferimento_mittente_alfa', 'importo_assicurazione');
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
}
