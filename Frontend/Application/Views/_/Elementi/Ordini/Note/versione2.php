<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "note")) { ?>
<div class="uk-margin-medium-bottom">
	<div class="uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Note d'acquisto")?></label>
	
		<?php echo Html_Form::textarea("note",$values["note"],"uk-textarea",null,"placeholder='".gtext("Scrivi qui una eventuale nota al tuo ordine..")."'");?>
	</div>
</div>
<?php } ?> 
