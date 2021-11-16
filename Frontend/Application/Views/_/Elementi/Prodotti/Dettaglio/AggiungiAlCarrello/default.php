<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-margin-small uk-width-1-1 uk-width-2-3@m">
	<div class="uk-width-1-1 uk-button uk-button-default spinner uk-hidden" uk-spinner="ratio: .70"></div>
	<a name="add-to-cart" id-cart="<?php echo isset($_GET["id_cart"]) ? (int)$_GET["id_cart"] : 0;?>" rel="<?php echo $p["pages"]["id_page"];?>" class="uk-width-1-1 uk-button uk-button-default aggiungi_al_carrello pulsante_carrello single_add_to_cart_button" href="#">
		<span>
			<?php if (idCarrelloEsistente()) { ?>
			<?php echo gtext("Aggiorna carrello", false); ?>
			<?php } else { ?>
			<?php echo gtext("Aggiungi al carrello", false); ?>
			<?php } ?>
		</span>
	</a>
</div>
