<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-margin uk-width-1-1">
	<div class="uk-grid uk-grid-collapse" uk-grid>
		<div class="uk-width-1-1 uk-width-1-2@m">
			<span class="uk-text-emphasis"><?php echo OrdiniModel::getNominativo($ordine);?></span>
			<?php if ($ordine["indirizzo"]) { ?><br /><span class="uk-text-emphasis"><?php echo gtext("Indirizzo");?>:</span> <?php echo $ordine["indirizzo"];?>
			<br /><?php echo $ordine["cap"];?>, <?php echo $ordine["citta"];?> (<?php echo NazioniModel::conProvince($ordine["nazione"]) ? ProvinceModel::sFindTitoloDaCodice($ordine["provincia"]) : $ordine["dprovincia"];?>)<?php } ?>
			<br /><span class="uk-text-emphasis"><?php echo gtext("Nazione");?>:</span> <?php echo nomeNazione($ordine["nazione"]);?>
			<?php if ($ordine["p_iva"]) { ?>
			<br /><span class="uk-text-emphasis"><?php echo gtext("P. IVA");?>: <?php echo $ordine["p_iva"];?>
			<?php } ?>
			<?php if ($ordine["codice_fiscale"]) { ?>
			<br /><span class="uk-text-emphasis"><?php echo gtext("Codice fiscale");?>: <?php echo strtoupper($ordine["codice_fiscale"]);?>
			<?php } ?>
		</div>
		<div class="uk-width-1-1 uk-width-1-2@m">
			<span class="uk-text-emphasis"><?php echo gtext("Tel");?>:</span> <?php echo $ordine["telefono"];?><br />
			<span class="uk-text-emphasis"><?php echo gtext("Email");?>:</span> <?php echo $ordine["email"];?><br />
			<?php if ($ordine["pec"]) { ?>
			<span class="uk-text-emphasis"><?php echo gtext("Pec");?>: <?php echo $ordine["pec"];?><br />
			<?php } ?>
			<?php if ($ordine["codice_destinatario"]) { ?>
			<span class="uk-text-emphasis"><?php echo gtext("Codice destinatario");?>: <?php echo $ordine["codice_destinatario"];?>
			<?php } ?>
		</div>
	</div>
</div>
