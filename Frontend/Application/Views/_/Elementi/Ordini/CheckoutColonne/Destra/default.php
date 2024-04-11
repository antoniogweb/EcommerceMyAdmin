<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div <?php if (!User::$isMobile) { ?>uk-sticky="offset: 10;bottom: true;"<?php } ?> id="fragment-checkout-carrello">
	<?php include(tpf("/Ordini/checkout_totali.php")); ?>
	
	<?php if (v("attiva_coupon_checkout") && !hasActiveCoupon()) { ?>
	<div class="box_coupon uk-margin-medium-top">
		<?php
		include(tpf(ElementitemaModel::p("CHECKOUT_COUPON","", array(
			"titolo"	=>	"Form coupon al checkout",
			"percorso"	=>	"Elementi/Ordini/Coupon",
		))));
		?>
	</div>
	<?php } ?>
</div>

<?php if (User::$isMobile) { ?>
<div class="uk-container uk-margin-large-bottom">
	<div class="uk-flex uk-flex-top">
		<div class="uk-width-expand">
			<hr class="uk-divider-icon uk-margin-medium-top uk-margin-medium-bottom">
			<h2 id="fragment-checkout-conferma" class="uk-margin-remove-top <?php echo v("classi_titoli_checkout");?>">
				<span uk-icon="icon:check;ratio:1" class="uk-margin-right uk-hidden@m <?php echo v("classi_icona_checkout")?>"></span><?php echo gtext("Conferma acquisto");?>
			</h2>
			
			<?php include(tpf(ElementitemaModel::p("CHECKOUT_BOTTOM","", array(
					"titolo"	=>	"Parte inferiore del checkout",
					"percorso"	=>	"Elementi/Ordini/CheckoutBottom",
				))));
			?>
		</div>
	</div>
</div>
<?php } ?>
