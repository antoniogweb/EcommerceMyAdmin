#!/usr/bin/php
<?php

define('APP_CONSOLE', true);

$options = getopt(null, array("prod::"));

$default = array(
	"prod"	=>	0,
);

$params = array_merge($default, $options);

require_once(dirname(__FILE__) . "/../../../index.php");

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

