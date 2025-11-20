<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="condizioni_privacy uk-margin uk-text-muted uk-text-small">
	<?php echo gtext("Ho letto e accettato le");?>
	<?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
	<?php if ($idPrivacy) { ?>
	<a class="uk-text-secondary" href="<?php echo getUrlPagina($idPrivacy);?>"><?php echo gtext("condizioni di privacy");?></a>
	<?php } ?>
</div>

<div class="class_accetto">
	<?php echo Html_Form::radio("accetto",$values['accetto'],array("<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("NON ACCETTO")."</span><span style='margin-right:20px;'></span>" => "non_accetto", "<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("ACCETTO")."</span>" => "accetto"),"radio_2");?>
</div>

<?php if (v("attiva_accetto_2")) { ?>
<div class="condizioni_privacy uk-margin uk-text-muted uk-text-small">
	<?php echo gtext("Ho letto e accettato le");?>
	<?php $idPrivacy = PagineModel::gTipoPagina("CONDIZIONI_AGG"); ?>
	<?php if ($idPrivacy) { ?>
	<a class="uk-text-secondary" href="<?php echo getUrlPagina($idPrivacy);?>"><?php echo gtext("condizioni aggiuntive");?></a>
	<?php } ?>
</div>

<div class="class_accetto_2">
	<?php echo Html_Form::radio("accetto_2",$values['accetto_2'],array("<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("NON ACCETTO")."</span><span style='margin-right:20px;'></span>" => "non_accetto", "<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("ACCETTO")."</span>" => "accetto"),"radio_2");?>
</div>
<?php } ?>