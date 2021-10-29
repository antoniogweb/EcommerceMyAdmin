#!/usr/bin/php
<?php

define('ROOT', dirname(dirname(dirname(__FILE__))));

require_once(ROOT . "/Library/Console.php");

Files_Log::$logFolder = LIBRARY."/Logs";

$log = Files_Log::getInstance("retargeting");
$log->writeString("INIZIO LOG RETARGETING");
$log->writeString("FINE LOG RETARGETING");

