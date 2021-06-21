<?php if (!defined('EG')) die('Direct access not allowed!');

$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Promozioni")	=>	"",
);

$descrizioneNoProdotti = gtext("Non è presente alcun articolo");
$titoloPagina = gtext("Prodotti in promozione");

include(tpf("/Contenuti/prodotti.php"));
