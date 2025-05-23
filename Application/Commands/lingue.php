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
define('EG','allowed');

$options = getopt(null, array(
	"azione::",
	"lingua::",
));

$default = array();

$params = array_merge($default, $options);

require_once(dirname(__FILE__) . "/../../index.php");

ImpostazioniModel::init();
VariabiliModel::ottieniVariabili();

Files_Log::$logFolder = LIBRARY."/Logs";

if (!isset($params["azione"]))
{
	echo "si prega di selezionare un'azione con l'istruzione --azione=\"<azione>\" \n";
	echo "azioni permesse:\n";
	echo "attiva-lingua -> attiva la lingua specificata nel parametro <lingua>\n";
	echo "rigenera-traduzioni-lingua -> rigenera le traduzioni per tutte le lingue\n";
	die();
}

$linguePermesse = LingueModel::getValori();

if (!isset($params["lingua"]) && (!isset($params["azione"]) || $params["azione"] != "rigenera-traduzioni-lingua"))
{
	echo "si prega di selezionare il codice ISO della lingua nella quale tradurre (en, fr, de, ...) con l'istruzione --lingua=\"<lingua>\" \n";
	echo "lingue permesse:\n";

	foreach ($linguePermesse as $codice => $lingua)
	{
		if ($codice != v("lingua_default_frontend"))
			echo "$codice: $lingua\n";
	}
	die();
}

$log = Files_Log::getInstance("log_lingue");

if ($params["azione"] == "attiva-lingua")
{
	$log->writeString("INIZIO ATTIVAZIONE LINGUA ".$params["lingua"]);

	LingueModel::g()->attivaLingua($params["lingua"]);

	$log->writeString("FINE ATTIVAZIONE LINGUA ".$params["lingua"]);
}

if ($params["azione"] == "rigenera-traduzioni-lingua")
{
	$log->writeString("INIZIO RIGENERAZIONE TRADUZIONI LINGUA");

	ContenutitradottiModel::rigeneraTraduzioni();

	$log->writeString("FINE RIGENERAZIONE TRADUZIONI LINGUA");
}
