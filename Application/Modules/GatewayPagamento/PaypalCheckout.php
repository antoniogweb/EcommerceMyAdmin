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

class PaypalCheckout
{
	private $ordine = null;
	private $pagamento = [];
	private $paypalClientID = null;
	private $paypalSecret = null;
	private $apiURL = "";
	
	protected $currencyCode = "EUR";
	
	public function __construct($ordine = array())
	{
		$this->pagamento = PagamentiModel::g(false)->where(array(
			"codice"	=>	"paypal"
		))->record();
		
		if (!empty($this->pagamento))
		{
			$this->paypalClientID = htmlentitydecode($this->pagamento["alias_account"]);
			$this->paypalSecret = htmlentitydecode($this->pagamento["chiave_segreta"]);
			
			if ((int)$this->pagamento["test"])
				$this->apiURL = "https://api-m.sandbox.paypal.com";
			else
				$this->apiURL = "https://api-m.paypal.com";
		}
		
		if (!empty($ordine) && isset($ordine["cart_uid"]))
			$this->ordine = $ordine;
	}
	
	public function isPaypalCheckout()
	{
		return true;
	}
	
	public function getPaypalClientId()
	{
		return $this->paypalClientID;
	}
	
	protected function getAccessToken()
	{
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiURL."/v1/oauth2/token",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_USERPWD => $this->paypalClientID.":".$this->paypalSecret,
			CURLOPT_POSTFIELDS => "grant_type=client_credentials",
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/x-www-form-urlencoded"
			),
		));

		$result= curl_exec($curl);
		curl_close($curl);
		
		$array = json_decode($result, true); 
		
		if (isset($array['access_token']))
			return $array['access_token'];
		
		return null;
	}
	
	public function captureOrder()
	{
		$accessToken = $this->getAccessToken();
		
		$ris = false;
		$output = "";
		$messaggio = "";
		
		if ($accessToken !== null)
		{
			if (isset($this->ordine["gateway_order_id"]) && $this->ordine["gateway_order_id"])
			{
				$ch = curl_init($this->apiURL."/v2/checkout/orders/".$this->ordine["gateway_order_id"]."/capture");
						
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					"PayPal-Request-Id: '".$this->ordine["banca_token"]."'",
					"Authorization: Bearer $accessToken",
					"Content-Type: application/json"
					));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   
				
				$result = $output = curl_exec($ch);
				curl_close($ch);
				
				$result = json_decode($result, true);
				
				if (isset($result["status"]) && $result["status"] == "COMPLETED")
				{
					$totale = 0;
					$tuttoPagato = true;
					
					if (isset($result["purchase_units"]))
					{
						foreach ($result["purchase_units"] as $unit)
						{
							foreach ($unit["payments"]["captures"] as $capture)
							{
								if ($capture["status"] == "COMPLETED")
									$totale += $capture["amount"]["value"];
								else
									$tuttoPagato = false;
							}
						}
					}
					
					if ($tuttoPagato && number_format($totale,2,".","") == number_format($this->ordine["total"],2,".",""))
						$ris = true;
					else
						$messaggio = gtext(vsprintf("Attenzione, discrepanza nel dovuto dell'ordine %s, si prega di controllare i log",$this->ordine["id_o"]));
				}
				else
					$messaggio = gtext(vsprintf("Attenzione, il pagamento dell'ordine %s non è andato a buon fine, si prega di controllare i log",$this->ordine["id_o"]));
				
				OrdiniresponseModel::aggiungi($this->ordine["cart_uid"], json_encode($result, JSON_PRETTY_PRINT), $ris);
			}
		}
		
		return array($ris, $output, $messaggio);
	}
	
	public function createOrder()
	{
		$accessToken = $this->getAccessToken();
		
		if ($accessToken !== null)
		{
			$valori = array(
				"intent"	=>	"CAPTURE",
				"purchase_units"	=>	array(
					array(
						"reference_id"	=>	$this->ordine["banca_token"],
						"amount"	=>	array(
							"currency_code"	=>	$this->currencyCode,
							"value"			=>	$this->ordine["total"],
						),
					),
				),
				"payment_source"	=>	array(
					"paypal"	=>	array(
						"experience_context"	=>	array(
							"payment_method_preference"	=>	"IMMEDIATE_PAYMENT_REQUIRED",
							"locale"	=>	$this->ordine["lingua"]."-".$this->ordine["nazione_navigazione"],
						),
						"email_address"	=>	$this->ordine["email"],
					),
				),
			);
			
			if (strcmp($this->ordine["tipo_cliente"], "privato") === 0)
			{
				$valori["payment_source"]["paypal"]["name"] = array(
					"given_name"	=>	$this->ordine["nome"],
					"surname"		=>	$this->ordine["cognome"],
				);
			}
			
			if (strcmp($this->ordine["indirizzo"], "") !== 0)
			{
				$valori["payment_source"]["paypal"]["address"] = array(
					"address_line_1"	=>	$this->ordine["indirizzo"],
					"postal_code"		=>	$this->ordine["cap"],
					"country_code"		=>	$this->ordine["nazione"],
					"admin_area_2"		=>	$this->ordine["citta"],
// 					"state"				=>	$this->ordine["provincia"],
				);
			}
			
			if (strcmp($this->ordine["telefono"], "") !== 0)
			{
				$valori["payment_source"]["paypal"]["phone"] = array(
					"phone_number"	=>	array(
						"national_number"	=>	$this->ordine["telefono"],
					),
				);
			}
			
			$ch = curl_init($this->apiURL."/v2/checkout/orders");
					
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json",
				"Authorization: Bearer $accessToken"
				));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($valori));                                                                                                                 
			
			$result = curl_exec($ch);
			curl_close($ch);
			
			$result = json_decode($result, true); 
			
			if (isset($result["id"]))
			{
				$oModel = new OrdiniModel();
				
				$oModel->sValues(array(
					"gateway_order_id"	=>	$result["id"],
				));
				
				$oModel->pUpdate((int)$this->ordine["id_o"]);
				
				return array(
					"id"	=>	sanitizeAll($result["id"]),
				);
			}
		}
		
		return array(
			"id"			=>	0,
			"description"	=>	gtext("Non è possibile iniziare il pagamento PayPal. Si prega di contattare il negozio."),
		);
	}
	
	public function getPulsantePaga()
	{
		$path = tpf("/Elementi/Pagamenti/Pulsanti/paypal_checkout.php");
		
		if (file_exists($path))
		{
			$paypalClientId = $this->paypalClientID;
			$ordine = $this->ordine;
			$paypalCsrf = App::getCSFR("paypal_csrf");
			
			ob_start();
			include $path;
			$pulsante = ob_get_clean();
		}
		
		return $pulsante;
	}
}
