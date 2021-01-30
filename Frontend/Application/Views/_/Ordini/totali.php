<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Totale merce");?></div>
	<div><?php echo getSubTotal(v("prezzi_ivati_in_carrello"));?> €</div>
</div>
<?php if (hasActiveCoupon()) { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Prezzo scontato");?> (<i><?php echo getNomePromozione();?></i>)</div>
	<div><?php echo getPrezzoScontato(v("prezzi_ivati_in_carrello"));?> €</div>
</div>
<?php } ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Spese spedizione");?></div>
	<div><?php echo getSpedizione(v("prezzi_ivati_in_carrello"));?> €</div>
</div>
<?php if (!v("prezzi_ivati_in_carrello")) { ?>
<div class="uk-grid-small uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Iva");?></div>
	<div><?php echo getIva();?> €</div>
</div>
<?php } ?>

<hr>
<div class="uk-grid-small uk-flex-middle uk-grid" uk-grid="">
	<div class="uk-width-expand uk-text-muted uk-first-column"><?php echo gtext("Totale ordine");?></div>
	<div class="uk-text-lead uk-text-bolder"><?php echo getTotal();?> €</div>
</div>
