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

if (!defined('EG')) die('Direct access not allowed!');

$default = array();

$params = array_merge($default, $options);

ImpostazioniModel::init();
VariabiliModel::ottieniVariabili();
Domain::setPathFromAdmin();

Files_Log::$logFolder = LIBRARY."/Logs";

if (!isset($params["azione"]))
{
	echo "si prega di selezionare un'azione con l'istruzione --azione=\"<azione>\" \n";
	die();
}

$log = Files_Log::getInstance("log_log_tecnici");

if ($params["azione"] == "notifica-log-tecnici")
{
	$log->writeString("INIZIO NOTIFICA LOG TECNICI");
	
	$struttura = LogtecniciModel::notifica($log);
	
	if (!empty($struttura))
		print_r($struttura);
	
	$log->writeString("FINE NOTIFICA LOG TECNICI");
}

if ($params["azione"] == "check-ip-log-tecnici")
{
	$log->writeString("INIZIO CHECK IP LOG TECNICI");
	
	LogtecniciModel::controllaIp();
	
	$log->writeString("FINE CHECK IP LOG TECNICI");
}

if ($params["azione"] == "check-ip-e-notifica")
{
	$log->writeString("INIZIO CHECK IP E NOTIFICA LOG TECNICI");
	
	LogtecniciModel::controllaIp();
	$struttura = LogtecniciModel::notifica($log);
	
	$log->writeString("FINE CHECK IP E NOTIFICA LOG TECNICI");
}