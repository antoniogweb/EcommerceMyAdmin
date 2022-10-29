<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_coupon_checkout") && !hasActiveCoupon()) { ?>
<div class="box_coupon">
	<div class="uk-margin">
		<div class="uk-text-small">
			<?php echo gtext("Possiedi il codice di una promozione attiva?");?> <a href="#" class="showcoupon"><?php echo gtext("Aggiungi il tuo codice all'ordine");?></a>	
		</div>
	</div>

	<div id="coupon" class="uk-child-width-1-3@m uk-text-center" uk-grid style="display:none">
		<div></div>
		<div>
			<?php
			include(tpf(ElementitemaModel::p("CHECKOUT_COUPON","", array(
				"titolo"	=>	"Form coupon al checkout",
				"percorso"	=>	"Elementi/Ordini/Coupon",
			))));
			?>
		</div>
		<div></div>
	</div>
</div>
<?php } ?>
