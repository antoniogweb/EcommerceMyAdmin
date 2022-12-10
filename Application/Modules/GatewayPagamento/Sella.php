<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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

class Sella
{
	private static $uicodes = array(
		"EUR"	=>	242,
	);
	
	private static $languages = array(
		"it"	=>	1,
		"en"	=>	2,
		"es"	=>	3,
		"fr"	=>	4,
		"de"	=>	5,
	);
	
	public $ALIAS = "";
	public $CHIAVESEGRETA = "";
	public $requestUrl = "";
	public $paymentUrl = "";
	public $merchantServerUrl = "";
	public $statoNotifica = "";
	public $statoCheckOrdine = "";
	
	private $urlPagamento = null;
	private $logFile = "";
	private $ordine = null;
	
	private static $contenutoDecriptato = null;
	private static $erroreClient = null;
	private static $cartUid = null;
	private static $transazioneEffettuataCorrettamente = false;
	
	public function __construct($ordine = array())
	{
		$pagamento = PagamentiModel::g(false)->where(array(
			"codice"	=>	"carta_di_credito"
		))->record();
		
		$this->CHIAVESEGRETA = $pagamento["chiave_segreta"];
		$this->ALIAS = $pagamento["alias_account"];
		
		if ((int)$pagamento["test"])
		{
			$this->requestUrl = "https://sandbox.gestpay.net/gestpay/GestPayWS/WsCryptDecrypt.asmx?wsdl";
			$this->paymentUrl = "https://sandbox.gestpay.net/pagam/pagam.aspx";
		}
		else
		{
			$this->requestUrl = "https://ecommS2S.sella.it/gestpay/GestPayWS/WsCryptDecrypt.asmx?wsdl";
			$this->paymentUrl = "https://ecomm.sella.it/pagam/pagam.aspx";
		}
		
		$this->merchantServerUrl = Domain::$name;
		
		if (!empty($ordine) && isset($ordine["cart_uid"]))
		{
			$this->ordine = $ordine;
		}
		
		$this->logFile = ROOT."/Logs/.ipncarta_results.log";
	}
	
	private static function getLanguageCode($lingua)
	{
		if (isset(self::$languages[$lingua]))
			return self::$languages[$lingua];
		
		return 1;
	}
	
	public function getPulsantePaga()
	{
		if (!$this->urlPagamento)
			$this->getUrlPagamento();
		
		$urlPagamento = $this->urlPagamento;
		
		$path = tpf("/Elementi/Pagamenti/Pulsanti/sella.php");
		
		if (file_exists($path))
		{
			ob_start();
			include $path;
			$pulsante = ob_get_clean();
		}
		
		return $pulsante;
	}
	
	public function getUrlPagamento()
	{
		$importo = $this->ordine["total"];
		
		$this->urlPagamento = $this->creaUrlPagamento($this->ordine["codice_transazione"], $importo, "EUR");
		
		return $this->urlPagamento;
	}
	
	public function creaUrlPagamento($codiceTransazione, $importo, $facoltativi = array())
	{
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');
		
		$client = new nusoap_client($this->requestUrl,true); 
		
		$parametri = array(
			'shopLogin' =>	$this->ALIAS, 
			'uicCode'	=>	self::$uicodes["EUR"], 
			'amount'	=>	$importo, 
			'shopTransactionId' => gtext("Ordine")." ".$this->ordine["id_o"]." ".gtext("del")." ".date("d/m/Y H:i"),    
			"paymentType" => "CREDITCARD",
			'buyerName'	=>	OrdiniModel::getNominativo($this->ordine),
			'buyerEmail'	=>	$this->ordine["email"],
			'languageId'	=>	self::getLanguageCode($this->ordine["lingua"]),
			'customInfo' 	=> "cart_uid=".$this->ordine["cart_uid"],
		);
		
		if (trim($this->CHIAVESEGRETA))
			$parametri["apikey"] = $this->CHIAVESEGRETA;
		
		$result = $client->call('Encrypt', $parametri);
		
		$errore = $client->getError();
		
		if (!$errore)
		{
			$codiceErrore = $result['EncryptResult']['GestPayCryptDecrypt']['ErrorCode'];
			$TransactionResult = $result['EncryptResult']['GestPayCryptDecrypt']['TransactionResult'];
			
			if ((int)$codiceErrore === 0 && (string)$TransactionResult === "OK")
			{
				//the call returned the encrypted string
				$stringaCriptata = $result['EncryptResult']['GestPayCryptDecrypt']['CryptDecryptString'];
				
				return $this->paymentUrl."?a=".$this->ALIAS."&b=".$stringaCriptata;
			}
// 			else
			
		}
		
		return null;
	}
	
	public function validate($scriviSuFileLog = true)
	{
		if (!isset($_GET["b"]))
		{
			$this->statoNotifica = "MANCA CODICE b IN URL";
			$this->scriviLog(false, $scriviSuFileLog);
			return false;
		}
		
		if (!isset(self::$contenutoDecriptato) || !isset(self::$erroreClient))
		{
			require_once(LIBRARY . '/External/libs/vendor/autoload.php');
			
			$client = new nusoap_client($this->requestUrl,true); 
			
			$parametri = array(
				'shopLogin'		 =>	$this->ALIAS, 
				'CryptedString'	=>	$_GET["b"],
			);
			
			if (trim($this->CHIAVESEGRETA))
				$parametri["apikey"] = $this->CHIAVESEGRETA;
			
			self::$contenutoDecriptato = $client->call('Decrypt', $parametri);
			
// 			print_r(self::$contenutoDecriptato);
			
			self::$erroreClient = $client->getError();
		}
		
		if (!self::$erroreClient)
		{
			$codiceErrore = self::$contenutoDecriptato['DecryptResult']['GestPayCryptDecrypt']['ErrorCode'];
			$testoErrore = self::$contenutoDecriptato['DecryptResult']['GestPayCryptDecrypt']['ErrorDescription'];
			$TransactionResult = self::$contenutoDecriptato['DecryptResult']['GestPayCryptDecrypt']['TransactionResult'];
			
			if ((int)$codiceErrore === 0 && (string)$TransactionResult === "OK")
			{
				$customInfo = self::$contenutoDecriptato['DecryptResult']['GestPayCryptDecrypt']['CustomInfo'];
				
				$customInfoArray = explode("*", $customInfo);
				
				foreach($customInfoArray as $k => $v)
				{
					if(trim($v) != "")
					{
						$array = explode("=", $v);
						
						if(count($array) == 2 && (string)strtolower($array[0]) === "cart_uid")
							self::$cartUid = $_GET["cart_uid"] = $array[1];
					}
				}
				
				self::$transazioneEffettuataCorrettamente = true;
				
				$this->statoNotifica = $testoErrore;
				$this->scriviLog(false, $scriviSuFileLog);
				return true;
			}
			else
			{
				$this->statoNotifica = $testoErrore;
				$this->scriviLog(false, $scriviSuFileLog);
				return false;
			}
		}
		else
		{
			$this->statoNotifica = "ERRORE TENTATIVO DECRYPT";
			$this->scriviLog(false, $scriviSuFileLog);
			return false;
		}
		
		return false;
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
		
		foreach ( $_GET as $key => $value ) {
			$this->statoNotifica .= "REQUEST:$key=$value \n";
		}
		
		if (isset(self::$contenutoDecriptato['DecryptResult']['GestPayCryptDecrypt']))
		{
			foreach (self::$contenutoDecriptato['DecryptResult']['GestPayCryptDecrypt'] as $key => $value)
			{
				if (is_array($value))
				{
					foreach ($value as $ks => $vs)
					{
						$this->statoNotifica .= "RISPOSTA:$ks=$vs \n";
					}
				}
				else
					$this->statoNotifica .= "RISPOSTA:$key=$value \n";
			}
		}
		
		if ($this->statoCheckOrdine)
			$this->statoNotifica .= "CHECK ORDINE:".$this->statoCheckOrdine."\n";
		
		if ($scriviSuFileLog)
		{
			// Write to log
			$fp = fopen ( $this->logFile , 'a+' );
			fwrite ( $fp, $this->statoNotifica . "\n\n" );
			fclose ( $fp ); // close file
			chmod ( $this->logFile , 0600 );
			$this->statoNotifica = "";
		}
		else
			return $this->statoNotifica;
		
	}
	
	public function redirect()
	{
		return true;
	}
	
	public function success()
	{
		return self::$transazioneEffettuataCorrettamente;
	}
	
	public function checkOrdine()
	{
		if (!isset(self::$contenutoDecriptato) || !isset(self::$erroreClient) || !isset(self::$cartUid))
		{
			$this->statoCheckOrdine = "MANCANO DATI DECRYPT IN CONTROLLO TOTALI\n";
			$this->scriviLog(false, true);
			return false;
		}
		
		$importo = $this->ordine["total"];
		$amount = self::$contenutoDecriptato['DecryptResult']['GestPayCryptDecrypt']['Amount'];
		$codTrans = self::$cartUid;
		
		if (strcmp($amount,$importo) === 0 && strcmp($codTrans,$this->ordine["cart_uid"]) === 0)
			return true;
		
		$this->statoCheckOrdine = "ORDINE NON TORNA\n";
		$this->statoCheckOrdine .= "DOVUTO: $importo - PAGATO: $amount \n";
		$this->statoCheckOrdine .= "COD TRANS: $codTrans - COD TRANS ORDINE: ".$this->ordine["codice_transazione"]." \n";
		
		$this->statoNotifica = 'OK, pagamento non corretto';
		$this->scriviLog(false, true);
		
		return false;
	}
	
	public function amountPagato()
	{
		if (!isset(self::$contenutoDecriptato) || !isset(self::$erroreClient) || !isset(self::$cartUid))
			return 0;
		
		return self::$contenutoDecriptato['DecryptResult']['GestPayCryptDecrypt']['Amount'];
	}
	
	public function getContenutoDecriptato()
	{
		return self::$contenutoDecriptato;
	}
}
