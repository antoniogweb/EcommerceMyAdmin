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

if (!defined('EG')) die('Direct access not allowed!');

date_default_timezone_set('Europe/Rome');

Params::$logFunctionBeforeRedirect = array("F","checkPreparedStatement");

VariabiliModel::ottieniVariabili();

if (VariabiliModel::valore("usa_transactions"))
	Users_CheckAdmin::$useConcurrencyCheckInLastFailureTime = true;

Users_CheckAdmin::$usersModel = "RegusersModel";
Users_CheckAdmin::$groupsModel = "ReggroupsModel";
Users_CheckAdmin::$sessionsModel = "RegsessioniModel";
Users_CheckAdmin::$accessesModel = "RegaccessiModel";

Cache_Db::$cachedTables = array("categories", "pages", "tag", "marchi", "testi", "lingue", "pages_personalizzazioni", "reggroups_categories", "contenuti", "prodotti_correlati", "traduzioni", "menu", "menu_sec", "nazioni", "ruoli", "pages_attributi", "personalizzazioni", "contenuti_tradotti", "tipi_clienti", "fasce_prezzo", "documenti", "immagini", "attributi_valori", "caratteristiche_valori", "pages_caratteristiche_valori", "pages_pages", "pagamenti", "captcha");

if (defined("CACHE_FOLDER"))
{
	Cache_Db::$cacheFolder = ROOT."/".ltrim(CACHE_FOLDER,"/");
	Cache_Db::$cacheMinutes = VariabiliModel::valore("query_cache_durata_massima");
	Cache_Db::$useRandomPeriods = VariabiliModel::valore("query_cache_usa_periodi_random");
	Cache_Db::$minutesOfPeriod = VariabiliModel::valore("query_cache_minuti_tra_periodi");
	Cache_Db::$cleanCacheEveryXMinutes = VariabiliModel::valore("query_cache_pulisci_ogni_x_minuti");
	Cache_Db::$maxNumberOfFilesCached = VariabiliModel::valore("numero_massimo_file_cache");
	Cache_Db::deleteExpired();
}

// Files_Log::$logFolder = ROOT."/Logs";
// Files_Log::getInstance("log_generico");

Theme::$alternativeViewFolders = array(
	LIBRARY . "/Frontend/Application/Views/_",
);

Controller::$alternativeControllerFolders = array(
	LIBRARY . "/Frontend/Application/".getApplicationPath().'Controllers',
);

// Carica le App
if (!defined("APPS"))
	ApplicazioniModel::carica();

// Imposto le app
if (defined("APPS"))
{
	Params::$installed = APPS;
	
	foreach (APPS as $app)
	{
		include(LIBRARY."/Application/Hooks/BeforeChecksVariabili.php");
	}
}

Params::$exactUrlMatchRewrite = true;

Params::$allowSessionIdFromGet = true;
Params::$errorStringClassName = "uk-alert uk-alert-danger";

// $mysqli = Db_Mysqli::getInstance();
$mysqli = Factory_Db::getInstance(DATABASE_TYPE);

if (!isset($_GET["url"]) || substr( $_GET["url"], 0, 6 ) !== "thumb/")
	$mysqli->query("set session sql_mode=''");

Params::$language = "It";
Params::$translatorFunction = "gtext";

//includo il file di parametri dall'admin
require(LIBRARY."/Application/Include/language.php");
require(LIBRARY."/Application/Include/functions.php");

// Lingua frontend principale
if (v("lingua_default_frontend"))
	Params::$defaultFrontEndLanguage = v("lingua_default_frontend");
else
	Params::$defaultFrontEndLanguage = LingueModel::getPrincipaleFrontend();

// Se abilita tutte le lingue a DB o solo quelle da variabile di sistema
if (v("abilita_tutte_le_lingue_attive"))
	Params::$frontEndLanguages = array_keys(LingueModel::getValoriAttivi());
else if (v("lingue_abilitate_frontend"))
	Params::$frontEndLanguages = explode(",", v("lingue_abilitate_frontend"));

if (v("attiva_nazione_nell_url"))
	Params::$frontEndCountries = array_map("strtolower",NazioniModel::g(false)->selectCodiciAttivi());

require(LIBRARY."/Application/Include/parametri.php");
require(LIBRARY."/Application/Include/user.php");
require(LIBRARY."/Application/Include/output.php");
require(LIBRARY."/Application/Include/tema.php");
require_once(LIBRARY."/Frontend/Application/Hooks/BeforeChecksLegacy.php");

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

// Cache HTML
if (defined("SAVE_CACHE_HTML") && isset($_SERVER["REQUEST_URI"]))
{
	$cacheKey = $_SERVER["REQUEST_URI"];
	
	$partialKey = "";
	
	if (User::$isPhone)
		$partialKey = "_PHONE";
	else if (User::$isTablet)
		$partialKey = "_TABLET";
	else
		$partialKey = "_DESK";
	
	if (empty($_POST))
	{
		Cache_Html::$maxNumberOfFilesCached = v("numero_massimo_file_cache_html");
		$cache = Cache_Html::getInstance(ROOT."/Logs", "cachehtml");
		$cache->loadHtml = true;
		$cache->cacheKey = $cacheKey.$partialKey;
		$cache->partialKey = $partialKey;
	}
}

Helper_Pages::$pageLinkWrap = array("li");
Helper_Pages::$pageLinkWrapClass = array("");
Helper_Pages::$staticLinkClass = "page-numbers";
Helper_Pages::$staticPreviousClass = "prev";
Helper_Pages::$staticNextClass = "next";
Helper_Pages::$staticCurrentClass = "uk-text-secondary";
Helper_Pages::$staticPreviousString = '<span uk-pagination-previous></span>';
Helper_Pages::$staticNextString = '<span uk-pagination-next></span>';
Helper_Pages::$staticAttributesPageFromSecond = v("attributi_link_pagina_2_in_poi");

if (v("permessi_cartella_cache_immagini"))
	Image_Gd_Thumbnail::$cacheFolderFilesPermission = octdec(v("permessi_cartella_cache_immagini"));

if (v("attiva_ip_location"))
{
	if (!isset($_GET["url"]) || substr( $_GET["url"], 0, 6 ) !== "thumb/")
		IplocationModel::setData();
}

if (v("theme_folder"))
	Params::$viewSubfolder = v("theme_folder");

Image_Gd_Thumbnail::$defaultJpegImgQuality = v("qualita_immagini_jpeg_default");

ImpostazioniModel::init();

if (!defined("FRONT"))
	define('FRONT', ROOT);

Domain::setPath();

// User::$nazione = User::$nazioneNavigazione = "FR";
