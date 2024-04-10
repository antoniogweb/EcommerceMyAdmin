<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
	$prezzoUnitario = p($p["cart"],$p["cart"]["price"]);
	$prezzoUnitarioFisso = p($p["cart"],$p["cart"]["prezzo_fisso"]);
	$backColor = checkGiacenza($p["cart"]["id_cart"], $p["cart"]["quantity"]) ? v("input_ok_back_color") : "red";
	$urlAliasProdotto = getUrlAlias($p["cart"]["id_page"], $p["cart"]["id_c"]);
?>
<div>
	<div class="cart_item_row uk-grid-small uk-child-width-1-1@m uk-child-width-1-2 uk-child-width-1-5@m uk-child-width-2-4 <?php if (!User::$isMobile) { ?>uk-flex-middle<?php } ?> uk-grid" uk-grid="" rel="<?php echo $p["cart"]["id_cart"];?>">
		<div class="uk-first-column">
			<?php include(tpf("Cart/main_campi_left.php"));?>
		</div>
		<div class="uk-width-expand">
			<?php include(tpf("Cart/main_campi_right.php"));?>
		</div>
	</div>
</div>
<?php include(tpf("Cart/main_elementi_riga.php"));?>
<hr>
