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
	"lingua::",
	"id_record::",
	"limit::",
));

$default = array(
	"id_record"	=>	0,
	"limit"		=>	10,
);

$params = array_merge($default, $options);

require_once(dirname(__FILE__) . "/../../index.php");

ImpostazioniModel::init();
VariabiliModel::ottieniVariabili();

Files_Log::$logFolder = LIBRARY."/Logs";

if (!isset($params["azione"]))
{
	echo "si prega di selezionare un'azione con l'istruzione --azione=\"<azione>\" \n";
	echo "azioni permesse:\n";
	echo "traduci-tabella-traduzioni -> traduce la tabella traduzioni (testi generici)\n";
	echo "traduci-categorie -> traduce la tabella categories (CATEGORIE)\n";
	echo "traduci-pagine -> traduce la tabella pages (PAGINE e PRODOTTI)\n";
	die();
}

if (!isset($params["lingua"]))
{
	echo "si prega di selezionare il codice ISO della lingua nella quale tradurre (en, fr, de, ...) con l'istruzione --lingua=\"<lingua>\" \n";
	echo "lingue permesse:\n";
	
	$linguePermesse = LingueModel::getValoriAttivi();
	
	foreach ($linguePermesse as $codice => $lingua)
	{
		if ($codice != v("lingua_default_frontend"))
			echo "$codice: $lingua\n";
	}
	die();
}

$log = Files_Log::getInstance("log_traduzioni");

if ($params["azione"] == "traduci-tabella-traduzioni")
{
	$log->writeString("INIZIO TRADUZIONE TABELLA TRADUZIONI");
	
	TraduttoriModel::traduciTabellaTraduzioni($params["lingua"], $params["id_record"], $params["limit"], $log);
	
	$log->writeString("FINE TRADUZIONE TABELLA TRADUZIONI");
}

if ($params["azione"] == "traduci-categorie")
{
	$log->writeString("INIZIO TRADUZIONE TABELLA CATEGORIES");
	
	TraduttoriModel::traduciTabellaContenuti("id_c", $params["lingua"], $params["id_record"], $params["limit"], $log);
	
	$log->writeString("INIZIO TRADUZIONE TABELLA CATEGORIES");
}

if ($params["azione"] == "traduci-pagine")
{
	$log->writeString("INIZIO TRADUZIONE TABELLA PAGES");
	
	TraduttoriModel::traduciTabellaContenuti("id_page", $params["lingua"], $params["id_record"], $params["limit"], $log);
	
	$log->writeString("FINE TRADUZIONE TABELLA PAGES");
}
