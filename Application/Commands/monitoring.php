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
	"limit::",
	"nazioni::",
	"numero::",
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
	
	arsort($conteggio);
	
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

if ($params["azione"] == "check-numero-query-network")
{
	$log->writeString("INIZIO CHECK NUMERO QUERY NETWORK");
	
	$query = $params["query"] ?? 10000;
	$secondi = $params["secondi"] ?? 60;
	$mail = isset($params["email"]) ? true : false;
	
	$conteggio = ConteggioqueryModel::numeroQueryNetwork($query, $secondi);
	
	arsort($conteggio);
	
	if (!empty($conteggio) && $mail)
		MailordiniModel::inviaMailLog("Superato il limite di $query query negli ultimi $secondi secondi", "<pre>".json_encode($conteggio,JSON_PRETTY_PRINT)."</pre>", "LIMITE QUERY");
	
	if (!empty($conteggio))
	{
		$log->writeString("IP\n".json_encode($conteggio,JSON_PRETTY_PRINT));
		
		print_r($conteggio);
	}
	
	Shield::freeTempIps($log);
	
	$log->writeString("FINE CHECK NUMERO QUERY NETWORK");
}

if ($params["azione"] == "check-numero-query-nazione")
{
	$log->writeString("INIZIO CHECK NUMERO QUERY NAZIONE");
	
	$query = $params["query"] ?? 10000;
	$secondi = $params["secondi"] ?? 60;
	$mail = isset($params["email"]) ? true : false;
	
	$conteggio = ConteggioqueryModel::numeroQueryNazione($query, $secondi);
	
	arsort($conteggio);
	
	if (!empty($conteggio) && $mail)
		MailordiniModel::inviaMailLog("Superato il limite di $query query negli ultimi $secondi secondi", "<pre>".json_encode($conteggio,JSON_PRETTY_PRINT)."</pre>", "LIMITE QUERY");
	
	if (!empty($conteggio))
	{
		$log->writeString("IP\n".json_encode($conteggio,JSON_PRETTY_PRINT));
		
		print_r($conteggio);
	}
	
	Shield::freeTempIps($log);
	
	$log->writeString("FINE CHECK NUMERO QUERY NAZIONE");
}

if ($params["azione"] == "check-numero-query-ip-nazioni")
{
	$log->writeString("INIZIO BLOCCO IP NAZIONI");
	
	if (isset($params["nazioni"]))
	{
		$secondi = $params["secondi"] ?? 60;
		$nazioniArray = explode(",", $params["nazioni"]);
		$blocca = isset($params["blocca"]) ? true : false;
		
		$conteggio = ConteggioqueryModel::ipNazioni($secondi, $nazioniArray);
		
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
	}
	
	Shield::freeTempIps($log);
	
	$log->writeString("FINE BLOCCO IP NAZIONI");
}

if ($params["azione"] == "svuota-query")
{
	$log->writeString("INIZIO ELIMINAZIONE CONTEGGIO VECCHIE QUERY");
	
	$giorni = $params["giorni"] ?? 10;
	
	ConteggioqueryModel::svuotaConteggioQueryPiuVecchioDiGiorni($giorni);
	
	$log->writeString("FINE ELIMINAZIONE CONTEGGIO VECCHIE QUERY");
}

if ($params["azione"] == "geolocalizza")
{
	$log->writeString("INIZIO GEOLOCALIZZAZIONE");
	
	$secondi = $params["secondi"] ?? 60;
	$limit = $params["limit"] ?? 100;
	
	ConteggioqueryModel::geolocalizzaIp($secondi, $limit);
	
	$log->writeString("FINE GEOLOCALIZZAZIONE");
}

// Crea la cartella con le immagini captcha anti DDOS
if ($params["azione"] == "crea-captcha-ddos")
{
	$log->writeString("INIZIO CREAZIONE CAPTCHA");
	
	$numero = $params["numero"] ?? 120;
	
	Shield::creaCapctaDDOS($numero);
	
	$log->writeString("FINE CREAZIONE CAPTCHA");
}

// Elimina la cartella con le immagini captcha anti DDOS
if ($params["azione"] == "elimina-captcha-ddos")
{
	$log->writeString("INIZIO ELIMINAZIONE CAPTCHA");
	
	if (is_dir(LIBRARY."/Logs/CaptchaDDOS"))
	{
		$nuovoNome = randomToken(20);
		rename(LIBRARY."/Logs/CaptchaDDOS", LIBRARY."/Logs/$nuovoNome");
		PagesModel::eliminaCartella(LIBRARY."/Logs/$nuovoNome");
	}
	
	$log->writeString("FINE ELIMINAZIONE CAPTCHA");
}