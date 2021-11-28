<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!partial()) { ?>
<?php include(tpf(ElementitemaModel::p("FOOTER")));?>

<?php include(tpf("/Elementi/menu-offcanvas.php"));?>

<div id="cart-offcanvas" uk-offcanvas="overlay: true; flip: true">
	<aside class="uk-offcanvas-bar uk-padding-remove carrello_secondario">
		<?php include(tpf("/Cart/ajax_cart.php"));?>
	</aside>
</div>

<?php } ?>
