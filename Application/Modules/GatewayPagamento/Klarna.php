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

class Klarna
{
	public $username = "";
	public $password = "";
	public $requestUrl = "";
	public $merchantServerUrl = "";
// 	public $okUrl = "";
// 	public $errorUrl = "";
// 	public $notifyUrl = "";
// 	public $statoNotifica = "";
// 	public $statoCheckOrdine = "";
	
	private $urlPagamento = null;
// 	private $logFile = "";
	private $ordine = null;
	
	public function __construct($ordine = array())
	{
		$pagamento = PagamentiModel::g(false)->where(array(
			"codice"	=>	"klarna"
		))->record();
		
		$pagamento = htmlentitydecodeDeep($pagamento);
		
		$this->username = $pagamento["chiave_segreta"];
		$this->password = $pagamento["alias_account"];
		
		if ((int)$pagamento["test"])
			$this->requestUrl = "https://api.playground.klarna.com";
		else
			$this->requestUrl = "https://api.klarna.com";
		
		$this->merchantServerUrl = Domain::$name;
		
		if (!empty($ordine) && isset($ordine["cart_uid"]))
		{
// 			$this->okUrl = "grazie-per-l-acquisto-carta?cart_uid=".$ordine["cart_uid"];
// 			$this->notifyUrl = Url::getRoot()."notifica-pagamento-carta?cart_uid=".$ordine["cart_uid"];
// 			$this->errorUrl = "ordini/annullapagamento/nexi/".$ordine["cart_uid"];
			$this->ordine = htmlentitydecodeDeep($ordine);
		}
		
// 		$this->logFile = ROOT."/Logs/.ipnklarna_results.log";
	}
	
	protected function creaSessione()
	{
		$strutturaOrdine = GestionaliModel::getModuloPadre()->infoOrdine((int)$this->ordine["id_o"]);
	}
	
	public function getPulsantePaga()
	{
// 		if (!$this->urlPagamento)
// 			$this->getUrlPagamento();
// 		
// 		$urlPagamento = $this->urlPagamento;
// 		
// 		$path = tpf("/Elementi/Pagamenti/Pulsanti/nexi.php");
// 		
// 		if (file_exists($path))
// 		{
// 			ob_start();
// 			include $path;
// 			$pulsante = ob_get_clean();
// 		}
// 		
// 		return $pulsante;
	}
	
	public function getUrlPagamento()
	{
		$this->creaSessione();
		
// 		$notifyUrl = $this->notifyUrl;
// 		$importo = str_replace(".","",$this->ordine["total"]);
// 		
// 		// Parametri facoltativi
// 		$facoltativi = array(
// 			'mail' => $this->ordine["email"],
// 			'languageId' => "ITA",
// 			'descrizione' => "Ordine ".$this->ordine["id_o"],
// 			"nome"	=>	$this->ordine["nome"],
// 			"cognome"	=>	$this->ordine["cognome"],
// 			'OPTION_CF' => $this->ordine["codice_fiscale"],
// 			'urlpost' => $notifyUrl,
// 		);
// 		
// 		$this->urlPagamento = $this->creaUrlPagamento($this->ordine["codice_transazione"], $importo, "EUR", $facoltativi);
// 		
// 		return $this->urlPagamento;
	}
	
	public function creaUrlPagamento($codiceTransazione, $importo, $divisa = "EUR", $facoltativi = array())
	{
// 		$codTrans = $codiceTransazione;
// 
// 		// Calcolo MAC
// 		$mac = sha1('codTrans=' . $codTrans . 'divisa=' . $divisa . 'importo=' . $importo . $this->CHIAVESEGRETA);
// 
// 		// Parametri obbligatori
// 		$obbligatori = array(
// 			'alias' => $this->ALIAS,
// 			'importo' => $importo,
// 			'divisa' => $divisa,
// 			'codTrans' => $codTrans,
// 			'url' => $this->merchantServerUrl . "/" . $this->okUrl,
// 			'url_back' => $this->merchantServerUrl . "/" . $this->errorUrl,
// 			'mac' => $mac,   
// 		);
// 
// 		$requestParams = array_merge($obbligatori, $facoltativi);
// 
// 		$aRequestParams = array();
// 		foreach ($requestParams as $param => $value) {
// 			$aRequestParams[] = $param . "=" . $value;
// 		}
// 
// 		$stringRequestParams = implode("&", $aRequestParams);
// 
// 		return $this->requestUrl . "?" . $stringRequestParams;
	}
	
	public function scriviLog($success, $scriviSuFileLog = true)
	{
// 		$hostname = gethostbyaddr ( $_SERVER ['REMOTE_ADDR'] );
// 		
// 		$text = '[' . date ( 'm/d/Y g:i A' ) . '] - ';
// 		// Success or failure being logged?
// 		if ($success)
// 			$this->statoNotifica = $text . 'SUCCESS:' . $this->statoNotifica . "!\n";
// 		else
// 			$this->statoNotifica = $text . 'FAIL: ' . $this->statoNotifica . "!\n";
// 		
// 		$this->statoNotifica .= "[From:" . $hostname . "|" . $_SERVER ['REMOTE_ADDR'] . "]REQUEST Vars Received:\n";
// 		
// 		foreach ( $_REQUEST as $key => $value ) {
// 			$this->statoNotifica .= "REQUEST:$key=$value \n";
// 		}
// 		
// 		if ($this->statoCheckOrdine)
// 			$this->statoNotifica .= "CHECK ORDINE:".$this->statoCheckOrdine."\n";
// 		
// 		if ($scriviSuFileLog)
// 		{
// 			// Write to log
// 			$fp = fopen ( $this->logFile , 'a+' );
// 			fwrite ( $fp, $this->statoNotifica . "\n\n" );
// 			fclose ( $fp ); // close file
// 			chmod ( $this->logFile , 0600 );
// 			
// 			// Salvo il response del gateway
// 			$cartUid = isset($_GET["cart_uid"]) ? (string)$_GET["cart_uid"] : "";
// 			OrdiniresponseModel::aggiungi($cartUid, $this->statoNotifica, $success);
// 			
// 			$this->statoNotifica = "";
// 		}
// 		else
// 			return $this->statoNotifica;
		
	}
	
	public function validate($scriviSuFileLog = true)
	{
		
		return false;
	}
	
	public function redirect()
	{
		return true;
	}
	
	public function success()
	{
		return false;
	}
	
	public function checkOrdine()
	{
		
		
		return false;
	}
	
	public function amountPagato()
	{
		
	}
	
}
