<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-visible@m">
	<?php if (!v("carrello_monoprodotto")) { ?>
		<div>
			<div class="uk-align-right <?php echo v("classe_pulsanti_carrello");?> spinner uk-hidden" uk-spinner="ratio: .70"></div>
			<a type="submit" class="btn_submit_form uk-align-right <?php echo v("classe_pulsanti_carrello");?> cart_button_aggiorna_carrello" name="update_cart" value="<?php echo gtext("Aggiorna carrello");?>"><?php echo gtext("Aggiorna carrello");?></a>
		</div>
	<?php } ?>
</div>
