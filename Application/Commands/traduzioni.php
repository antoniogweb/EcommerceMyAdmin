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
	echo "traduci-attributi -> traduce la tabella attributi\n";
	echo "traduci-attributi-valori -> traduce la tabella attributi_valori\n";
	echo "traduci-testi -> traduce la tabella testi (TESTI EDITABILI DA FRONTEND)\n";
	echo "traduci-pagamenti -> traduce la tabella pagamenti (PAGAMENTI AL CHECKOUT)\n";
	echo "traduci-stati-ordine -> traduce la tabella stati_ordine (STATI DEGLI ORDINI)\n";
	echo "traduci-caratteristiche -> traduce la tabella caratteristiche (CARATTERISTICHE PRODOTTI)\n";
	echo "traduci-caratteristiche-valori -> traduce la tabella caratteristiche_valori (VALORI DELLE CARATTERISTICHE PRODOTTI)\n";
	echo "traduci-marchi -> traduce la tabella marchi (MARCHI PRODOTTI)\n";
	echo "traduci -> traduce tutti i testi del sito\n";
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
	TraduttoriModel::traduciTabellaTraduzioni($params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-categorie")
	TraduttoriModel::traduciTabellaContenuti("id_c", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-pagine")
	TraduttoriModel::traduciTabellaContenuti("id_page", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-attributi")
	TraduttoriModel::traduciTabellaContenuti("id_a", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-attributi-valori")
	TraduttoriModel::traduciTabellaContenuti("id_av", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-pagamenti")
	TraduttoriModel::traduciTabellaContenuti("id_pagamento", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-stati-ordine")
	TraduttoriModel::traduciTabellaContenuti("id_stato_ordine", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-caratteristiche")
	TraduttoriModel::traduciTabellaContenuti("id_car", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-caratteristiche-valori")
	TraduttoriModel::traduciTabellaContenuti("id_cv", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-marchi")
	TraduttoriModel::traduciTabellaContenuti("id_marchio", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-testi")
	TraduttoriModel::traduciTabellaTesti($params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci")
	TraduttoriModel::traduciTutto($params["lingua"], $params["id_record"], $params["limit"], $log);
