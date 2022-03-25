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

Cache::$cachedTables = array("categories", "pages", "tag", "marchi", "testi", "lingue", "pages_personalizzazioni", "reggroups_categories", "contenuti", "prodotti_correlati", "traduzioni", "menu", "menu_sec", "nazioni", "ruoli", "pages_attributi", "personalizzazioni", "contenuti_tradotti");

if (defined("CACHE_FOLDER"))
{
	Cache::$cacheFolder = CACHE_FOLDER;
	Cache::$cacheMinutes = 60;
	Cache::$cleanCacheEveryXMinutes = 70;
	Cache::deleteExpired();
}

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

VariabiliModel::ottieniVariabili();

Params::$exactUrlMatchRewrite = true;

Params::$allowSessionIdFromGet = true;
Params::$errorStringClassName = "uk-alert uk-alert-danger";

$mysqli = Db_Mysqli::getInstance();

if (!isset($_GET["url"]) || substr( $_GET["url"], 0, 6 ) !== "thumb/")
	$mysqli->query("set session sql_mode=''");

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

Helper_Pages::$pageLinkWrap = array("li");
Helper_Pages::$pageLinkWrapClass = array("");
Helper_Pages::$staticLinkClass = "page-numbers";
Helper_Pages::$staticPreviousClass = "prev";
Helper_Pages::$staticNextClass = "next";
Helper_Pages::$staticCurrentClass = "uk-text-secondary";
Helper_Pages::$staticPreviousString = '<span uk-pagination-previous></span>';
Helper_Pages::$staticNextString = '<span uk-pagination-next></span>';

if (v("permessi_cartella_cache_immagini"))
	Image_Gd_Thumbnail::$cacheFolderFilesPermission = octdec(v("permessi_cartella_cache_immagini"));

if (v("attiva_ip_location"))
{
	if (!isset($_GET["url"]) || substr( $_GET["url"], 0, 6 ) !== "thumb/")
		IplocationModel::setData();
}

if (v("theme_folder"))
	Params::$viewSubfolder = v("theme_folder");

ImpostazioniModel::init();

if (!defined("FRONT"))
	define('FRONT', ROOT);

Domain::setPath();

Form_Entry::$defaultLabelClass = "uk-form-label";
Form_Entry::$defaultWrap = array('<div class="uk-margin">',null,'<div class="uk-form-controls">','</div>',"</div>");
Form_Entry::$defaultInputClasses["input"] = "uk-input";
Form_Entry::$defaultInputClasses["select"] = "uk-select";

Helper_Menu::$htmlLinks = array(
	"back" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'class="uk-button uk-button-primary uk-button-small"',
		'text'	=>	"Torna",
		"classIconBefore"	=>	'<span uk-icon="icon: arrow-left"></span>',
	),
	"add" => array(
		"htmlBefore" => '',
		"htmlAfter" => '',
		"attributes" => 'class="uk-button uk-button-primary uk-button-small"',
		'text'	=>	"Nuovo",
		"classIconBefore"	=>	'<span uk-icon="icon: plus"></span>',
	),
);

Form_Form::$defaultEntryAttributes["submitClass"] = "uk-button uk-button-secondary";

Helper_List::$tableAttributes = array('class'=>'uk-table uk-table-divider uk-table-striped uk-table-hover uk-table-small','cellspacing'=>'0');
