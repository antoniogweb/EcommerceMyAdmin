<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!hasActiveCoupon()) { ?>
<form action="<?php echo $this->baseUrl."/carrello/vedi";?>" method="POST">
	<div class="uk-grid-small uk-child-width-expand@s uk-grid" uk-grid="">
		<div>
			<input type="text" name="il_coupon" class="uk-input uk-form-width-medium@m input-text" id="coupon_code" value="" placeholder="<?php echo gtext("Codice promozione", false);?>" />
		</div>
		<div>
			<button type="submit" class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-1@s" name="invia_coupon" value="<?php echo gtext("Invia codice promozione", false);?>"><?php echo gtext("Invia codice");?></button>
		</div>
	</div>
</form>
<?php } ?>
