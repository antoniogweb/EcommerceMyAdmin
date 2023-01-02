<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-margin uk-width-1-1">
	<div class="uk-grid uk-grid-collapse" uk-grid>
		<div class="uk-width-1-1 uk-width-1-2@m">
			<span class="uk-text-emphasis"><?php echo gtext("Indirizzo");?>:</span> <?php echo $ordine["indirizzo_spedizione"];?>
			<br /><?php echo $ordine["cap_spedizione"];?>, <?php echo $ordine["citta_spedizione"];?> (<?php echo $ordine["nazione_spedizione"] == "IT" ? $ordine["provincia_spedizione"] : $ordine["dprovincia_spedizione"];?>)
			<br /><span class="uk-text-emphasis"><?php echo gtext("Nazione");?>:</span> <?php echo nomeNazione($ordine["nazione_spedizione"]);?>
		</div>
		<div class="uk-width-1-1 uk-width-1-2@m">
			<span class="uk-text-emphasis"><?php echo gtext("Tel");?>:</span> <?php echo $ordine["telefono_spedizione"];?><br />
			<?php if (trim($ordine["destinatario_spedizione"])) { ?>
				<span class="uk-text-emphasis"><?php echo gtext("Destinatario");?>:</span> <?php echo $ordine["destinatario_spedizione"];?><br />
			<?php } ?>
			<?php if (v("mostra_modalita_spedizione_in_resoconto")) { ?>
			<span class="uk-text-emphasis"><?php echo gtext("Modalità di spedizione", false); ?>: </span> <?php echo CorrieriModel::g()->where(array("id_corriere"=>(int)$ordine["id_corriere"]))->field("titolo");?>
			<?php } ?>
		</div>
	</div>
</div>
