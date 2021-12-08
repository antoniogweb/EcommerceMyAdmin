<?php if (!defined('EG')) die('Direct access not allowed!');

$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Wishlist")	=>	"",
);

$titoloPagina = gtext("La tua lista dei desideri");
$descrizioneNoProdotti = gtext("La tua lista dei desideri è vuota");
$noOrdinamento = true;

include(tpf("/Contenuti/prodotti.php"));
?>
<span class="in-pagina-wishlist"></span>
