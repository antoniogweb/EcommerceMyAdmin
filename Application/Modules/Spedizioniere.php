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

class Spedizioniere
{
	use Modulo;
	
	protected $condizioniCampi = array(
		"lunghezzaMax"	=>	array(),
	);
	
	public function invia($spedizioni)
	{
		$arrayResult = [];
		
		foreach ($spedizioni as $sp)
		{
			$this->scriviLog("INVIO SPEDIZIONE - ID:".(int)$sp["id_spedizione_negozio"]);
			
			$arrayResult[$sp["id_spedizione_negozio"]] = array(
				"numero_spedizione"	=>	"55",
				"risultato"			=>	"OK",
				"errore_invio"		=>	"EFGH",
			);
		}
		
		return $arrayResult;
	}
	
	protected function checklunghezzaMax(SpedizioninegozioModel $spedizione, $campi)
	{
		foreach ($campi as $campo => $length)
		{
			$spedizione->addSoftCondition("update",'checkLength|'.$length,$campo);
		}
	}
	
	// Setta le condizioni aggiuntive del corriere
	public function setConditions(SpedizioninegozioModel $spedizione)
	{
		foreach ($this->condizioniCampi as $tipo => $campi)
		{
			$metodo = "check".strtolower($tipo);
			
			if (method_exists($this, $metodo))
			{
				call_user_func_array(array($this, $metodo), array($spedizione, $campi));
			}
		}
	}
	
	// Chiama i server del corriere e salva le informazioni del tracking nella spedizione
	public function getInfo($idSpedizione)
	{
		
	}
	
	public function scriviLog($testo)
	{
		Files_Log::$logFolder = LIBRARY."/Logs";
		
		Files_Log::getInstance("log_spedizioni")->writeString($this->params["codice"]. " - $testo");
	}
	
	public function scriviLogInfoTracking($idSpedizione)
	{
		$this->scriviLog("RICHIESTA INFO TRACKING SPEDIZIONE - ID:".(int)$idSpedizione);
	}
	
	public function scriviLogConsegnata($idSpedizione)
	{
		$this->scriviLog("SPEDIZIONE CONSEGNATA - ID:".(int)$idSpedizione);
	}
	
	public function scriviLogInErrore($idSpedizione)
	{
		$this->scriviLog("SPEDIZIONE IN ERRORE - ID:".(int)$idSpedizione);
	}
	
	// Recupera le ultime informazioni del tracking salvate e verifica se la spedizione è stata consegnata
	public function consegnata($idSpedizione)
	{
		return false;
	}
	
	// Recupera le ultime informazioni del tracking salvate e verifica se la spedizione è stata impostata in errore
	public function inErrore($idSpedizione)
	{
		return false;
	}
	
	public function gCodiciPagamentoContrassegno()
	{
		return [];
	}
	
	public function gFormatiEtichetta()
	{
		return [];
	}
	
	public function gTipoServizio()
	{
		return [];
	}
	
	public function gPasswordLabel()
	{
		return "Password";
	}
	
	public function gCodiceTariffa()
	{
		return [];
	}
	
	public function gAssicurazioneIntegrativa()
	{
		return [];
	}
	
	public function settaNoticeModel(SpedizioninegozioModel $spedizione = null, $notice)
	{
		if ($spedizione)
			$spedizione->notice = "<div class='alert alert-danger'>".gtext($notice)."</div>";
	}
	
	protected function getLogPath($idSpedizione)
	{
		$moduleFullPath = $this->cacheAbsolutePath."/".trim($this->params["codice"]);
		
		// Controllo e in caso creo la cartella della spedizione
		if (!@is_dir($moduleFullPath."/".(int)$idSpedizione))
			createFolderFull((int)$idSpedizione, $moduleFullPath);
		
		return $moduleFullPath."/".(int)$idSpedizione;
	}
}
