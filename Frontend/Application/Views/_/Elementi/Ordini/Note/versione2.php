<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "note") && OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "con_note")) { ?>
<div class="spedizione_box uk-width-1-1 uk-margin-bottom">
	<div class="uk-flex uk-flex-top">
		<div>
			<?php echo Html_Form::checkbox("con_note_check",$values["con_note"],1,"","none");?>
		</div>
		<div class="uk-margin-left uk-text-small">
			<?php echo gtext("Aggiungi una nota all'ordine");?>
		</div>
	</div>
</div>

<div class="box_note uk-margin-medium-bottom">
	<div class="uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Nota d'acquisto")?></label>
	
		<?php echo Html_Form::textarea("note",$values["note"],"uk-textarea",null,"placeholder='".gtext("Scrivi qui una eventuale nota al tuo ordine..")."'");?>
	</div>
</div>

<?php echo Html_Form::hidden("con_note",$values["con_note"]);?>
<?php } ?>
