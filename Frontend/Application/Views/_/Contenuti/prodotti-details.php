<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) { ?>
<?php include(tpf(ElementitemaModel::p("PRODOTTO_TOP")));?>
<?php include(tpf(ElementitemaModel::p("PRODOTTO_BOTTOM")));?>
<?php include(tpf("/Elementi/Pagine/page_bottom.php"));?>
<?php } ?>
