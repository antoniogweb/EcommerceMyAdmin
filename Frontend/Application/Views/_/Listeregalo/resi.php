<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php $tabellaPeriodiReso = ListeregaloModel::g(false)->gTabellaPeriodiReso($lista["id_lista_regalo"]); ?>
<?php foreach ($tabellaPeriodiReso as $pr) { ?>
	<?php if ($pr["richiesta"] || OrdiniperiodiresoModel::g(false)->inPeriodoReso($pr["id_o_periodo_reso"])) { ?>
		<hr />
		<span class="uk-text-small"><?php echo gtext("Prodotti consegnati il");?>: <b><?php echo smartDate($pr["data_inizio"], v("default_date_format"));?></b></span>
		<?php if ($pr["richiesta"]) { ?>
			<br />
			<span class="uk-text-small uk-text-warning">
				<?php echo gtext("In data")." <b>".smartDate($pr["data_richiesta"], v("default_date_format")." H:i");?></b>
				<?php echo gtext("hai eseguito una richiesta di resto per i prodotti della lista consegnati il")." <b>".smartDate($pr["data_inizio"], v("default_date_format")); ?></b>
			</span>
		<?php } else { ?>
			<a target="_blank" class="uk-margin-left uk-button uk-button-primary uk-button-small" href="<?php echo OrdiniperiodiresoModel::g(false)->getUrlRichiediResoLista($pr["id_o_periodo_reso"])?>"><?php echo gtext("Richiedi il reso", false); ?> <span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/arrow-right.svg");?></span></a><br />
		<?php } ?>
	<?php } ?>
<?php } ?>