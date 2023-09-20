<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-center uk-grid" uk-grid="">
	<div class="uk-first-column">
		<?php echo gtext("Prodotto");?>
	</div>
	<?php if (v("mostra_codice_in_carrello")) { ?>
	<div>
		<?php echo gtext("Codice");?>
	</div>
	<?php } ?>
	<?php if (v("attiva_prezzo_fisso")) { ?>
	<div>
		<?php echo gtext("Prezzo fisso");?> <?php if (!v("prezzi_ivati_in_carrello")) { ?><?php echo gtext("(Iva esclusa)");?><?php } ?>
	</div>
	<?php } ?>
	<div>
		<?php echo gtext("Prezzo");?> <?php if (!v("prezzi_ivati_in_carrello")) { ?><?php echo gtext("(Iva esclusa)");?><?php } ?>
	</div>
	<div>
		<?php echo gtext("Quantità");?>
	</div>
	<div>
		<?php echo gtext("Totale");?> <?php if (!v("prezzi_ivati_in_carrello")) { ?><?php echo gtext("(Iva esclusa)");?><?php } ?>
	</div>
	<div class=""></div>
</div>
