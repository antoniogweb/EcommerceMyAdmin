<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($pr["richiesta"]) { ?>
	<span class="uk-text-small uk-text-warning">
		<?php echo gtext("In data")." <b>".smartDate($pr["data_richiesta"], v("default_date_format")." H:i");?></b>
		<?php echo gtext("hai eseguito un richiesta di resto per l'ordine")." <b>".$ordine["id_o"]; ?></b>
		<?php if ($pr["id_spedizione_negozio"]) { echo " - ". gtext("merce consegnata il")." ".smartDate($pr["data_inizio"], v("default_date_format"));} ?>
	</span>
<?php } else { ?>
	<?php if (OrdiniperiodiresoModel::g(false)->inPeriodoReso($pr["id_o_periodo_reso"])) { ?>
		<a target="_blank" class="uk-button uk-button-secondary uk-button-small" href="<?php echo OrdiniperiodiresoModel::g(false)->getUrlRichiediReso($pr["id_o_periodo_reso"])?>"><?php echo gtext("Richiedi il reso", false); ?> <span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/arrow-right.svg");?></span></a><br />
		
		<span class="uk-text-small"><?php echo gtext("Il reso può essere richiesto nel seguente periodo:");?> <?php echo smartDate($pr["data_inizio"], v("default_date_format"));?> - <b><?php echo smartDate($pr["data_fine"], v("default_date_format"));?></b><br /></span>
	<?php } else { ?>
		<span class="uk-text-small uk-text-warning"><?php echo gtext("Il reso può essere richiesto nel seguente periodo:");?> <?php echo smartDate($pr["data_inizio"], v("default_date_format"));?> - <b><?php echo smartDate($pr["data_fine"], v("default_date_format"));?></b><br /></span>
	<?php } ?>
<?php } ?>