<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<form class="checkout_coupon" method="post" action="<?php echo $this->baseUrl."/checkout";?>">
	<p class="uk-text-small uk-text-muted"><?php echo gtext("Se hai un codice promozione, inseriscilo sotto.");?></p>
	
	<div class="uk-margin">
		<label class="uk-form-label uk-text-bold"><?php echo gtext("Codice promozione");?> *</label>
		<div class="uk-form-controls">
			<input class="uk-input uk-width-1-2@s uk-width-1-1@m" autocomplete="new-password" name="il_coupon" type="text" placeholder="<?php echo gtext("Codice promozione", false)?>" />
		</div>
	</div>
	
	<div>
		<div class="uk-button uk-button-secondary uk-width-1-2@s uk-width-1-1@m spinner uk-hidden" uk-spinner="ratio: .70"></div>
		<input autocomplete="new-password" class="uk-button uk-button-secondary uk-width-1-2@s uk-width-1-1@m" type="submit" name="invia_coupon" value="<?php echo gtext("Invia codice");?>" />
	</div>
</form>
