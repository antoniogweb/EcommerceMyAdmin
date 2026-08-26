<?php if (!defined('EG')) die('Direct access not allowed!');

$breadcrumb = array(
	gtextPlain("Home") 		=> $this->baseUrl,
	gtextPlain("Cerca")	=>	"",
);

$titoloPagina = gtextPlain("Risultati della ricerca")." $s";

include(tpf("/Contenuti/prodotti.php"));
