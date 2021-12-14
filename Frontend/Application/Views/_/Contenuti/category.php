<?php if (!defined('EG')) die('Direct access not allowed!');

$descrizioneNoProdotti = gtext("Non è presente alcun articolo");
$itemFile = "/Elementi/Categorie/news.php";
$noFiltri = $noNumeroProdotti = true;

include(tpf("/Contenuti/prodotti.php"));

