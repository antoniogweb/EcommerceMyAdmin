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

if (!defined('EG')) die('Direct access not allowed!');

trait InitController
{
	public static $traduzioni = array();
	
	public function init()
	{
		VariabiliModel::inizializza();
		
		$this->model("ContenutitradottiModel");
		
		// Estraggo le traduzioni
		$this->model("LingueModel");
		
		self::$traduzioni = $data['elencoTraduzioniAttive'] = LingueModel::getLingueNonPrincipali();
		
		$data['elencoLingue'] = $this->elencoLingue = $this->m["LingueModel"]->clear()->where(array(
			"attiva"	=>	1,
		))->orderBy("id_order")->toList("codice", "descrizione")->send();
		
		$this->model('ImpostazioniModel');
		
		$this->m["ImpostazioniModel"]->getImpostazioni();
		
		// Leggi le impostazioni
		if (ImpostazioniModel::$valori)
		{
			Parametri::$useSMTP = ImpostazioniModel::$valori["usa_smtp"] == "Y" ? true : false;
			Parametri::$SMTPHost = ImpostazioniModel::$valori["smtp_host"];
			Parametri::$SMTPPort = ImpostazioniModel::$valori["smtp_port"];
			Parametri::$SMTPUsername = ImpostazioniModel::$valori["smtp_user"];
			Parametri::$SMTPPassword = ImpostazioniModel::$valori["smtp_psw"];
			Parametri::$mailFrom = ImpostazioniModel::$valori["smtp_from"];
			Parametri::$mailFromName = ImpostazioniModel::$valori["smtp_nome"];
			Parametri::$mailInvioOrdine = ImpostazioniModel::$valori["mail_invio_ordine"];
			Parametri::$mailInvioConfermaPagamento = ImpostazioniModel::$valori["mail_invio_conferma_pagamento"];
			Parametri::$nomeNegozio = ImpostazioniModel::$valori["nome_sito"];
			Parametri::$iva = ImpostazioniModel::$valori["iva"];
			Parametri::$ivaInclusa = ImpostazioniModel::$valori["iva_inclusa"] == "Y" ? true : false;
			Parametri::$mailReplyTo = (isset(ImpostazioniModel::$valori["reply_to_mail"]) && ImpostazioniModel::$valori["reply_to_mail"]) ? ImpostazioniModel::$valori["reply_to_mail"] : Parametri::$mailFrom;
		}
		
		// Variabili
		$this->model('VariabiliModel');
		
		// Traduzioni
		TraduzioniModel::checkTraduzioneAttiva();
		$this->model('TraduzioniModel');
		$this->m["TraduzioniModel"]->ottieniTraduzioni();
		
		Lang_It_UploadStrings::$staticStrings = array(
			"error" => "<div class='alert'>".gtext("Errore: verificare i permessi del file/directory")."</div>\n",
			"executed"	=>	"<div class='alert alert-success'>".gtext("Operazione eseguita!")."</div>",
			"not-child" => "<div class='alert'>".gtext("La cartella selezionata non è una sotto directory della directory base")."</div>\n",
			"not-dir" => "<div class='alert'>".gtext("La cartella selezionata non è una directory")."</div>\n",
			"not-empty" => "<div class='alert'>".gtext("La cartella selezionata non è vuota")."</div>\n",
			"no-folder-specified" => "<div class='alert'>".gtext("Non è stata specificata alcuna cartella")."</div>\n",
			"no-file-specified" => "<div class='alert'>".gtext("Non è stato specificato alcun file")."</div>\n",
			"not-writable" => "<div class='alert'>".gtext("La cartella non è scrivibile")."</div>\n",
			"not-writable-file" => "<div class='alert'>".gtext("Il file non è scrivibile")."</div>\n",
			"dir-exists" => "<div class='alert'>".gtext("Esiste già una directory con lo stesso nome")."</div>\n",
			"no-upload-file" => "<div class='alert'>".gtext("Non c'è alcun file di cui fare l'upload")."</div>\n",
			"size-over" => "<div class='alert'>".gtext("La dimensione del file è troppo grande")."</div>\n",
			"not-allowed-ext" => "<div class='alert'>".gtext("L'estensione del file che vuoi caricare non è consentita")."</div>\n",
			"not-allowed-mime-type" => "<div class='alert'>".gtext("Il tipo MIME del file che vuoi caricare non è consentito")."</div>\n",
			"file-exists" => "<div class='alert'>".gtext("Esiste già un file con lo stesso nome")."</div>\n",
		);
		
		$this->parentRoot = $data['parentRoot'] = Domain::$name = str_replace("/admin",null,$this->baseUrlSrc);
		
		$this->parentRootFolder = $data['parentRootFolder'] = Domain::$parentRoot = str_replace("/admin",null,ROOT);
		
		Domain::$adminRoot = ROOT;
		Domain::$adminName = $this->baseUrlSrc;
		Domain::$publicUrl = str_replace("/admin",null,$this->baseUrlSrc);
		
		Params::$actionArray = "REQUEST";
		
		Params::$rewriteStatusVariables = false;
		
		$data["sidebarCollapsed"] = false;
		
		if (isset($_COOKIE["tipo_sidebar"]) && (int)$_COOKIE["tipo_sidebar"] === 2)
			$data["sidebarCollapsed"] = true;
		
		$this->append($data);
	}
}
