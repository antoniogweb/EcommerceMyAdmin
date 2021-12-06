<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
$standardPage = false;
$itemFile = "/Elementi/Categorie/evento.php";

include(tpf("/Elementi/Pagine/page_top.php"));

include(tpf(ElementitemaModel::p("EVENTI_TOP","", array(
	"titolo"	=>	"Parte superiore eventi",
	"percorso"	=>	"Elementi/Sezioni/Eventi/Elenco/Top",
))));

echo $fasce;

include(tpf("/Elementi/Pagine/page_bottom.php"));
