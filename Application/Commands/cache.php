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
define('CACHE_COMMAND', true);

$options = getopt(null, array(
	"lingua::",
	"nazione::",
	"url::",
	"crea_elenco::",
	"dispositivo::",
));

$default = array(
	"lingua"		=>	"it",
	"nazione"		=>	"it",
	"dispositivo"	=>	"_DESK",
);

$params = array_merge($default, $options);

$creaCache = false;
$creaFileCache = false;

if (isset($params["url"]))
{
	$creaCache = true;
	
	define ('PARTIAL_KEY',$params["dispositivo"]);
	
	$_GET["url"] = $params["url"];
	$_SERVER['REQUEST_URI'] = "/".$params["url"];
}

if (isset($params["crea_elenco"]))
	$creaFileCache = true;

require_once(dirname(__FILE__) . "/../../../index.php");

Params::$lang = $params["lingua"];
Params::$country = $params["nazione"];

Files_Log::$logFolder = LIBRARY."/Logs";
$log = Files_Log::getInstance("cache_sito");

if ($creaCache)
{
	if ($params["dispositivo"] == "_PHONE")
		User::$isPhone = User::$isMobile = true;
	else
		User::$isPhone = User::$isMobile = false;
	
	// Imposta operazione schedulata: non salvare i file del carrello e quelli delle statistiche
	App::$operazioneSchedulata = true;
	
	ob_start();
	callHook();
	$output = ob_get_clean();
	
	$cache = Cache_Html::getInstance();
	echo md5($cache->cacheKey).".php";
}
else if ($creaFileCache)
{
	$log->writeString("INIZIO CREAZIONE ELENCO PAGINE");
	$cModel = new CategoriesModel();
	$p = new PagesModel();
	$combModel = new CombinazioniModel();
	
	$idShop = $cModel->getShopCategoryId();
	
	$idsCat = $cModel->children($idShop, true, true);
	
	$elencoUrl = "";
	
	foreach ($idsCat as $idC)
	{
		$url = $cModel->getUrlAlias($idC, $params["lingua"]);
		$elencoUrl .= $url."\n";
		
		echo $url."\n";
	}
	
	$combinazioni = $combModel->clear()->select("combinazioni.*")->inner(array("pagina"))->addWhereAttivo()->aWhere(array(
		"combinazioni.acquistabile"	=>	1,
	))->send();
	
	foreach ($combinazioni as $c)
	{
		$url = $p->getUrlAlias($c["combinazioni"]["id_page"], $params["lingua"], $c["combinazioni"]["id_c"]);
		$elencoUrl .= $url."\n";
		
		echo $url."\n";
	}
	
	FilePutContentsAtomic(LIBRARY."/Logs/elenco_url_da_salvare_in_cache.txt", $elencoUrl);
	
	$log->writeString("FINE CREAZIONE ELENCO PAGINE");
}
