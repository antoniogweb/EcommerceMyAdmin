<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) { ?>
<?php include(tpf(ElementitemaModel::p("PRODOTTO_TOP")));?>

<?php include(tpf("/Elementi/Pagine/dettagli_pagina.php"));?>
<?php include(tpf("/Fasce/fascia_spedizioni.php"));?>
<?php include(tpf("/Elementi/Pagine/prodotti_correlati.php"));?>
<?php include(tpf("/Elementi/Pagine/page_bottom.php"));?>
<?php } ?>
