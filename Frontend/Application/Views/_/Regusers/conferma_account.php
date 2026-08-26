<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtextPlain("Home") 		=> $this->baseUrl,
	gtextPlain("Verifica account")	=>	"",
);

$titoloPagina = gtextPlain("Verifica account");

include(tpf("/Elementi/Pagine/page_top.php"));

if (!v("permetti_registrazione"))
	$noLoginRegistrati = true;

include(tpf(ElementitemaModel::p("LOGIN_CONFERMA_ACCOUNT","", array(
	"titolo"	=>	"Form con codice verifica dell'account",
	"percorso"	=>	"Elementi/Generali/LoginConfermaAccount",
))));

include(tpf("/Elementi/Pagine/page_bottom.php"));
