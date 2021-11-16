<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!idCarrelloEsistente()) { ?>
<div class="uk-width-1-1 uk-width-2-3@m">
	<div class="uk-width-1-1 uk-button uk-button-secondary spinner uk-hidden" uk-spinner="ratio: .70"></div>
	<button id="acquista" class="uk-width-1-1 uk-button uk-button-secondary acquista_prodotto"><?php echo gtext("Acquista ora");?></button>
</div>
<?php } ?>
