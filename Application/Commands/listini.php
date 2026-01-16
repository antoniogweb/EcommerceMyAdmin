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

define('APP_CONSOLE', true);
define('EG','allowed');

$options = getopt(null, array(
	"azione::",
	"listino::",
));

$default = array(
	"listino"	=>	"IT",
);

$params = array_merge($default, $options);

include_once(dirname(__FILE__)."/../../config.php");

define('DOMAIN_NAME',$website_domain_name);

require_once(dirname(__FILE__) . "/../../index.php");

ImpostazioniModel::init();
VariabiliModel::ottieniVariabili();
Domain::$parentRoot = ROOT."/..";

if ($params["listino"] != "W")
	Params::$country = strtolower($params["listino"]);

Files_Log::$logFolder = LIBRARY."/Logs";

if (!isset($params["azione"]))
{
	echo "si prega di selezionare un'azione con l'istruzione --azione=\"<azione>\" \n";
	die();
}

$listiniPermessi = CombinazionilistiniModel::elencoListiniPermessi();

if (!isset($listiniPermessi[$params["listino"]]))
{
	echo "si prega di selezionare il listino usando il codice ISO della nazione (FR, ES, DE, ...) con l'istruzione --listino=\"<nazione>\" \n";
	echo "listini permessi:\n";

	foreach ($listiniPermessi as $codice => $listino)
	{
		if ($codice != v("nazione_default"))
			echo "$codice: $listino\n";
	}
	die();
}

$log = Files_Log::getInstance("log_listini");

if ($params["azione"] == "crea-listino")
{
	User::setPostCountryFromUrl();
	
	IvaModel::getAliquotaEstera();
	
	$log->writeString("INIZIO CREAZIONE LISTINO NAZIONE ".$params["listino"]);
	
	CombinazionilistiniModel::g(false)->creaListino($params["listino"]);
	
	$log->writeString("FINE CREAZIONE LISTINO NAZIONE ".$params["listino"]);
}

if ($params["azione"] == "sincronizza-listini")
{
	$log->writeString("INIZIO SINCRONIZZAZIONE LISTINI");
	
	$cModel = new CombinazioniModel();
	
	$cModel->aggiornaPrezzoListini(0, $log);
	
	$log->writeString("FINE SINCRONIZZAZIONE LISTINI");
}