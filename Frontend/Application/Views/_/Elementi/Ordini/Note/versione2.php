<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "note") && OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "con_note")) { ?>
<div class="spedizione_box uk-width-1-1 uk-margin-bottom">
	<div class="uk-flex uk-flex-top">
		<div>
			<?php echo Html_Form::checkbox("con_note_check",$values["con_note"],1,"","checkout_con_note_check");?>
		</div>
		<div class="uk-margin-left uk-text-small">
			<label for="checkout_con_note_check"><?php echo gtextPlain("Aggiungi una nota all'ordine");?></label>
		</div>
	</div>
</div>

<div class="box_note uk-margin-medium-bottom">
	<div class="uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label" for="checkout_nota_acquisto"><?php echo gtextPlain("Nota d'acquisto")?></label>
	
		<?php echo Html_Form::textarea("note",$values["note"],"uk-textarea","checkout_nota_acquisto","placeholder='".gtextAttr("Scrivi qui una eventuale nota al tuo ordine..")."'");?>
	</div>
</div>

<?php echo Html_Form::hidden("con_note",$values["con_note"]);?>
<?php } ?>
