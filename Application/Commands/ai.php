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

define('APP_CONSOLE', true);
define('EG','allowed');

$options = getopt(null, array(
	"azione::",
	"lingua::",
	"id_record::",
	"query::",
	"numero_risultati::",
	"ambito",
));

$default = array(
	"lingua"	=>	null,
	"id_record"	=>	0,
	"query"		=>	"",
	"numero_risultati"	=>	10,
	"ambito"	=>	"Ecommerce",
);

$params = array_merge($default, $options);

include_once(dirname(__FILE__)."/../../config.php");

define('DOMAIN_NAME',$website_domain_name);

require_once(dirname(__FILE__) . "/../../index.php");

ImpostazioniModel::init();
VariabiliModel::ottieniVariabili();
Domain::$parentRoot = ROOT."/..";
Domain::$publicUrl = rtrim(Url::getFileRoot(), "/");
TraduzioniModel::$contestoStatic = "front";
TraduzioniModel::getInstance()->ottieniTraduzioni();

Files_Log::$logFolder = LIBRARY."/Logs";

if (!isset($params["azione"]))
{
	echo "si prega di selezionare un'azione con l'istruzione --azione=\"<azione>\" \n";
	die();
}

$log = Files_Log::getInstance("log_ai");

if ($params["azione"] == "crea-embeddings-pagina")
{
	$log->writeString("INIZIO CREAZIONE EMBEDDINGS PAGINA");
	
	EmbeddingsModel::g(false)->getPageEmbeddings($params["id_record"], $params["lingua"], $log);
	
	$log->writeString("FINE CREAZIONE EMBEDDINGS PAGINA");
}

if ($params["azione"] == "crea-embeddings-categoria")
{
	$log->writeString("INIZIO CREAZIONE EMBEDDINGS CATEGORIA");
	
	EmbeddingsModel::g(false)->getCategoryEmbeddings($params["id_record"], $params["lingua"], $log);
	
	$log->writeString("FINE CREAZIONE EMBEDDINGS CATEGORIA");
}

if ($params["azione"] == "ricerca-semantica")
{
	$log->writeString("INIZIO RICERCA SEMANTICA");
	
	$risultati = EmbeddingsModel::ricercaSemantica($params["query"], null, $params["lingua"], $params["numero_risultati"], $log);
	
	print_r($risultati);
	
	$log->writeString("FINE RICERCA SEMANTICA");
}

if ($params["azione"] == "routing")
{
	$log->writeString("INIZIO ROUTING");
	
	$risultati = AirichiesteModel::g(false)->routing($params["query"], $params["ambito"]);
	
	print_r($risultati);
	
	$log->writeString("FINE ROUTING");
}

if ($params["azione"] == "rag")
{
	$log->writeString("INIZIO RAG");
	
	list($intent, $risposta, $istruzioni) = AirichiesteModel::g(false)->rag($params["query"], $params["ambito"], $params["lingua"], $params["numero_risultati"]);
	
	echo $risposta;
	
	$log->writeString("FINE RAG");
}