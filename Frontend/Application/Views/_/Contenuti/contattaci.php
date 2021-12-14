<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php

$standardPage = false;
$noNumeroProdotti = $noContainer = $noContainer = true;

foreach ($pages as $p) {
	$datiCategoria = $p;
	$urlAlias = getUrlAlias($p["pages"]["id_page"]);
	$urlAliasCategoria = getCategoryUrlAlias($p["categories"]["id_c"]);

	$titoloPagina = field($p, "title");
	$sottotitoloPagina = field($p, "sottotitolo");
	
	if (!isset($noTopPagina))
		include(tpf("/Elementi/Pagine/page_top.php"));
	
	include(tpf(ElementitemaModel::p("CONTATTI_TOP","", array(
		"titolo"	=>	"Parte superiore contatti",
		"percorso"	=>	"Elementi/Sezioni/Pagine/Contatti/Top",
	))));

	echo $fasce;

	include(tpf("/Elementi/Pagine/page_bottom.php"));
}
