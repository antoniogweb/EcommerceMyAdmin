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

if (!defined('EG')) die('Direct access not allowed!');

class BasePaypalController extends OrdiniController
{
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!PagamentiModel::gateway(array(), true, "paypal")->isPaypalCheckout() || !App::checkCSRF("paypal_csrf"))
			$this->responseCode(403);
		
		$this->clean();
		
		$rawData = file_get_contents("php://input");
	
		if ($rawData)
			$_POST = json_decode($rawData, true);
		
		App::createLogFolder();
	}
	
	protected function getOrdine($cart_uid = "", $banca_token = "")
	{
		return $this->m("OrdiniModel")->clear()->where(array(
			"cart_uid"		=>	sanitizeAll($cart_uid),
			"banca_token"	=>	sanitizeAll($banca_token),
			"stato"			=>	"pending",
		))->record();
	}
	
	public function createorder($cart_uid = "", $banca_token = "")
	{
		$ordine = $this->getOrdine($cart_uid, $banca_token);
		
		if (!empty($ordine))
		{
// 			if ($ordine["gateway_order_id"])
// 				echo json_encode(array(
// 					"id"	=>	$ordine["gateway_order_id"],
// 				));
// 			else
				echo json_encode(PagamentiModel::gateway($ordine, true, "paypal")->createOrder());
		}
		else
			echo json_encode(array(
				"id"			=>	0,
				"description"	=>	gtext("Ordine non esistente. Si prega di conttare il negozio"),
			));
	}
	
	public function captureorder($cart_uid = "", $banca_token = "")
	{
		$ordine = $this->getOrdine($cart_uid, $banca_token);
		
		$output = json_encode([]);
		
		if (!empty($ordine) && $ordine["gateway_order_id"])
		{
			list($result, $output, $messaggio) = PagamentiModel::gateway($ordine, true, "paypal")->captureOrder();
			
			if ($result)
			{
				$this->model("FattureModel");
				
				$this->m("OrdiniModel")->values = array();
				$this->m("OrdiniModel")->values["data_pagamento"] = date("Y-m-d H:i");
				
				$statoPagato = $this->getStatoOrdinePagato($ordine);
				$this->m("OrdiniModel")->values["stato"] = $statoPagato;
				
				$this->m("OrdiniModel")->update((int)$ordine["id_o"]);
			
				$this->mandaMailDopoPagamento($ordine);
				
				$mandaFattura = false;
				
				if (ImpostazioniModel::$valori["manda_mail_fattura_in_automatico"] == "Y")
				{
					$mandaFattura = true;
					//genera la fattura
					$this->m("FattureModel")->crea($ordine["id_o"]);
				}
				
				if (v("manda_mail_avvenuto_pagamento_al_cliente"))
					$this->m("OrdiniModel")->mandaMailGeneric($ordine["id_o"], v("oggetto_ordine_pagato"), "mail-$statoPagato", "P", $mandaFattura);
				
				$res = MailordiniModel::inviaMail(array(
					"emails"	=>	array(Parametri::$mailInvioOrdine),
					"oggetto"	=>	v("oggetto_ordine_pagato"),
					"testo"		=>	"Il pagamento dell'ordine #".$ordine["id_o"]." è andato a buon fine. <br />",
					"tipologia"	=>	"ORDINE NEGOZIO",
					"id_o"		=>	$ordine["id_o"],
					"tipo"		=>	"P",
					"id_user"	=>	$ordine["id_user"],
					"array_variabili"	=>	$ordine,
					"lingua"	=>	v("lingua_default_frontend"),
				));
			}
			else
				MailordiniModel::inviaMailLog($messaggio, "<pre>$output</pre>", "CAPTURE PAYPAL CHECKOUT");
		}
		
		echo $output;
	}
}
