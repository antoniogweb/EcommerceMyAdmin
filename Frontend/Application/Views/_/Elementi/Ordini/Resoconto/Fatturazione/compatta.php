<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-margin uk-width-1-1">
	<div class="uk-grid uk-grid-collapse" uk-grid>
		<div class="uk-width-1-1 uk-width-1-2@m">
			<span class="uk-text-emphasis"><?php echo OrdiniModel::getNominativo($ordine);?></span>
			<?php if ($ordine["indirizzo"]) { ?><br /><span class="uk-text-emphasis"><?php echo gtextPlain("Indirizzo");?>:</span> <?php echo $ordine["indirizzo"];?>
			<br /><?php echo $ordine["cap"];?>, <?php echo $ordine["citta"];?> (<?php echo NazioniModel::conProvince($ordine["nazione"]) ? ProvinceModel::sFindTitoloDaCodice($ordine["provincia"]) : $ordine["dprovincia"];?>)<?php } ?>
			<br /><span class="uk-text-emphasis"><?php echo gtextPlain("Nazione");?>:</span> <?php echo nomeNazione($ordine["nazione"]);?>
			<?php if ($ordine["p_iva"]) { ?>
			<br /><span class="uk-text-emphasis"><?php echo gtextPlain("P. IVA");?>: <?php echo $ordine["p_iva"];?>
			<?php } ?>
			<?php if ($ordine["codice_fiscale"]) { ?>
			<br /><span class="uk-text-emphasis"><?php echo gtextPlain("Codice fiscale");?>: <?php echo strtoupper($ordine["codice_fiscale"]);?>
			<?php } ?>
		</div>
		<div class="uk-width-1-1 uk-width-1-2@m">
			<span class="uk-text-emphasis"><?php echo gtextPlain("Tel");?>:</span> <?php echo $ordine["telefono"];?><br />
			<span class="uk-text-emphasis"><?php echo gtextPlain("Email");?>:</span> <?php echo $ordine["email"];?><br />
			<?php if ($ordine["pec"]) { ?>
			<span class="uk-text-emphasis"><?php echo gtextPlain("Pec");?>: <?php echo $ordine["pec"];?><br />
			<?php } ?>
			<?php if ($ordine["codice_destinatario"]) { ?>
			<span class="uk-text-emphasis"><?php echo gtextPlain("Codice destinatario");?>: <?php echo $ordine["codice_destinatario"];?>
			<?php } ?>
		</div>
	</div>
</div>
