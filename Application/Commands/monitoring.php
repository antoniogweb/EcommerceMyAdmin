#!/usr/bin/php
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

ini_set("memory_limit","-1");

define('APP_CONSOLE', true);
define('EG','allowed');

$options = getopt(null, array(
	"azione::",
	"query::",
	"secondi::",
	"email::",
	"blocca::",
	"giorni::",
	"numero_ip_stessa_rete::",
));

$default = array(
);

$params = array_merge($default, $options);

require_once(dirname(__FILE__) . "/../../index.php");

ImpostazioniModel::init();
VariabiliModel::ottieniVariabili();
Domain::setPathFromAdmin();

Files_Log::$logFolder = LIBRARY."/Logs";

if (!isset($params["azione"]))
{
	echo "si prega di selezionare un'azione con l'istruzione --azione=\"<azione>\" \n";
	die();
}

$log = Files_Log::getInstance("log_monitoring");

if ($params["azione"] == "check-numero-query")
{
	$log->writeString("INIZIO CHECK NUMERO QUERY");
	
	$query = $params["query"] ?? 10000;
	$secondi = $params["secondi"] ?? 60;
	$mail = isset($params["email"]) ? true : false;
	$blocca = isset($params["blocca"]) ? true : false;
	$numero_ip_stessa_rete = $params["numero_ip_stessa_rete"] ?? 30;
	
	$conteggio = ConteggioqueryModel::numeroQuery($query, $secondi, $numero_ip_stessa_rete);
	
	if (!empty($conteggio) && $mail)
		MailordiniModel::inviaMailLog("Superato il limite di $query query negli ultimi $secondi secondi", "<pre>".json_encode($conteggio,JSON_PRETTY_PRINT)."</pre>", "LIMITE QUERY");
	
	if (!empty($conteggio))
	{
		$log->writeString("IP\n".json_encode($conteggio,JSON_PRETTY_PRINT));
		
		print_r($conteggio);
	}
	
	if (!empty($conteggio) && $blocca)
	{
		Shield::blockIps($conteggio, $secondi);
		
		$log->writeString("Gli IP sono stati bloccati");
	}
	
	Shield::freeTempIps($log);
	
	$log->writeString("FINE CHECK NUMERO QUERY");
}

if ($params["azione"] == "svuota-query")
{
	$log->writeString("INIZIO ELIMINAZIONE CONTEGGIO VECCHIE QUERY");
	
	$giorni = $params["giorni"] ?? 10;
	
	ConteggioqueryModel::svuotaConteggioQueryPiuVecchioDiGiorni($giorni);
	
	$log->writeString("FINE ELIMINAZIONE CONTEGGIO VECCHIE QUERY");
}
