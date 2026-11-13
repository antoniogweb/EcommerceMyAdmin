<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-margin-small uk-grid-small uk-text-right uk-flex uk-flex-middle" uk-grid>
	<div class="uk-width-3-4 uk-text-small">
		<label for="quantita-prodotto"><?php echo gtextPlain("Quantità");?>:</label>
	</div>
	<div class="uk-width-1-4">
		<input id="quantita-prodotto" name="quantita" class="uk-input uk-form-width-xsmall quantita_input" type="number" value="<?php echo getQtaDaCarrello();?>" min="1" style="font-size: 14px;">
	</div>
</div>
