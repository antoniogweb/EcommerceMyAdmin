<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-flex uk-flex-column uk-height-viewport uk-background-default uk-card uk-card-default uk-card-body uk-padding-small@s uk-padding">
	<div class="uk-flex-1 uk-overflow-auto uk-margin-small-bottom">
		<div class="uk-grid-small" uk-grid>

		</div>
	</div>

	<div class="uk-card uk-card-default uk-card-body uk-padding-small uk-margin-remove-top">
		<div class="uk-flex uk-flex-middle uk-grid-small" uk-grid>
			<div class="uk-width-expand">
				<input class="uk-input" type="text" placeholder="Scrivi un messaggio...">
			</div>
			<div class="uk-width-auto">
				<button class="uk-button uk-button-primary" type="button"><?php echo gtext("Invia");?></button>
			</div>
		</div>
	</div>
</div>
