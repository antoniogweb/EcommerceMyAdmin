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

class PagamentiModel extends GenericModel {
	
	public static $gateway = null;
	public static $sCodice = null;
	
	public static $attivoSiNoGateway = array(
		"1"	=>	"Sì, usa i pagamenti finti (solo per sviluppo)",
		"0"	=>	"No, usa pagamenti veri (solo per produzione)",
	);
	
	public static $elencoGateway = array(
		"Nexi"		=>	"Circuito Nexi (XPay Easy / XPay Pro)",
		"NexiLink"	=>	"Circuito Nexi (XPay Link)",
		"Sella"		=>	"Circuito Banca Sella",
	);
	
	public static $elencoGatewayPaypal = array(
		""					=>	"PayPal Standard",
		"PaypalCheckout"	=>	"PayPal Checkout",
	);
	
	public function __construct() {
		$this->_tables='pagamenti';
		$this->_idFields='id_pagamento';
		
		$this->_idOrder = 'id_order';
		
		$this->traduzione = true;
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/pagamenti",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	false,
				"maxFileSize"	=>	3000000,
// 				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	100,
					'imgHeight'		=>	100,
					'defaultImage'	=>  null,
					'cropImage'		=>	'yes',
				),
			),
		);
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_pagamento', null, "CASCADE"),
        );
    }
    
	public function setFormStruct($id = 0)
	{
		$record = $this->selectId($id);
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attivo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Attivo",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'test'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Pagamento di test",
					"options"	=>	self::$attivoSiNoGateway,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'gateway_pagamento'	=>	$this->campoGatewayPagamento($record),
				'utilizzo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Utilizzo",
					"options"	=>	array(
						"W"	=>	"Web",
						"B"	=>	"Backend",
						"E"	=>	"Web + Backend",
					),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'prezzo'	=>	array(
					"labelString"	=>	"Costo (IVA esclusa)",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Il costo del pagamento verrà aggiunto al totale dell'ordine.")."</div>"
					),
				),
				'prezzo_ivato'	=>	array(
					"labelString"	=>	"Costo (IVA inclusa)",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Il costo del pagamento verrà aggiunto al totale dell'ordine.")."</div>"
					),
				),
				'istruzioni_pagamento'		=>	array(
					'labelString'=>	'Istruzioni per il pagamento',
					'entryClass'	=>	'form_input_text help_alias',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Questo testo apparirà nella pagina di resoconto e nella mail al cliente una volta che l'ordine sarà confermato.")."</div>"
					),
				),
				'alias_account'		=>	array(
					'labelString'=>	$this->aliasAccountLabel($record),
				),
				'chiave_segreta'		=>	array(
					'labelString'=>	$this->chiaveSegretaLabel($record),
				),
				'codice_pagamento_pa'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Codice pagamento pubblica amministrazione",
					"options"	=>	$this->opzioniPagamentiPa(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	protected function campoGatewayPagamento($record)
	{
		$res = array(
			"type"	=>	"Select",
			"labelString"	=>	"Gateway di pagamento",
			"options"	=>	$this->elencoGateway($record["codice"]),
			"reverse"	=>	"yes",
			"className"	=>	"form-control",
		);
		
		if ($record["codice"] == "paypal")
			$res["wrap"] = array(
				null,
				null,
				"<div class='form_notice'>".gtext("Nel caso si scelga PayPal Standard, i campi Pagamento di test, CLIENT ID e SECRET non avranno effetto, la configurazione del pagamento andrà fatta sotto Preferenze > Impostazioni > Account Business Paypal.")."</div>"
			);
				
		return $res;
	}
	
	protected function aliasAccountLabel($record)
	{
		if ($record["codice"] == "carta_di_credito")
			return 'Alias Account / Shop ID';
		else if ($record["codice"] == "klarna")
			return 'Nome utente API';
		else
			return 'PAYPAL CLIENT ID';
	}
	
	protected function chiaveSegretaLabel($record)
	{
		if ($record["codice"] == "carta_di_credito")
			return 'Chiave segreta / API KEY';
		else if ($record["codice"] == "klarna")
			return 'Password API';
		else
			return 'PAYPAL SECRET';
	}
	
	public function elencoGateway($codicePagamento)
	{
		if ($codicePagamento == "carta_di_credito")
			return self::$elencoGateway;
		else if ($codicePagamento == "paypal")
			return self::$elencoGatewayPaypal;
	}
	
	public function opzioniPagamentiPa()
	{
		$op = new OpzioniModel();
		
		$res = $op->clear()->where(array(
			"codice"	=>	"CODICE_PAGAMENTO_PA",
			"attivo"	=>	1,
		))->findAll(false);
		
		$arraySelect = [""=>"--seleziona--"];
		
		foreach ($res as $r)
		{
			$arraySelect[$r["valore"]] = $r["valore"]." - ".$r["titolo"];
		}
		
		return $arraySelect;
	}
	
	public function attivo($record)
	{
		return $record[$this->_tables]["attivo"] ? gtext("Sì") : gtext("No");
	}
	
	public function edit($record)
	{
		return "<span class='data-record-id' data-primary-key='".$record[$this->_tables][$this->_idFields]."'>".$record[$this->_tables][$this->campoTitolo]."</span>";
	}
	
	public static function gateway($ordine = array(), $force = false, $codice = "carta_di_credito")
	{
		$p = new PagamentiModel();
		
		if (isset(self::$sCodice))
			$codice = self::$sCodice;
		
		if (!isset(self::$gateway) || $force)
		{
			$attivo = $p->clear()->where(array(
				"attivo"	=>	1,
				"codice"	=>	sanitizeAll($codice),
			))->record();
			
			if (!empty($attivo) && file_exists(LIBRARY."/Application/Modules/GatewayPagamento/".$attivo["gateway_pagamento"].".php"))
			{
				require_once(LIBRARY."/Application/Modules/GatewayPagamento/".$attivo["gateway_pagamento"].".php");
				
				$objectReflection = new ReflectionClass($attivo["gateway_pagamento"]);
				$object = $objectReflection->newInstanceArgs(array($ordine));
				
				self::$gateway = $object;
			}
		}
		
		return $p;
	}
	
	public function __call($metodo, $argomenti)
	{
		if ($this->checkCallLingue($metodo, $argomenti))
			return $this->callLingue($metodo, $argomenti);
		
		if (isset(self::$gateway) && method_exists(self::$gateway, $metodo))
			return call_user_func_array(array(self::$gateway, $metodo), $argomenti);

		return false;
	}
	
	public function setPriceNonIvato()
	{
		if (isset($this->values["prezzo_ivato"]))
			$this->values["prezzo"] = number_format(setPrice($this->values["prezzo_ivato"]) / (1 + (Parametri::$iva / 100)), v("cifre_decimali"),".","");
	}
	
	public function insert()
	{
		$this->setPriceNonIvato();
		
		if ($this->upload("update"))
			return parent::insert();
		
		return false;
	}
	
	public function update($id = null, $where = null)
	{
		$this->setPriceNonIvato();
		
		if ($this->upload("insert"))
			return parent::update($id, $where);
		
		return false;
	}
	
	public static function getCostoCarrello()
	{
		$campoPrezzo = "prezzo_ivato";
		
		if (isset($_POST["pagamento"]))
			$prezzoIvato = (float)PagamentiModel::g(false)->select("pagamenti.prezzo")->where(array(
				"codice"	=>	sanitizeAll($_POST["pagamento"]),
			))->field($campoPrezzo);
		else
			$prezzoIvato = (float)PagamentiModel::g(false)->where(array(
				"attivo"	=>	1,
			))->getMin($campoPrezzo);
		
		if (v("scorpora_iva_prezzo_estero_spedizione_pagamenti"))
			$prezzoIvato = number_format(($prezzoIvato / (1 + (Parametri::$iva / 100))) * (1 + (CartModel::getAliquotaIvaSpedizione() / 100)), 2, ".", "");
			
		$prezzo = number_format($prezzoIvato / ( 1 + (CartModel::getAliquotaIvaSpedizione() / 100)), v("cifre_decimali"), ".", "");
		
		return $prezzo;
	}
	
	public static function getMaxPagamento()
	{
		return (float)PagamentiModel::g(false)->where(array(
			"attivo"	=>	1,
		))->getMax("prezzo");
	}
	
	public function deletable($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && $record["tipo"] == "U")
			return true;
		
		return false;
	}
}
