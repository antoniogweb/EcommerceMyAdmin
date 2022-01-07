<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "fattura")) { ?>
<div class="uk-margin uk-margin-remove-bottom uk-width-1-1 nascondi_fuori_italia campo_check_fattura">
	<label><?php echo Html_Form::checkbox('fattura',$values['fattura'],'1','uk-checkbox');?> <?php echo gtext("Mi serve la fattura");?></label>
</div>
<?php } ?>
