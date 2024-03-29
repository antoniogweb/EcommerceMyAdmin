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
define('EG','allowed');

$options = getopt(null, array(
	"prod::",
	"lingua::",
	"nazione::"
));

$default = array(
	"prod"		=>	0,
	"lingua"	=>	"it",
	"nazione"	=>	"it",
);

$params = array_merge($default, $options);

include_once(dirname(__FILE__)."/../../config.php");

if (isset($params["dominio"]))
	$website_domain_name = $params["dominio"];

define('DOMAIN_NAME',$website_domain_name);

require_once(dirname(__FILE__) . "/../../index.php");

ImpostazioniModel::init();
VariabiliModel::ottieniVariabili();
Domain::$parentRoot = ROOT."/..";
Domain::$publicUrl = rtrim(Url::getFileRoot(), "/");

Params::$lang = $params["lingua"];
Params::$country = $params["nazione"];

Files_Log::$logFolder = LIBRARY."/Logs";

if (!$params["prod"])
	EventiretargetingModel::setDebug();

if ($params["prod"])
{
	$log = Files_Log::getInstance("retargeting");
	$log->writeString("INIZIO LOG RETARGETING");
}

EventiretargetingModel::processa();

if ($params["prod"])
	$log->writeString("FINE LOG RETARGETING");

if (!$params["prod"])
	print_r(EventiretargetingModel::$debugResult);

