<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtextPlain("Home") 		=> $this->baseUrl,
	gtextPlain("Carrello") => $this->baseUrl."/carrello/vedi",
	gtextPlain("Checkout") => "",
);

$titoloPagina = gtextPlain("Checkout");
$noFiltri = true;
$noNumeroProdotti = true;

include(tpf("/Elementi/Pagine/page_top.php"));

include(tpf(ElementitemaModel::p("CHECKOUT","", array(
	"titolo"	=>	"Pagina checkout",
	"percorso"	=>	"Elementi/Ordini/Checkout",
))));

include(tpf("/Elementi/Pagine/page_bottom.php"));
