<?php if (!defined('EG')) die('Direct access not allowed!');

$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Cerca")	=>	"",
);

$titoloPagina = gtext("Risultati della ricerca")." $s";

include(tpf("/Contenuti/prodotti.php"));
