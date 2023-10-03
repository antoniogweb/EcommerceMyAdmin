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
	
	public function gCampiIndirizzo()
	{
		return array();
	}
	
	public function settaNoticeModel(SpedizioninegozioModel $spedizione = null, $notice)
	{
		if ($spedizione)
			$spedizione->notice = "<div class='alert alert-danger'>".gtext($notice)."</div>";
	}
	
	public function getLogPath($idElemento = 0, $currentDateTime = null, $isInvio = false)
	{
		$moduleFullPath = $this->cacheAbsolutePath."/".trim($this->params["codice"]);
		
		$folder = $isInvio ? "Invii" : "Spedizioni";
		
		// Controllo e in caso creo la cartella della spedizione o invio
		if (!@is_dir($moduleFullPath."/$folder"))
			createFolderFull($folder, $moduleFullPath);
		
		// Controllo e in caso creo la cartella della spedizione o invio specifico
		if (!@is_dir($moduleFullPath."/$folder/".(int)$idElemento))
			createFolderFull((int)$idElemento, $moduleFullPath."/$folder");
		
		// Cartella con dati di invio corrente
		if ($currentDateTime)
			createFolderFull($currentDateTime, $moduleFullPath."/$folder/".(int)$idElemento);
		
		// Controllo e in caso creo la cartella con i PDF della spedizione
		if (!$isInvio && !@is_dir($moduleFullPath."/$folder/".(int)$idElemento."/Pdf"))
			createFolderFull("Pdf", $moduleFullPath."/$folder/".(int)$idElemento);
		
		return $moduleFullPath."/$folder/".(int)$idElemento;
	}
	
	public function getLabelNumeroSpedizione()
	{
		return gtext("Numero spedizione corriere");
	}
	
	// Verifica se le spedizioni di ID $ids sono confermabili
	public function spedizioniConfermabili(array $ids)
	{
		return true;
	}
	
	protected function getPathTemplateBordero()
	{
		return LIBRARY . "/Application/Views/Spedizionieri/Bordero/" . $this->params["modulo"].".php";
	}
	
	protected function getTitoloPdf($record)
	{
		return "bordero_".strtoupper($this->params["modulo"])."_invio_del_".date("Y_m_d", strtotime($record["data_elaborazione"])).".pdf";
	}
	
	// Stampa il pdf del borderò dell'invio $id
	protected function genericReportPdf($idInvio = 0)
	{
		$spniModel = new SpedizioninegozioinviiModel();
		
		$record = SpedizioninegozioinviiModel::g(false)->selectId((int)$idInvio);
		
		if (!empty($record))
		{
			$spedizioni = $spniModel->getSpedizioniInvio((int)$idInvio);
			
			$templatePath = $this->getPathTemplateBordero();
			$nomeFilePdf = $this->getTitoloPdf($record);
			
			ob_start();
			include($templatePath);
			$content = ob_get_clean();
// 			echo $content;die();

			Pdf::$params["format"] = "A4-L";
			
			Pdf::output("", $nomeFilePdf, $spedizioni, "I", $content);
		}
	}
}
