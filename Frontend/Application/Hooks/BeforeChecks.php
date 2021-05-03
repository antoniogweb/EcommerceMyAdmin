<?php

// All EasyGiant code is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// See COPYRIGHT.txt and LICENSE.txt.

if (!defined('EG')) die('Direct access not allowed!');

Cache::$cachedTables = array("categories", "pages", "tag", "marchi", "testi", "lingue", "pages_personalizzazioni", "reggroups_categories", "contenuti", "prodotti_correlati", "traduzioni", "menu", "menu_sec", "nazioni", "ruoli", "pages_attributi", "personalizzazioni", "contenuti_tradotti");

if (defined("CACHE_FOLDER"))
{
	Cache::$cacheFolder = CACHE_FOLDER;
	Cache::$cacheMinutes = 60;
	Cache::$cleanCacheEveryXMinutes = 70;
	Cache::deleteExpired();
}

VariabiliModel::ottieniVariabili();

// Se arriva dalla app usa php://input
if (isset($_GET["fromApp"]))
{
	$rawData = file_get_contents("php://input");
	
	if ($rawData)
		$_POST = json_decode($rawData, true);
}

//in this file you can write the PHP code that will be executed at the beginning of the EasyGiant execution, before super global array have been sanitizied

//this is the preferred place to create and fill log files

//you can access the whole set of classes and functions of EasyGiant

Params::$exactUrlMatchRewrite = true;

Params::$allowSessionIdFromGet = true;
Params::$errorStringClassName = "uk-alert uk-alert-danger";

$mysqli = Db_Mysqli::getInstance();

if (!isset($_GET["url"]) || substr( $_GET["url"], 0, 6 ) !== "thumb/")
	$mysqli->query("set session sql_mode=''");

//includo il file di parametri dall'admin
require(LIBRARY."/Application/Include/language.php");
require(LIBRARY."/Application/Include/functions.php");

if (v("lingue_abilitate_frontend"))
	Params::$frontEndLanguages = explode(",", v("lingue_abilitate_frontend"));

require(LIBRARY."/Application/Include/parametri.php");
require(LIBRARY."/Application/Include/user.php");
require(LIBRARY."/Application/Include/output.php");

if (!v("traduzione_frontend"))
	Lang::$edit = false;

Domain::$adminRoot = LIBRARY;

require(LIBRARY."/External/mobile_detect.php");

if (v("usa_https"))
	Params::$useHttps = true;

$detect = new Mobile_Detect();
User::$isMobile = $detect->isMobile();
User::$isTablet = $detect->isTablet();
User::$isPhone = ($detect->isMobile() && !$detect->isTablet());

Helper_Pages::$pageLinkWrap = array("li");
Helper_Pages::$pageLinkWrapClass = array("");
Helper_Pages::$staticLinkClass = "page-numbers";
Helper_Pages::$staticPreviousClass = "prev";
Helper_Pages::$staticNextClass = "next";
Helper_Pages::$staticCurrentClass = "uk-text-secondary";
Helper_Pages::$staticPreviousString = '<span uk-pagination-previous></span>';
Helper_Pages::$staticNextString = '<span uk-pagination-next></span>';

if (v("attiva_ip_location"))
{
	if (!isset($_GET["url"]) || substr( $_GET["url"], 0, 6 ) !== "thumb/")
		IplocationModel::setData();
}

if (v("theme_folder"))
	Params::$viewSubfolder = v("theme_folder");
