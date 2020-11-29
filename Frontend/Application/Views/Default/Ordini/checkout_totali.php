<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="col-lg-5 col-md-12 col-sm-12">
	<div class="sticker">
		<h2 id="order_review_heading"><?php echo gtext("Il tuo ordine");?></h2>
		<div id="order_review" <?php if (!User::$isMobile) { ?>class="woocommerce-checkout-review-order"<?php } ?>>
		<h2 class="h2 order_review_heading"><?php echo gtext("Il tuo ordine");?></h2>
		
		<div class="blocco_totale_merce">
			<?php include($this->viewPath("totale_merce"));?>
		</div>
		
		<input class="button button_submit_cell" type="submit" name="invia" value="<?php echo gtext("Completa acquisto", false);?>" /></p>
		</div>
	</div>
</div>
