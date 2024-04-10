<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!v("carrello_monoprodotto")) { ?>
	<?php
	$idRigaCarrello = $p["cart"]["id_cart"];
	$quantitaRigaCarrello = $p["cart"]["quantity"];
	include(tpf(ElementitemaModel::p("INPUT_QUANTITA_CARRELLO","", array(
		"titolo"	=>	"Campo input di modifica della quantità",
		"percorso"	=>	"Elementi/Generali/QuantitaCarrello",
	))));
	?>
<?php } else { ?>
	<?php if (User::$isMobile) { echo gtext("Qta").":"; } ?> <?php echo $p["cart"]["quantity"];?>
<?php } ?>
