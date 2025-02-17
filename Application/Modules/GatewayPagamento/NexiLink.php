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

require_once(LIBRARY."/Application/Modules/GatewayPagamento/Nexi.php");

class NexiLink extends Nexi
{
	public function __construct($ordine = array())
	{
		$pagamento = PagamentiModel::g(false)->where(array(
			"codice"	=>	"carta_di_credito"
		))->record();
		
		$this->CHIAVESEGRETA = $pagamento["chiave_segreta"];
		$this->ALIAS = $pagamento["alias_account"];
		
		if ((int)$pagamento["test"])
			$this->requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/bo/richiestaPayMail";
		else
			$this->requestUrl = "https://ecommerce.nexi.it/ecomm/api/bo/richiestaPayMail";
		
		$this->merchantServerUrl = Domain::$name;
		
		if (!empty($ordine) && isset($ordine["cart_uid"]))
		{
			$this->okUrl = "grazie-per-l-acquisto-carta?cart_uid=".$ordine["cart_uid"];
			$this->notifyUrl = Url::getRoot()."notifica-pagamento-carta?cart_uid=".$ordine["cart_uid"];
			$this->errorUrl = "ordini/annullapagamento/nexi/".$ordine["cart_uid"];
			$this->ordine = $ordine;
		}
		
		$this->logFile = ROOT."/Logs/.ipncarta_results.log";
	}
	
	public function getPulsantePaga()
	{
		if (!$this->urlPagamento)
			$this->getUrlPagamento();
		
		$urlPagamento = $this->urlPagamento;
		
		if (!$urlPagamento)
			return "";
		
		$path = tpf("/Elementi/Pagamenti/Pulsanti/nexi.php");
		
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
		if (isset($this->ordine["id_o"]))
		{
			OrdiniModel::g()->resettaCodiceTransazione($this->ordine["id_o"]);
			$this->ordine = OrdiniModel::g()->selectId((int)$this->ordine["id_o"]);
		}
		
		$notifyUrl = $this->notifyUrl;
		$importo = str_replace(".","",$this->ordine["total"]);
		
		// Parametri facoltativi
		$facoltativi = array(
			'mail' => $this->ordine["email"],
			'languageId' => "ITA",
			'descrizione' => "Ordine ".$this->ordine["id_o"],
			"nome"	=>	$this->ordine["nome"],
			"cognome"	=>	$this->ordine["cognome"],
			'urlpost' => $notifyUrl,
			'url_back' => $this->merchantServerUrl . "/" . $this->errorUrl,
		);
		
		$this->urlPagamento = $this->creaUrlPagamento($this->ordine["codice_transazione"], $importo, "EUR", $facoltativi);
		
		return $this->urlPagamento;
	}
	
	public function creaUrlPagamento($codiceTransazione, $importo, $divisa = "EUR", $facoltativi = array())
	{
		$codTrans = $codiceTransazione;

		// Calcolo MAC
		$timeStamp = (time()) * 1000;
		$mac = sha1("apiKey=" . $this->ALIAS . "codiceTransazione=" . $codTrans . "importo=" . $importo . "timeStamp=" . $timeStamp . $this->CHIAVESEGRETA);

		// Parametri obbligatori
		$obbligatori = array(
			'apiKey' => $this->ALIAS,
			'importo' => $importo,
			'codiceTransazione' => $codTrans,
			'timeStamp' => $timeStamp,
			'mac' => $mac,
			'url' => $this->merchantServerUrl . "/" . $this->okUrl,
			"parametriAggiuntivi"	=>	$facoltativi,
		);
		
		$connection = curl_init();
		
		curl_setopt_array($connection, array(
			CURLOPT_URL => $this->requestUrl,
			CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => json_encode($obbligatori),
			CURLOPT_RETURNTRANSFER => 1,
			CURLINFO_HEADER_OUT => true,
			CURLOPT_SSL_VERIFYPEER => 0
		));

		$json = curl_exec($connection);

		curl_close($connection);

		// Decodifico risposta
		$risposta = json_decode($json, true);
		
		$urlRedirect = null;
		
		// Controllo JSON di risposta
		if (json_last_error() === JSON_ERROR_NONE) {

			$MACrisposta = sha1('esito=' . $risposta['esito'] . 'idOperazione=' . $risposta['idOperazione'] . 'timeStamp=' . $risposta['timeStamp'] . $this->CHIAVESEGRETA);

			// Controllo MAC di risposta
			if ($risposta['mac'] == $MACrisposta) {

				// Controllo esito
				if ($risposta['esito'] == 'OK')
					$urlRedirect = $risposta['payMailUrl'];
			} else {
				
			}
		} else {
			
		}
		
		return $urlRedirect;
	}
	
	public function checkOrdine()
	{
		$importo = str_replace(".","",$this->ordine["total"]);
		$amount = isset($_REQUEST["importo"]) ? $_REQUEST["importo"] : 0;
		$codTrans = isset($_REQUEST["codTrans"]) ? $_REQUEST["codTrans"] : 0;
		
		if (strcmp($amount,$importo) === 0)
			return true;
		
		$this->statoCheckOrdine = "ORDINE NON TORNA\n";
		$this->statoCheckOrdine .= "DOVUTO: $importo - PAGATO: $amount \n";
		$this->statoCheckOrdine .= "COD TRANS: $codTrans - COD TRANS ORDINE: ".$this->ordine["codice_transazione"]." \n";
		
		$this->statoNotifica = 'OK, pagamento non corretto';
		$this->scriviLog(false, true);
		
		return false;
	}
}
