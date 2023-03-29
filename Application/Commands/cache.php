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

$options = getopt(null, array(
	"lingua::",
	"nazione::",
	"url::",
	"crea_elenco::",
));

$default = array(
	"lingua"		=>	"it",
	"nazione"		=>	"it",
);

$params = array_merge($default, $options);

$creaCache = false;
$creaFileCache = false;

if (isset($params["url"]))
{
	$creaCache = true;
	
	$_GET["url"] = $params["url"];
	$_SERVER['REQUEST_URI'] = "/".$params["url"];
}

if (isset($params["crea_elenco"]))
	$creaFileCache = true;

// define ("CACHE_FOLDER", "Logs/cache");
// define ('CACHE_METHODS_TO_FILE',true);

require_once(dirname(__FILE__) . "/../../../index.php");

Params::$lang = $params["lingua"];
Params::$country = $params["nazione"];

Files_Log::$logFolder = LIBRARY."/Logs";
$log = Files_Log::getInstance("cache_sito");

if ($creaCache)
{
	ob_start();
	callHook();
	$output = ob_get_clean();
	
	$cache = Cache_Html::getInstance();
	echo md5($cache->cacheKey).".php";
}
else if ($creaFileCache)
{
	$log->writeString("INIZIO CREAZIONE ELENCO PAGINE");
// 	$c = new CategoriesModel();
	$p = new PagesModel();
	$combModel = new CombinazioniModel();
	
	$combinazioni = $combModel->clear()->select("combinazioni.*")->inner(array("pagina"))->addWhereAttivo()->aWhere(array(
		"combinazioni.acquistabile"	=>	1,
	))->send();
	
	$elencoUrl = "";
	
	foreach ($combinazioni as $c)
	{
		$url = $p->getUrlAlias($c["combinazioni"]["id_page"], $params["lingua"], $c["combinazioni"]["id_c"]);
		$elencoUrl .= $url."\n";
		
		echo $url."\n";
	}
	
	FilePutContentsAtomic(LIBRARY."/Logs/elenco_url_da_salvare_in_cache.txt", $elencoUrl);
	
	$log->writeString("FINE CREAZIONE ELENCO PAGINE");
}
