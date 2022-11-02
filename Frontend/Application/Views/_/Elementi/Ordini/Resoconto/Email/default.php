<?php if (!defined('EG')) die('Direct access not allowed!');
ElementitemaModel::$percorsi["RESOCONTO_PRODOTTI"]["nome_file"] = "default";
include(tpf(ElementitemaModel::p("RESOCONTO_ACQUISTI","", array(
	"titolo"	=>	"Pagina con il resoconto dell'acquisto",
	"percorso"	=>	"Elementi/Ordini/Resoconto/Acquisto",
))));
