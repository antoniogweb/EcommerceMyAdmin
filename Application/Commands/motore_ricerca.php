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

define('APP_CONSOLE', true);

$options = getopt(null, array(
	"lingua::",
	"nazione::",
	"operazione::",
));

$default = array(
	"lingua"		=>	"it",
	"nazione"		=>	"it",
	"operazione"	=>	"cerca",
);

$params = array_merge($default, $options);

require_once(dirname(__FILE__) . "/../../index.php");

ImpostazioniModel::init();

Files_Log::$logFolder = LIBRARY."/Logs";
$log = Files_Log::getInstance("motori_ricerca");

if (MotoriricercaModel::getModulo()->isAttivo())
{
	Params::$lang = $params["lingua"];
	Params::$country = $params["nazione"];
	
// 	print_r($params);
	if ($params["operazione"] == "invia_oggetti")
		$res = MotoriricercaModel::getModulo()->inviaProdotti(0, "prodotti_".$params["lingua"]);
	else if ($params["operazione"] == "svuota_oggetti")
		$res = MotoriricercaModel::getModulo()->svuotaProdotti("prodotti_".$params["lingua"]);
		
	print_r($res);
}

