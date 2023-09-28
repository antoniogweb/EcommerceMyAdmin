#!/usr/bin/php
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

ini_set("memory_limit","-1");

define('APP_CONSOLE', true);
define('EG','allowed');

$options = getopt(null, array(
	"azione::",
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

$log = Files_Log::getInstance("log_spedizioni");

if ($params["azione"] == "conferma-spedizioni-prenotate")
{
	$log->writeString("INIZIO INVIO SPEDIZIONI");
	
	SpedizioninegozioinviiModel::g()->inviaAlCorriere();
	
	$log->writeString("FINE INVIO SPEDIZIONI");
}

if ($params["azione"] == "get-info-tracking")
{
	$log->writeString("INIZIO RICHIESTA INFO TRACKING");
	
	SpedizioninegozioModel::g()->controllaStatoSpedizioniInviate(0, 20, 1);
	
	$log->writeString("FINE RICHIESTA INFO TRACKING");
}
