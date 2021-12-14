<?php if (!defined('EG')) die('Direct access not allowed!');

$standardPage = false;
$itemFile = "/Elementi/Categorie/servizio.php";

include(tpf("/Elementi/Pagine/page_top.php"));

include(tpf(ElementitemaModel::p("SERVIZI_TOP","", array(
	"titolo"	=>	"Parte superiore servizi",
	"percorso"	=>	"Elementi/Sezioni/Servizi/Elenco/Top",
))));

echo $fasce;

include(tpf("/Elementi/Pagine/page_bottom.php"));
