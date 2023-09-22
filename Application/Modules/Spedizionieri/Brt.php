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
	public function gCampiForm()
	{
		return 'titolo,modulo,attivo';
	}
	
	public function setConditions(SpedizioninegozioModel $spedizione)
	{
		
	}
	
	public function gCampiSpedizione()
	{
		return array('codice_pagamento_contrassegno', 'riferimento_mittente_numerico', 'riferimento_mittente_alfa', 'importo_assicurazione');
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
}
