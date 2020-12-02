<?php

// All EasyGiant code is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// See COPYRIGHT.txt and LICENSE.txt.

if (!defined('EG')) die('Direct access not allowed!');

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

Params::$frontEndLanguages = array("it","en");
Params::$allowSessionIdFromGet = true;

$mysqli = Db_Mysqli::getInstance();
$mysqli->query("set session sql_mode=''");

//includo il file di parametri dall'admin
require(LIBRARY."/Application/Include/language.php");
require(LIBRARY."/Application/Include/functions.php");
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

Helper_Pages::$pageLinkWrap = array("li");
Helper_Pages::$pageLinkWrapClass = array("");
Helper_Pages::$staticLinkClass = "page-numbers";
Helper_Pages::$staticPreviousClass = "prev";
Helper_Pages::$staticNextClass = "next";
Helper_Pages::$staticCurrentClass = "current";

if (v("attiva_ip_location"))
	IplocationModel::setData();

if (v("theme_folder"))
	Params::$viewSubfolder = v("theme_folder");
