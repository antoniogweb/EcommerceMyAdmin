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

class Klarna
{
	private $token = "";
	
	public $requestUrl = "";
	public $merchantServerUrl = "";
	public $statoSessione = "";
	public $statoNotifica = "";
	public $statoCheckOrdine = "";
	
	private $urlPagamento = null;
	
	private $ordine = null;
	private $clientToken = "";
	private $sessionId = "";
	
	private $hppSessionId = "";
	
	private static $hppOrderId = "";
	private static $amountPagato = 0;
	
	public function __construct($ordine = array())
	{
		$pagamento = PagamentiModel::g(false)->where(array(
			"codice"	=>	"klarna"
		))->record();
		
		$pagamento = htmlentitydecodeDeep($pagamento);
		
		$this->token = base64_encode($pagamento["alias_account"].":".$pagamento["chiave_segreta"]);
		
		if ((int)$pagamento["test"])
			$this->requestUrl = "https://api.playground.klarna.com";
		else
			$this->requestUrl = "https://api.klarna.com";
		
		$this->merchantServerUrl = Domain::$name;
		
		if (!empty($ordine) && isset($ordine["cart_uid"]))
			$this->ordine = htmlentitydecodeDeep($ordine);
	}
	
	protected function setImporto($importo)
	{
		$importo = number_format($importo,2,".","");
		
		return str_replace(".","",$importo);
	}
	
	protected function callUrl($url, $data, $tipo, $metodo = "POST")
	{
		$klarnaUrl = $this->requestUrl."/".ltrim($url,"/");
		
		$ch = curl_init($klarnaUrl);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"Authorization: Basic ".$this->token
		));
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		if ($metodo == "POST")
		{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));                                                                                                                 
		}
		
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
				"street_address2"	=>	"",
				"postal_code"	=>	$strutturaOrdine["cap"],
				"phone"			=>	$strutturaOrdine["telefono"],
				"country"		=>	$strutturaOrdine["nazione"],
				"city"			=>	$strutturaOrdine["citta"],
// 				"region"		=>	$strutturaOrdine["provincia"],
			),
		);
		
		if ($strutturaOrdine["cognome"])
			$valori["billing_address"]["family_name"] = $strutturaOrdine["cognome"];
		
		$result = $this->callUrl("payments/v1/sessions", $valori, "SESSIONE_KP");
		
		if (isset($result["client_token"]) && isset($result["session_id"]))
		{
			$this->clientToken = $result["client_token"];
			$this->sessionId = $result["session_id"];
			
			$valori = array(
				"payment_session_url"	=>	$this->requestUrl."/payments/v1/sessions/".$this->sessionId,
				"merchant_urls"			=>	array(
					"success"	=>	Url::getRoot()."grazie-per-l-acquisto-klarna?cart_uid=".$this->ordine["cart_uid"]."&banca_token=".$this->ordine["banca_token"],
					"cancel"	=>	Url::getRoot()."ordini/annullapagamento/klarna/".$this->ordine["cart_uid"],
					"back"		=>	Url::getRoot()."resoconto-acquisto/".$this->ordine["id_o"]."/".$this->ordine["cart_uid"]."/".$this->ordine["admin_token"]."?n=y",
					"failure"	=>	Url::getRoot()."ordini/errorepagamento/".$this->ordine["banca_token"],
					"error"		=>	Url::getRoot()."ordini/errorepagamento/".$this->ordine["banca_token"],
					"status_update"	=>	Url::getRoot()."notifica-pagamento-klarna?cart_uid=".$this->ordine["cart_uid"]."&banca_token=".$this->ordine["banca_token"],
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
		$path = tpf("/Elementi/Pagamenti/Pulsanti/klarna.php");
		
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
		return $this->creaSessione();
	}
	
	public function leggiSessione()
	{
		$log = new LogModel();
		
		$ultimoLog = $log->getLog("SESSIONE_HPP", $this->ordine["cart_uid"]);
		
		if (!empty($ultimoLog))
		{
			$sessionArray = json_decode($ultimoLog["log_piattaforma"]["full_log"], true);
			
			if (isset($sessionArray["session_id"]))
			{
				$hppSessionId = $this->hppSessionId = $sessionArray["session_id"];
				
				$result = $this->callUrl("hpp/v1/sessions/$hppSessionId", array(), "LEGGI_SESSIONE", "GET");
				
				if (isset($result["order_id"]))
					self::$hppOrderId = $result["order_id"];
				
				$this->statoSessione = json_encode($result, JSON_PRETTY_PRINT);
				
				return $result;
			}
		}
		
		return false;
	}
	
	public function scriviLog($success, $scriviSuFileLog = true)
	{
		$hostname = gethostbyaddr ( $_SERVER ['REMOTE_ADDR'] );
		
		$text = '[' . date ( 'm/d/Y g:i A' ) . '] - ';
		// Success or failure being logged?
		if ($success)
			$this->statoNotifica = $text . 'SUCCESS:' . $this->statoNotifica."!\n";
		else
			$this->statoNotifica = $text . 'FAIL:' . $this->statoNotifica."!\n";
		
		$this->statoNotifica .= "[From:" . $hostname . "|" . $_SERVER ['REMOTE_ADDR'] . "]REQUEST Vars Received:\n";
		
		foreach ( $_REQUEST as $key => $value ) {
			$this->statoNotifica .= "REQUEST:$key=$value \n";
		}
		
		$this->statoNotifica .= "SESSIONE:\n".$this->statoSessione."\n";
		
		if ($this->statoCheckOrdine)
			$this->statoNotifica .= "CHECK ORDINE:".$this->statoCheckOrdine."\n";
		
		if ($scriviSuFileLog)
		{
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
	
	public function validate($scriviSuFileLog = true)
	{
		$clean['banca_token'] = isset($_GET["banca_token"]) ? sanitizeAll($_GET["banca_token"]) : "";
		$clean['cart_uid'] = isset($_GET["cart_uid"]) ? sanitizeAll($_GET["cart_uid"]) : "";
		
		$result = false;
		
		$inProgress = false;
		
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
				
				$sessione = $this->leggiSessione();
				
				if ($sessione !== false && isset($sessione["status"]))
				{
					if ($sessione["status"] == "COMPLETED")
						$result = true;
					else if ($sessione["status"] == "IN_PROGRESS")
						$inProgress = true;
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
		
		if ($inProgress)
			die("");
		
		return $result;
	}
	
	public function redirect()
	{
		return true;
	}
	
	public function success()
	{
		return true;
	}
	
	public function checkOrdine()
	{
		$importo = number_format($this->ordine["total"] * 100,0,".","");
		
		if (self::$hppOrderId)
		{
			$result = $this->callUrl("ordermanagement/v1/orders/".self::$hppOrderId, array(), "LEGGI_ORDINE", "GET");
			
			if (isset($result["original_order_amount"]))
				self::$amountPagato = $result["original_order_amount"];
		}
		
		if (strcmp(self::$amountPagato,$importo) === 0)
			return true;
		
		$this->statoCheckOrdine = "ORDINE NON TORNA\n";
		$this->statoCheckOrdine .= "DOVUTO: $importo - CAPTURED: ".self::$amountPagato." \n";
		
		$this->statoNotifica = 'OK, pagamento non corretto';
		$this->scriviLog(false, true);
		
		return false;
	}
	
	public function amountPagato()
	{
		return self::$amountPagato / 100;
	}
	
}
