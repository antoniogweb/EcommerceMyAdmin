<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Carrello") => $this->baseUrl."/carrello/vedi",
	gtext("Checkout") => "",
);

$titoloPagina = gtext("Checkout");
$noFiltri = true;
$noNumeroProdotti = true;

include(tpf("/Elementi/Pagine/page_top.php"));

include(tpf(ElementitemaModel::p("CHECKOUT","", array(
	"titolo"	=>	"Pagina checkout",
	"percorso"	=>	"Elementi/Ordini/Checkout",
))));

include(tpf("/Elementi/Pagine/page_bottom.php"));
