<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (ContenutiModel::$tipoElementoCorrente == "pagine") {
	$p = PagesModel::getPageDetails((int)ContenutiModel::$idElementoCorrente);
	$altreImmagini = ImmaginiModel::altreImmaginiPagina((int)ContenutiModel::$idElementoCorrente);
?>
<?php include(tpf("/Elementi/Pagine/slide_prodotto.php"));?>
<?php } ?>
