<?php if (!defined('EG')) die('Direct access not allowed!');

$breadcrumb = array(
	gtextPlain("Home") 		=> $this->baseUrl,
	gtextPlain("Promozioni")	=>	"",
);

$descrizioneNoProdotti = gtext("Non è presente alcun articolo");
$titoloPagina = gtextPlain("Prodotti in promozione");

include(tpf("/Contenuti/prodotti.php"));
