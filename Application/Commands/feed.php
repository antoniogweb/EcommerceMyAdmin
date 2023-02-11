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

ini_set("memory_limit","-1");

define('APP_CONSOLE', true);
define('EG','allowed');

$options = getopt(null, array(
	"modulo::",
	"lingua::",
	"nazione::",
	"path::",
));

$default = array(
	"lingua"		=>	"it",
	"nazione"		=>	"it",
);

$params = array_merge($default, $options);

include_once("../../config.php");
define('DOMAIN_NAME',$website_domain_name);

require_once(dirname(__FILE__) . "/../../index.php");

ImpostazioniModel::init();
VariabiliModel::ottieniVariabili();

Params::$lang = $params["lingua"];
Params::$country = $params["nazione"];

Files_Log::$logFolder = LIBRARY."/Logs";
$log = Files_Log::getInstance("creazione_feed");

if (!isset($params["modulo"]))
{
	echo "si prega di selezionare il modulo con l'istruzione --modulo=\"<modulo>\"";
	die();
}

if (!isset($params["path"]))
{
	echo "si prega di selezionare il percorso del file dove salvare il feed con l'istruzione --path=\"<path>\"";
	die();
}

$modulo = strtoupper((string)$params["modulo"]);

if (FeedModel::getModulo($modulo)->isAttivo())
{
	User::setPostCountryFromUrl();
	
	IvaModel::getAliquotaEstera();
	
	FeedModel::getModulo($modulo)->feedProdotti(null, $params["path"]);
}

