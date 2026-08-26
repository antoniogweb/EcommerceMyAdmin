<?php if (!defined('EG')) die('Direct access not allowed!');

$breadcrumb = array(
	gtextPlain("Home") 		=> $this->baseUrl,
	gtextPlain("Wishlist")	=>	"",
);

$titoloPagina = gtextPlain("La tua lista dei desideri");
$descrizioneNoProdotti = gtext("La tua lista dei desideri è vuota");
$noOrdinamento = true;

include(tpf("/Contenuti/prodotti.php"));
?>
<span class="in-pagina-wishlist"></span>
