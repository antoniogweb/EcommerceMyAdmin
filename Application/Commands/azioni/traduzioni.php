<?php
if (!defined('EG')) die('Direct access not allowed!');

$default = array(
	"lingua"	=>	"",
	"id_record"	=>	0,
	"limit"		=>	10,
	"da_lingua"	=>	"",
);

$params = array_merge($default, $options);

ImpostazioniModel::init();
VariabiliModel::ottieniVariabili();
Cache_Db::$cachedTables = array();

Files_Log::$logFolder = LIBRARY."/Logs";

if (!isset($params["azione"]))
{
	echo "si prega di selezionare un'azione con l'istruzione --azione=\"<azione>\" \n";
	echo "azioni permesse:\n";
	echo "traduci-tabella-traduzioni -> traduce la tabella traduzioni (testi generici)\n";
	echo "traduci-categorie -> traduce la tabella categories (CATEGORIE)\n";
	echo "traduci-pagine -> traduce la tabella pages (PAGINE e PRODOTTI)\n";
	echo "traduci-attributi -> traduce la tabella attributi\n";
	echo "traduci-attributi-valori -> traduce la tabella attributi_valori\n";
	echo "traduci-testi -> traduce la tabella testi (TESTI EDITABILI DA FRONTEND)\n";
	echo "traduci-pagamenti -> traduce la tabella pagamenti (PAGAMENTI AL CHECKOUT)\n";
	echo "traduci-stati-ordine -> traduce la tabella stati_ordine (STATI DEGLI ORDINI)\n";
	echo "traduci-caratteristiche -> traduce la tabella caratteristiche (CARATTERISTICHE PRODOTTI)\n";
	echo "traduci-caratteristiche-valori -> traduce la tabella caratteristiche_valori (VALORI DELLE CARATTERISTICHE PRODOTTI)\n";
	echo "traduci-marchi -> traduce la tabella marchi (MARCHI PRODOTTI)\n";
	echo "traduci-tag -> traduce la tabella tag (TAG / LINEE / COLLEZIONI)\n";
	echo "traduci-ruoli -> traduce la tabella ruoli (ruoli utenti o elementi team)\n";
	echo "traduci-contenuti -> traduce la tabella caontenuti (FASCE)\n";
	echo "traduci -> traduce tutti i testi del sito\n";
	echo "traduci-pagine-tutte-le-lingue -> traduce la tabella pages (PAGINE e PRODOTTI) per tutte le lingue\n";
	die();
}

if (!$params["lingua"] && $params["azione"] != "traduci" && $params["azione"] != "traduci-pagine-tutte-le-lingue")
{
	echo "si prega di selezionare il codice ISO della lingua nella quale tradurre (en, fr, de, ...) con l'istruzione --lingua=\"<lingua>\" \n";
	echo "lingue permesse:\n";
	
	$linguePermesse = LingueModel::getValoriAttivi();
	
	foreach ($linguePermesse as $codice => $lingua)
	{
		if ($codice != v("lingua_default_frontend"))
			echo "$codice: $lingua\n";
	}
	die();
}

$log = Files_Log::getInstance("log_traduzioni");

if ($params["azione"] == "traduci-tabella-traduzioni")
	TraduttoriModel::traduciTabellaTraduzioni($params["lingua"], $params["id_record"], $params["limit"], $log, $params["da_lingua"]);

if ($params["azione"] == "traduci-categorie")
	TraduttoriModel::traduciTabellaContenuti("id_c", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-pagine")
	TraduttoriModel::traduciTabellaContenuti("id_page", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-attributi")
	TraduttoriModel::traduciTabellaContenuti("id_a", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-attributi-valori")
	TraduttoriModel::traduciTabellaContenuti("id_av", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-pagamenti")
	TraduttoriModel::traduciTabellaContenuti("id_pagamento", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-stati-ordine")
	TraduttoriModel::traduciTabellaContenuti("id_stato_ordine", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-caratteristiche")
	TraduttoriModel::traduciTabellaContenuti("id_car", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-caratteristiche-valori")
	TraduttoriModel::traduciTabellaContenuti("id_cv", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-marchi")
	TraduttoriModel::traduciTabellaContenuti("id_marchio", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-tag")
	TraduttoriModel::traduciTabellaContenuti("id_tag", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-contenuti")
	TraduttoriModel::traduciTabellaContenuti("id_cont", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-ruoli")
	TraduttoriModel::traduciTabellaContenuti("id_ruolo", $params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-testi")
	TraduttoriModel::traduciTabellaTesti($params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci")
	TraduttoriModel::traduciTutto($params["lingua"], $params["id_record"], $params["limit"], $log);

if ($params["azione"] == "traduci-pagine-tutte-le-lingue")
	TraduttoriModel::traduciPagine($params["lingua"], $params["id_record"], $params["limit"], $log);
