<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-background-muted uk-card uk-card-default uk-card-small" style="box-shadow: none;">
	<div class="uk-card-body">
		<form class="checkout_coupon" method="post" action="<?php echo $this->baseUrl."/checkout";?>">
			<p class="uk-text-small uk-text-emphasis"><?php echo gtext("Se hai un codice promozione, inseriscilo sotto.");?></p>
			
			<div class="uk-margin">
				<div class="uk-form-controls">
					<input class="uk-input uk-width-1-2@s uk-width-1-1@m" autocomplete="new-password" name="il_coupon" type="text" placeholder="<?php echo gtext("Codice promozione", false)?>" />
				</div>
			</div>
			
			<div>
				<div class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-2@s uk-width-1-1@m spinner uk-hidden" uk-spinner="ratio: .70"></div>
				<button class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-2@s uk-width-1-1@m" name="invia_coupon"><?php echo gtext("Invia codice");?></button>
			</div>
		</form>
	</div>
</div>
