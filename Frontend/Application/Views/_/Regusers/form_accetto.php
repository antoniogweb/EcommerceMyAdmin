<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="condizioni_privacy uk-margin uk-text-muted uk-text-small">
	<?php echo gtextPlain("Ho letto e accettato le");?>
	<?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
	<?php if ($idPrivacy) { ?>
	<a class="uk-text-secondary" href="<?php echo getUrlPagina($idPrivacy);?>"><?php echo gtextPlain("condizioni di privacy");?></a>
	<?php } ?>
</div>

<div class="class_accetto" data-field-name="<?php echo gtextAttr("condizioni di privacy");?>">
	<label for="accetto_non_accetto"><?php echo Html_Form::radio("accetto",$values['accetto'],"non_accetto","radio_2","none","accetto_non_accetto",null,"aria-required='true'");?><span style='margin-left:8px;'></span><span class='radio_2_testo'><?php echo gtext("NON ACCETTO");?></span><span style='margin-right:20px;'></span></label>
	<label for="accetto_accetto"><?php echo Html_Form::radio("accetto",$values['accetto'],"accetto","radio_2","none","accetto_accetto",null,"aria-required='true'");?><span style='margin-left:8px;'></span><span class='radio_2_testo'><?php echo gtext("ACCETTO");?></span></label>
</div>

<?php if (v("attiva_accetto_2")) { ?>
<div class="condizioni_privacy uk-margin uk-text-muted uk-text-small">
	<?php echo gtextPlain("Ho letto e accettato le");?>
	<?php $idPrivacy = PagineModel::gTipoPagina("CONDIZIONI_AGG"); ?>
	<?php if ($idPrivacy) { ?>
	<a class="uk-text-secondary" href="<?php echo getUrlPagina($idPrivacy);?>"><?php echo gtextPlain("condizioni aggiuntive");?></a>
	<?php } ?>
</div>

<div class="class_accetto_2" data-field-name="<?php echo gtextAttr("condizioni aggiuntive");?>">
	<label for="accetto_2_non_accetto"><?php echo Html_Form::radio("accetto_2",$values['accetto_2'],"non_accetto","radio_2","none","accetto_2_non_accetto",null,"aria-required='true'");?><span style='margin-left:8px;'></span><span class='radio_2_testo'><?php echo gtext("NON ACCETTO");?></span><span style='margin-right:20px;'></span></label>
	<label for="accetto_2_accetto"><?php echo Html_Form::radio("accetto_2",$values['accetto_2'],"accetto","radio_2","none","accetto_2_accetto",null,"aria-required='true'");?><span style='margin-left:8px;'></span><span class='radio_2_testo'><?php echo gtext("ACCETTO");?></span></label>
</div>
<?php } ?>
