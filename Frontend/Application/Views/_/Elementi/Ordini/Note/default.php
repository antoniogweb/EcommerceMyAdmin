<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "note")) { ?>
<div class="uk-margin">
	<h2 class="<?php echo v("classi_titoli_checkout");?>"><?php echo gtextPlain("Note d'acquisto")?></h2>
	
	<div class="blocco_checkout">
		<label class="accessibility-visually-hidden" for="checkout_nota_acquisto"><?php echo gtextPlain("Nota d'acquisto")?></label>
		<?php echo Html_Form::textarea("note",$values["note"],"uk-textarea","checkout_nota_acquisto","placeholder='".gtextAttr("Scrivi qui una eventuale nota al tuo ordine..")."'");?>
	</div>
</div>
<?php } ?> 
