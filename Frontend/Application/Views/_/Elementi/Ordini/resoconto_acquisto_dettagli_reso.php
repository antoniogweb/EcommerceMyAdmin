<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($pr["richiesta"]) { ?>
	<span class="uk-text-small uk-text-warning">
		<?php echo gtext("In data")." <b>".smartDate($pr["data_richiesta"], v("default_date_format")." H:i");?></b>
		<?php echo gtext("hai eseguito una richiesta di resto per l'ordine")." <b>".$pr["id_o"]; ?></b>
		<?php if ($pr["id_spedizione_negozio"]) { echo " - ". gtext("merce consegnata il")." ".smartDate($pr["data_inizio"], v("default_date_format"));} ?>
	</span>
<?php } else { ?>
	<a target="_blank" class="uk-button uk-button-secondary uk-button-small" href="<?php echo OrdiniperiodiresoModel::g(false)->getUrlRichiediReso($pr["id_o_periodo_reso"])?>"><?php echo gtext("Richiedi il reso", false); ?> <span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/arrow-right.svg");?></span></a><br />
<?php } ?>