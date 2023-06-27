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

ini_set("memory_limit","-1");

define('APP_CONSOLE', true);
define('EG','allowed');

$options = getopt(null, array(
	"azione::",
));

$default = array(
);

$params = array_merge($default, $options);

require_once(dirname(__FILE__) . "/../../index.php");

ImpostazioniModel::init();
VariabiliModel::ottieniVariabili();

Files_Log::$logFolder = LIBRARY."/Logs";

if (!isset($params["azione"]))
{
	echo "si prega di selezionare un'azione con l'istruzione --azione=\"<azione>\" \n";
	die();
}

$log = Files_Log::getInstance("log_comandi_prodotti");

if ($params["azione"] == "allinea-numero-acquisti")
{
	$log->writeString("INIZIO ALLINEAMENTO NUMERO ACQUISTI");
	
	$p = new PagesModel();
	$combModel = new CombinazioniModel();
	
	$combinazioni = $combModel->clear()->select("*")->inner(array("pagina"))->groupBy("combinazioni.id_page")->send();
	
	foreach ($combinazioni as $c)
	{
		$numero = $p->aggiornaNumeroAcquisti($c["combinazioni"]["id_page"]);
		echo "ID PAGE:".$c["pages"]["id_page"]." - ".$c["pages"]["title"]." - $numero \n";
		$log->writeString("ID PAGE:".$c["pages"]["id_page"]." - ".$c["pages"]["title"]." - $numero");
	}
	
	$log->writeString("FINE ALLINEAMENTO NUMERO ACQUISTI");
}

if ($params["azione"] == "disattiva-prodotti-senza-combinazioni-acquistabili")
{
	$log->writeString("INIZIO CONTROLLO PRODOTTI SENZA COMBINAZIONI ACQUISTABILI");
	
	$p = new PagesModel();
	
	$res = $p->query('select pages.id_page,title from pages inner join (select id_page,max(acquistabile) as acq from combinazioni group by id_page) as accessoria on pages.id_page = accessoria.id_page where accessoria.acq = 0 and pages.attivo = "Y"');
	
	foreach ($res as $r)
	{
		$p->sValues(array(
			"attivo"	=>	"N",
		));
		
		$p->pUpdate($r["pages"]["id_page"]);
		
		$log->writeString("ID PAGE:".$r["pages"]["id_page"]." - ".$r["pages"]["title"]);
		
		echo "ID PAGE:".$r["pages"]["id_page"]." - ".$r["pages"]["title"]."\n";
	}
	
	$log->writeString("FINE CONTROLLO PRODOTTI SENZA COMBINAZIONI ACQUISTABILI");
}

