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
	private $token = "";
	
	public $requestUrl = "";
	public $merchantServerUrl = "";
// 	public $okUrl = "";
// 	public $errorUrl = "";
// 	public $notifyUrl = "";
// 	public $statoNotifica = "";
// 	public $statoCheckOrdine = "";
	
	private $urlPagamento = null;
	
	private $ordine = null;
	private $clientToken = "";
	private $sessionId = "";
	
	public function __construct($ordine = array())
	{
		$pagamento = PagamentiModel::g(false)->where(array(
			"codice"	=>	"klarna"
		))->record();
		
		$pagamento = htmlentitydecodeDeep($pagamento);
		
// 		print_r($pagamento);die();
		
		$this->token = base64_encode($pagamento["alias_account"].":".$pagamento["chiave_segreta"]);
		
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
	
	protected function setImporto($importo)
	{
		$importo = number_format($importo,2,".","");
		
		return str_replace(".","",$importo);
	}
	
	protected function callUrl($url, $data, $tipo)
	{
		$klarnaUrl = $this->requestUrl."/".ltrim($url,"/"); //"https://api.playground.klarna.com/payments/v1/sessions";
		
		$ch = curl_init($klarnaUrl);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"Authorization: Basic ".$this->token
		));
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));                                                                                                                 
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		$array = json_decode($result, true); 
		
		$risultato = isset($array["session_id"]) ? "OK" : "KO";
		
		$logSubmit = new LogModel();
		$logSubmit->setFullLog($result);
		$logSubmit->setSvuota(0);
		$logSubmit->setCartUid($this->ordine["cart_uid"]);
		$logSubmit->write($tipo, $risultato, true);
		
		return $array;
	}
	
	// Crea una sessione Klarna
	protected function creaSessione()
	{
		$strutturaOrdine = GestionaliModel::getModuloPadre()->infoOrdine((int)$this->ordine["id_o"]);
		
		$orderLines = array();
		
		foreach ($strutturaOrdine["righe"] as $riga)
		{
			$orderLines[] = array(
				"reference"	=>	$riga["codice"],
				"name"		=>	$riga["titolo"],
				"quantity"	=>	$riga["quantity"],
				"unit_price"=>	$this->setImporto($riga["prezzo_finale_ivato"]),
				"total_amount"	=>	$this->setImporto($riga["quantity"] * $riga["prezzo_finale_ivato"]),
			);
		}
		
		$valori = array(
			"purchase_country"	=>	$strutturaOrdine["nazione"],
			"purchase_currency"	=>	"EUR",
			"locale"			=>	$this->ordine["lingua"]."-".$this->ordine["nazione_navigazione"],
			"order_amount"		=>	$this->setImporto($strutturaOrdine["total"]),
			"order_tax_amount"	=>	0,
			"order_lines"		=>	$orderLines,
			"billing_address"	=>	array(
				"given_name"	=>	$strutturaOrdine["ragione_sociale"] ? $strutturaOrdine["ragione_sociale"] : $strutturaOrdine["nome"],
				"email"			=>	$strutturaOrdine["email"],
				"street_address"=>	$strutturaOrdine["indirizzo"],
				"postal_code"	=>	$strutturaOrdine["cap"],
				"phone"			=>	$strutturaOrdine["telefono"],
				"country"		=>	$strutturaOrdine["nazione"],
			),
		);
		
		if ($strutturaOrdine["cognome"])
			$valori["billing_address"]["family_name"] = $strutturaOrdine["cognome"];
		
// 		print_r($valori);die();
		
		$result = $this->callUrl("payments/v1/sessions", $valori, "SESSIONE_KP");
		
// 		$result = array(
// 			"client_token"	=>	"eyJhbGciOiJSUzI1NiIsImtpZCI6IjgyMzA1ZWJjLWI4MTEtMzYzNy1hYTRjLTY2ZWNhMTg3NGYzZCJ9.eyJzZXNzaW9uX2lkIjoiYzViNDNhZDMtYzgwZS01OGRlLTg2MTgtYjI4ZWRhOWUwNGM3IiwiYmFzZV91cmwiOiJodHRwczovL2pzLnBsYXlncm91bmQua2xhcm5hLmNvbS9ldS9rcCIsImRlc2lnbiI6ImtsYXJuYSIsImxhbmd1YWdlIjoiaXQiLCJwdXJjaGFzZV9jb3VudHJ5IjoiSVQiLCJlbnZpcm9ubWVudCI6InBsYXlncm91bmQiLCJtZXJjaGFudF9uYW1lIjoiWW91ciBidXNpbmVzcyBuYW1lIiwic2Vzc2lvbl90eXBlIjoiUEFZTUVOVFMiLCJjbGllbnRfZXZlbnRfYmFzZV91cmwiOiJodHRwczovL2V1LnBsYXlncm91bmQua2xhcm5hZXZ0LmNvbSIsInNjaGVtZSI6dHJ1ZSwiZXhwZXJpbWVudHMiOlt7Im5hbWUiOiJrcC1jbGllbnQtb25lLXB1cmNoYXNlLWZsb3ciLCJ2YXJpYXRlIjoidmFyaWF0ZS0xIn0seyJuYW1lIjoia3BjLTFrLXNlcnZpY2UiLCJ2YXJpYXRlIjoidmFyaWF0ZS0xIn0seyJuYW1lIjoia3AtY2xpZW50LXV0b3BpYS1zdGF0aWMtd2lkZ2V0IiwidmFyaWF0ZSI6ImluZGV4IiwicGFyYW1ldGVycyI6eyJkeW5hbWljIjoidHJ1ZSJ9fSx7Im5hbWUiOiJrcC1jbGllbnQtdXRvcGlhLWZsb3ciLCJ2YXJpYXRlIjoidmFyaWF0ZS0xIn0seyJuYW1lIjoiaW4tYXBwLXNkay1uZXctaW50ZXJuYWwtYnJvd3NlciIsInBhcmFtZXRlcnMiOnsidmFyaWF0ZV9pZCI6Im5ldy1pbnRlcm5hbC1icm93c2VyLWVuYWJsZSJ9fSx7Im5hbWUiOiJrcC1jbGllbnQtdXRvcGlhLXNkay1mbG93IiwidmFyaWF0ZSI6InZhcmlhdGUtMSJ9LHsibmFtZSI6ImtwLWNsaWVudC11dG9waWEtd2Vidmlldy1mbG93IiwidmFyaWF0ZSI6InZhcmlhdGUtMSJ9LHsibmFtZSI6ImluLWFwcC1zZGstY2FyZC1zY2FubmluZyIsInBhcmFtZXRlcnMiOnsidmFyaWF0ZV9pZCI6ImNhcmQtc2Nhbm5pbmctZW5hYmxlIn19XSwicmVnaW9uIjoiZXUiLCJvcmRlcl9hbW91bnQiOjEwNTUwLCJvZmZlcmluZ19vcHRzIjowLCJvbyI6Ijg4IiwidmVyc2lvbiI6InYxLjEwLjAtMTU5MC1nM2ViYzM5MDciLCJhIjowfQ.Osi2cOqwv9ofi648D-K5HrQxT6T40ROnr7hQnqpNBvuIkgGq23kvMMjsevaq9IofdrVnmMh8k87F7aaVKmALfqr9yYJ6HQPvkYxotrl3FD2nutBbfcRZEflCErirIfK2eMxABdJEaU68ADcWD1BKxgNxPnpQSoh1O4aMimRQhcMFdQRlBTm9bz5jgYGoWUEOpHssaVuqsEi6SxnZyx9dQarnCo0YQ46joYuGquVFWPcQ-uv9lVMjk7Tm2E99n2r5L8lmlGser0vk2CkLfb7vTKHuj5jVmIczjw7uszeZFFiY8M8EH1CmvyzCDN2f8Xwxl_yPSAwFzGzTmVMGZnvjhw",
// 			"session_id"	=>	"c5b43ad3-c80e-58de-8618-b28eda9e04c7",
// 		);
		
		if (isset($result["client_token"]) && isset($result["session_id"]))
		{
			$this->clientToken = $result["client_token"];
			$this->sessionId = $result["session_id"];
			
			$valori = array(
				"payment_session_url"	=>	$this->requestUrl."/payments/v1/sessions/".$this->sessionId,
				"merchant_urls"			=>	array(
					"success"	=>	Url::getRoot()."grazie-per-l-acquisto-klarna?cart_uid=".$this->ordine["cart_uid"],
					"cancel"	=>	Url::getRoot()."ordini/annullapagamento/klarna/".$this->ordine["cart_uid"],
					"back"		=>	Url::getRoot()."resoconto-acquisto/".$this->ordine["id_o"]."/".$this->ordine["cart_uid"]."?n=y",
					"failure"	=>	Url::getRoot()."ordini/errorepagamento/".$this->ordine["banca_token"],
					"error"		=>	Url::getRoot()."ordini/errorepagamento/".$this->ordine["banca_token"],
				),
				"options"	=>	array(
					"page_title"		=>	gtext("Completa il tuo acquisto"),
					"place_order_mode"	=>	"CAPTURE_ORDER",
					"purchase_type"		=>	"BUY",
					"show_subtotal_detail"	=>	"HIDE",
				),
			);
			
			$result = $this->callUrl("hpp/v1/sessions", $valori, "SESSIONE_HPP");
			
			if (isset($result["redirect_url"]))
				return $result["redirect_url"];
		}
		
		return false;
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
		return $this->creaSessione();
		
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
