<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-center uk-grid" uk-grid="">
	<div class="uk-first-column">
		<?php echo gtextPlain("Prodotto");?>
	</div>
	<?php if (v("mostra_codice_in_carrello")) { ?>
	<div>
		<?php echo gtextPlain("Codice");?>
	</div>
	<?php } ?>
	<?php if (v("attiva_prezzo_fisso")) { ?>
	<div>
		<?php echo gtextPlain("Prezzo fisso");?> <?php if (!v("prezzi_ivati_in_carrello")) { ?><?php echo gtextPlain("(Iva esclusa)");?><?php } ?>
	</div>
	<?php } ?>
	<div>
		<?php echo gtextPlain("Prezzo");?> <?php if (!v("prezzi_ivati_in_carrello")) { ?><?php echo gtextPlain("(Iva esclusa)");?><?php } ?>
	</div>
	<div>
		<?php echo gtextPlain("Quantità");?>
	</div>
	<div>
		<?php echo gtextPlain("Totale");?> <?php if (!v("prezzi_ivati_in_carrello")) { ?><?php echo gtextPlain("(Iva esclusa)");?><?php } ?>
	</div>
	<div class=""></div>
</div>
