<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Verifica codice")	=>	"",
);

$titoloPagina = gtext("Verifica codice");

include(tpf("/Elementi/Pagine/page_top.php"));

if (!v("permetti_registrazione"))
	$noLoginRegistrati = true;

include(tpf(ElementitemaModel::p("LOGIN_TWO_FACTOR","", array(
	"titolo"	=>	"Form con codice verifica a due fattore",
	"percorso"	=>	"Elementi/Generali/LoginTwoFactor",
))));

include(tpf("/Elementi/Pagine/page_bottom.php"));
