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

class FattureInCloud extends Gestionale
{
	private $url; // l'URL di invio all'API di fatture in FattureInCloud
	
	public function gCampiForm()
	{
		return 'titolo,attivo,param_1,param_2';
	}
	
	public function gParam1Label()
	{
		return "ID azienda";
	}
	
	public function gParam2Label()
	{
		return "Accesso Token";
	}
	
	public function isAttiva()
	{
		if (trim($this->params["param_1"]) && trim($this->params["param_2"]))
			return true;
		
		return false;
	}
	
	public function descInviaAlGestionale($ordine, $testo = "Invia la fattura a")
	{
		if ($ordine["versione_api_gestionale"] != "v1")
			return parent::descInviaAlGestionale($ordine, $testo);
		
		return "";
	}
	
	public function descAnnullaInvioAlGestionale($ordine, $testo = "Invia l'invio a")
	{
		if ($ordine["versione_api_gestionale"] != "v1")
			return parent::descAnnullaInvioAlGestionale($ordine, $testo);
		
		return "";
	}
	
	public function descOrdineInviato($ordine)
	{
		$f = new FattureModel();
		
		$numero = $f->clear()->where(array(
			"id_o"	=>	(int)$ordine["id_o"]
		))->field("numero");
		
		if ($numero)
			return "<span class='text text-success text-bold'>".sprintf(gtext("Fattura %s inviata a"), $numero)." ".$this->titolo()."</span><br />".$this->descInviaAlGestionale($ordine, "Invia nuovamente a");
		else
			return "<span class='text text-danger text-bold'>".sprintf(gtext("Fattura assente nel gestionale ma segnata come inviata a"))." ".$this->titolo()."?!?</span>";
	}
	
	public function setUrl($url)
	{
		$this->url = rtrim($this->params["api_endpoint"],"/")."/".ltrim($url,"/");
	}
	
	public function send($method = "POST", $valori = array())
	{
		if (!$valori)
		{
			$options_dett = array(
				"http" => array(
					"header"  => "Accept: application/json\r\n"."Authorization: Bearer ".$this->params["param_2"]."\r\n"."Content-type: application/json\r\n",
					"method"  => $method,
				),
			);
			
			$context_dett  = stream_context_create($options_dett);
			$result_dett = json_decode(file_get_contents($this->url, false, $context_dett), true);
			
			return $result_dett;
		}
		else
		{
			$ch = curl_init($this->url);
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Accept: application/json', "Authorization: Bearer ".$this->params["param_2"]]);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($valori));                                                                                                                 
			
			$result = curl_exec($ch);
			
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			
			return json_decode($result, true);
		}
	}
	
	public function info()
	{
		$this->setUrl("/user/companies");
		$result = $this->send("GET");
		
		return json_encode($result);
	}
	
	public function infoPagamenti()
	{
		$this->setUrl("/c/".$this->params["param_1"]."/info/payment_methods");
		$result = $this->send("GET");
		
		return json_encode($result);
	}
	
	public function infoContiDiSaldo()
	{
		$this->setUrl("/c/".$this->params["param_1"]."/info/payment_accounts");
		$result = $this->send("GET");
		
		return json_encode($result);
	}
	
	public function inviaOrdine($idO)
	{
		$ordine = $this->infoOrdine((int)$idO);
		
		$f = new FattureModel();
		$o = new OrdiniModel();
		
		$fattura = $f->clear()->where(array(
			"id_o"	=>	(int)$ordine["id_o"],
		))->record();
		
		if (empty($fattura))
			return $this->impostaErrori($ordine["id_o"], array(gtext("Fattura mancante")));
		
		$errori = $this->checkCampi($ordine, array("codice_pagamento", "codice_pagamento_pa"));
		
		if ($errori)
			return $this->impostaErrori($ordine["id_o"], $errori);
		
		$articoli = array();
		
		foreach ($ordine["righe"] as $riga)
		{
			if ($riga["codice_iva"] == "")
				return $this->impostaErrori($ordine["id_o"], array(gtext("Codice IVA gestionale mancante per iva"." ".$riga["iva"]."%")));
			
			$articoli[] = array(
				"product_id"=>"0",
				"code"=>$riga["codice"],
				"name"=>htmlentitydecode($riga["titolo"]),
				"measure"=>"N",
				"qty"=>$riga["quantity"],
				"description"=>$riga["attributi"] ? strip_tags($riga["attributi"]) : "",
				"category"=>"",
				"net_price"=>$riga["prezzo_finale"],
				"gross_price"=>number_format($riga["prezzo_finale"]*(1 + ($riga["iva"]/100)), 2, ".",""),
				"vat"=>array(
					"id"	=>	$riga["codice_iva"],
				),
				"not_taxable"=>false,
				"discount"=>0,
				"apply_withholding_taxes"=>false,
				"discount_highlight"=>0,
				"in_ddt"=>false,
				"stock"=>false
			);
		}
		
		$pagamenti = [];
		
		foreach ($ordine["pagamenti"] as $pagamento)
		{
			$metodo = "not";
			$statoPagamento = $ordine["pagato_finale"] ? "paid" : "not_paid";
			
			$tempPagamenti = array(
				"due_date"	=>	date("Y-m-d"),
				"amount"	=>	number_format($pagamento["importo"],2,".",""),
				"status"	=>	$statoPagamento,
			);
			
			if ($statoPagamento == "paid")
			{
				$tempPagamenti["payment_account"] = array(
					"id"		=>	$this->getVariabile("conto_di_saldo"),
					"paid_date"	=>	$pagamento["data_pagamento"] ? $pagamento["data_pagamento"] : date("Y-m-d"),
				);
				
				$tempPagamenti["paid_date"] = $pagamento["data_pagamento"] ? $pagamento["data_pagamento"] : date("Y-m-d");
			}
			
			$pagamenti[] = $tempPagamenti;
		}
		
		$pec = ($ordine["codice_destinatario"] && $ordine["codice_destinatario"] != "0000000" && $ordine["codice_destinatario"] != "000000") ? "" : $ordine["pec"];
		
		$codiceDestinatario = ($ordine["codice_destinatario"] && $ordine["codice_destinatario"] != "0000000" && $ordine["codice_destinatario"] != "000000") ? $ordine["codice_destinatario"] : "";
		
		$note = ($ordine["usata_promozione"] == "Y" && $ordine["nome_promozione"]) ? "Usata promozione ".htmlentitydecode($ordine["nome_promozione"]) : "";
		
		$valori = array(
			"type"	=>	"invoice",
			"entity"	=>	array(
				"name"	=>	htmlentitydecode($ordine["nominativo"]),
				"vat_number"	=>	$ordine["p_iva"],
				"tax_code"		=>	$ordine["codice_fiscale"],
				"address_street"=>	htmlentitydecode($ordine["indirizzo"]),
				"address_postal_code"	=>	$ordine["cap"],
				"address_city"	=>	htmlentitydecode($ordine["citta"]),
				"address_province"	=>	$ordine["provincia"],
				"address_extra"	=>	"",
				"country"		=>	nomeNazione($ordine["nazione"]),
				"certified_email"=>$pec,
				"ei_code"=>$codiceDestinatario,
			),
			"language"	=>	array(
				"code"	=>	"it",
				"name"	=>	"Italiano",
			),
			"number"	=>$fattura["numero"],
			"date"		=>$fattura["data_fattura"] ? $fattura["data_fattura"] : date("Y-m-d", strtotime($fattura["data_creazione"])),
			"currency"	=>	array(
				"id"	=>	"EUR",
				"exchange_rate"	=>	"1.00000",
				"symbol"	=>	"€",
			),
			"use_gross_prices"=>false,
			"rivalsa"=>0,
			"cassa"=>0,
			"show_payments"=>false,
			"show_paypal_button"=>false,
			"show_tspay_button"=>false,
			"show_notification_button"=>false,
			"items_list"=>$articoli,
			"payments_list"=>$pagamenti,
			"e_invoice"=>true,
			"PA_tipo_cliente"=>"B2B",
			"payment_method"	=>	array(
				"id"	=>	$ordine["codice_pagamento"],
			),
			"ei_data"	=>	array(
				"payment_method"	=>	$ordine["codice_pagamento_pa"],
				"vat_kind"=>"I",
				"bank_name"=>htmlentitydecode($this->getVariabile("istituto_di_credito")),
				"bank_iban"=>str_replace(" ","",$this->getVariabile("iban")),
				"bank_beneficiary"=>htmlentitydecode($this->getVariabile("beneficiario")),
			),
			"notes"	=>	$note,
			"use_split_payment"=>false
		);
		
		if ($this->getVariabile("sezionale"))
			$valori["numeration"] = $this->getVariabile("sezionale");
		
		$valori = array(
			"data"	=>	$valori,
		);
		
		if ($ordine["codice_gestionale"])
		{
			$this->setUrl("/c/".$this->params["param_1"]."/issued_documents/".$ordine["codice_gestionale"]);
			$result = $this->send("PUT", $valori);
		}
		else
		{
			$this->setUrl("/c/".$this->params["param_1"]."/issued_documents");
			$result = $this->send("POST", $valori);
		}
		
		if (isset($result["data"]["id"]))
		{
			$o->sValues(array(
				"codice_gestionale"			=>	$result["data"]["id"],
				"errore_gestionale"			=>	"",
				"versione_api_gestionale"	=>	"v2",
			), "sanitizeDb");
		}
		else
		{
			$o->sValues(array(
				"errore_gestionale"=>	json_encode($result),
				"versione_api_gestionale"	=>	"v2",
			), "sanitizeDb");
		}
		
		$o->pUpdate((int)$idO);
	}
}
