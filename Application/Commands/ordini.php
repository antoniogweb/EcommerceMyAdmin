#!/usr/bin/php
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

date_default_timezone_set('Europe/Rome');

ini_set("memory_limit","-1");

define('APP_CONSOLE', true);
define('EG','allowed');

$options = getopt(null, array(
	"azione::",
	"stati::",
	"numero_minuti::",
));

$default = array(
);

$params = array_merge($default, $options);

require_once(dirname(__FILE__) . "/../../index.php");

ImpostazioniModel::init();
VariabiliModel::ottieniVariabili();

Files_Log::$logFolder = LIBRARY."/Logs";

if (!isset($params["azione"]))
{
	echo "si prega di indicare un'azione con l'istruzione --azione=\"<azione>\" \n";
	die();
}

if (!isset($params["stati"]))
{
	echo "si prega di indicare l'elenco dei codici degli stati divisi da una virgola con l'istruzione --stati=\"<stati>\" \n";
	die();
}

if (!isset($params["numero_minuti"]) || !is_numeric($params["numero_minuti"]) && (int)$params["numero_minuti"] > 0)
{
	echo "si prega di indicare un numero di minuti maggiore di 0 con l'istruzione --numero_minuti=\"<numero_minuti>\" \n";
	die();
}

$log = Files_Log::getInstance("log_comandi_ordini");

if ($params["azione"] == "annulla-ordini")
{
	$log->writeString("INIZIO ANNULLAMENTO ORDINI");
	
	$numeroMinuti = (int)$params["numero_minuti"];
	
	if ($numeroMinuti > 0)
	{
		$elencoStati = explode(",", $params["stati"]);
		
		$tempo = time() - ($numeroMinuti * 60);
		
		$o = new OrdiniModel();
		
		$ordiniDaAnnullare = $o->clear()->where(array(
			"in"	=>	array(
				"stato"	=>	sanitizeAllDeep($elencoStati)
			),
			"tipo_ordine"	=>	"W",
			"fonte"			=>	"ORDINE_WEB",
		))->sWhere(array(
			"creation_time < ?", array($tempo)
		))->send(false);
		
		foreach ($ordiniDaAnnullare as $ordine)
		{
			$o->sValues(array(
				"stato"	=>	"deleted",
				"note_interne"	=>	(string)$ordine["note_interne"]." - Ordine con pagamento online annullato in automatico dopo $numeroMinuti minuti dal mancato pagamento"
			));
			
			$o->update($ordine["id_o"]);
			
			$log->writeString("ANNULLATO ORDINE ".$ordine["id_o"]);
		}
	}
	
	$log->writeString("FINE ANNULLAMENTO ORDINI");
}
