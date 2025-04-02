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

class Satispay
{
	private $public_key = "";
	private $private_key = "";
	private $keyId = "";
	
	// public $requestUrl = "";
	public $merchantServerUrl = "";
	
	public $okUrl = "";
	public $errorUrl = "";
	public $notifyUrl = "";
	public $statoNotifica = "";
	public $statoPagamento = "";
	public $statoCheckOrdine = "";
	
	protected $urlPagamento = null;
	protected $ordine = null;
	
	protected $idPagamentoSatispay = null; // l'ID del pagamento Satispay della sessione in corso
	private static $amountPagato = 0;
	
	// private static $languages = array(
	// 	"it"	=>	"ITA",
	// 	"en"	=>	"ENG",
	// 	"es"	=>	"SPA",
	// 	"fr"	=>	"FRA",
	// 	"de"	=>	"GER",
	// );
	
	public function __construct($ordine = array())
	{
		$pagamento = PagamentiModel::g(false)->where(array(
			"codice"	=>	"satispay"
		))->record();
		
		$pagamento = htmlentitydecodeDeep($pagamento);
		
		$this->public_key = str_replace("\r",'',$pagamento["public_key"]);
		$this->private_key = str_replace("\r",'',$pagamento["private_key"]);
		$this->keyId = $pagamento["alias_account"];
		
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');
		
		if ((int)$pagamento["test"])
			\SatispayGBusiness\Api::setSandbox(true);
		
		$this->merchantServerUrl = Domain::$name;
		
		if (!empty($ordine) && isset($ordine["cart_uid"]))
		{
			$this->okUrl = Url::getRoot()."grazie-per-l-acquisto-satispay?cart_uid=".$ordine["cart_uid"]."&banca_token=".$ordine["banca_token"];
			$this->notifyUrl = Url::getRoot()."notifica-pagamento-satispay?cart_uid=".$ordine["cart_uid"]."&banca_token=".$ordine["banca_token"]."&payment_id={uuid}";
			$this->errorUrl = "";
			$this->ordine = $ordine;
		}
	}
	
// 	private static function getLanguageCode($lingua)
// 	{
// 		if (isset(self::$languages[$lingua]))
// 			return self::$languages[$lingua];
// 		
// 		return "ITA";
// 	}

	public function getPulsantePaga()
	{
		// if (!$this->urlPagamento)
		// 	$this->getUrlPagamento();
		
		$urlPagamento = $this->urlPagamento;
		
		$path = tpf("/Elementi/Pagamenti/Pulsanti/satispay.php");
		
		if (file_exists($path))
		{
			ob_start();
			include $path;
			$pulsante = ob_get_clean();
		}
		
		return $pulsante;
	}
	
	protected function setImporto($importo)
	{
		return number_format($importo * 100,0,".","");
	}
	
	public function getUrlPagamento()
	{
		\SatispayGBusiness\Api::setPublicKey($this->public_key);
		\SatispayGBusiness\Api::setPrivateKey($this->private_key);
		\SatispayGBusiness\Api::setKeyId($this->keyId);
		
		$prefissoTelefonico = NazioniModel::g()->getPrefissoTelefonicoDaCodice($this->ordine["nazione"]);
		
		$pagamento = [
			'flow' => 'MATCH_CODE',
			'amount_unit' => $this->setImporto($this->ordine["total"]),
			'currency' => 'EUR',
			'external_code' => gtext("Ordine")." ".$this->ordine["id_o"],
			'callback_url' => $this->notifyUrl,
			'redirect_url' => $this->okUrl,    
			'metadata' => [
				"phone_number"  =>  $prefissoTelefonico.str_replace(" ","",$this->ordine["telefono"]),
				// 'order_id' => '1234',
				// 'user_id' => '5678',
				// 'payment_id' => 'payment1234',
				// 'session_id' => 'session1234',
				// 'key' => 'key1234'
			]
		];
		
		$payment = \SatispayGBusiness\Payment::create($pagamento);
		
		$risultato = isset($payment->redirect_url) ? "OK" : "KO";
		
		$logSubmit = new LogModel();
		$logSubmit->setFullLog(@json_encode($payment));
		$logSubmit->setSvuota(0);
		$logSubmit->setCartUid($this->ordine["cart_uid"]);
		$logSubmit->write("SATISPAY_CREA_PAGAMENTO", $risultato, true);
		
		return isset($payment->redirect_url) ? $payment->redirect_url : false;
	}
	
	public function scriviLog($success, $scriviSuFileLog = true)
	{
		$hostname = gethostbyaddr ( $_SERVER ['REMOTE_ADDR'] );
		
		$text = '[' . date ( 'm/d/Y g:i A' ) . '] - ';
		// Success or failure being logged?
		if ($success)
			$this->statoNotifica = $text . 'SUCCESS:' . $this->statoNotifica . "!\n";
		else
			$this->statoNotifica = $text . 'FAIL: ' . $this->statoNotifica . "!\n";
		
		$this->statoNotifica .= "[From:" . $hostname . "|" . $_SERVER ['REMOTE_ADDR'] . "]REQUEST Vars Received:\n";
		
		foreach ( $_REQUEST as $key => $value ) {
			$this->statoNotifica .= "REQUEST:$key=$value \n";
		}
		
		$this->statoNotifica .= "SESSIONE:\n".$this->statoPagamento."\n";
		
		if ($this->statoCheckOrdine)
			$this->statoNotifica .= "CHECK ORDINE:".$this->statoCheckOrdine."\n";
		
		if ($scriviSuFileLog)
		{
			// Write to log
			// Salvo il response del gateway
			$cartUid = isset($_GET["cart_uid"]) ? (string)$_GET["cart_uid"] : "";
			OrdiniresponseModel::aggiungi($cartUid, $this->statoNotifica, $success);
			
			$this->statoNotifica = "";
		}
		else
			return $this->statoNotifica;
		
	}
	
	public function validateRitorno()
	{
		return true;
	}
	
	public function getPagamento()
	{
		$log = new LogModel();
		
		$ultimoLog = $log->getLog("SATISPAY_CREA_PAGAMENTO", $this->ordine["cart_uid"]);
		
		if (!empty($ultimoLog))
		{
			$sessionArray = json_decode($ultimoLog["log_piattaforma"]["full_log"], true);
			
			if (isset($sessionArray["id"]))
			{
				$idPagamentoSatispay = $this->idPagamentoSatispay = $sessionArray["id"];
				
				\SatispayGBusiness\Api::setPublicKey($this->public_key);
				\SatispayGBusiness\Api::setPrivateKey($this->private_key);
				\SatispayGBusiness\Api::setKeyId($this->keyId);
				
				$pagamento = \SatispayGBusiness\Payment::get($idPagamentoSatispay);
				
				$this->statoPagamento = json_encode($pagamento, JSON_PRETTY_PRINT);
				
				if (isset($pagamento->amount_unit))
					self::$amountPagato = $pagamento->amount_unit;
				
				return $pagamento;
			}
		}
		
		return false;
	}
	
	public function validate($scriviSuFileLog = true)
	{
		$clean['banca_token'] = isset($_GET["banca_token"]) ? sanitizeAll($_GET["banca_token"]) : "";
		$clean['cart_uid'] = isset($_GET["cart_uid"]) ? sanitizeAll($_GET["cart_uid"]) : "";
		
		$result = false;
		
		// $inProgress = false;
		
		if ($clean['banca_token'] && $clean['cart_uid'])
		{
			$oModel = new OrdiniModel();
			
			$ordine = $oModel->clear()->where(array(
				"cart_uid"		=>	$clean['cart_uid'],
				"banca_token" 	=>	$clean['banca_token'],
			))->record();
			
			if (!empty($ordine))
			{
				$this->ordine = $ordine;
				
				$pagamento = $this->getPagamento();
				
				if ($pagamento !== false && isset($pagamento->status))
				{
					if ($pagamento->status == "ACCEPTED")
						$result = true;
					// else if ($sessione["status"] == "IN_PROGRESS")
					// 	$inProgress = true;
				}
			}
		}
		
		if ($result)
		{
			$this->statoNotifica = 'OK, pagamento avvenuto, preso riscontro';
			$this->scriviLog(true, $scriviSuFileLog);
		}
		else
		{
			$this->statoNotifica = 'KO, pagamento non avvenuto, preso riscontro';
			$this->scriviLog(false, $scriviSuFileLog);
		}
		
		return $result;
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
		$importo = number_format($this->ordine["total"] * 100,0,".","");
		
		if (strcmp(self::$amountPagato,$importo) === 0)
			return true;
		
		$this->statoCheckOrdine = "ORDINE NON TORNA\n";
		$this->statoCheckOrdine .= "DOVUTO: $importo - CAPTURED: ".self::$amountPagato." \n";
		
		$this->statoNotifica = 'KO, pagamento non corretto';
		$this->scriviLog(false, true);
		
		return false;
	}
	
	public function amountPagato()
	{
		return self::$amountPagato / 100;
	}
	
}
