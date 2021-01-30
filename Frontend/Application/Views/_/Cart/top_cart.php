<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Carrello") => "",
);

$titoloPagina = gtext("Il tuo Carrello");
$noFiltri = true;
$noNumeroProdotti = true;

include(tpf("/Elementi/Pagine/page_top.php"));
