<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-overflow-auto">
	<table class="table uk-table uk-table-divider uk-table-hover">
		<tr>
			<td class="first_column"><?php echo gtextPlain("Indirizzo", false); ?></td>
			<td><?php echo $ordine["indirizzo_spedizione"];?></td>
		</tr>
		<?php if ($ordine["cap_spedizione"]) { ?>
		<tr>
			<td class="first_column"><?php echo gtextPlain("Cap", false); ?></td>
			<td><?php echo $ordine["cap_spedizione"];?></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="first_column"><?php echo gtextPlain("Nazione", false); ?></td>
			<td><?php echo nomeNazione($ordine["nazione_spedizione"]);?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtextPlain("Provincia", false); ?></td>
			<td><?php echo NazioniModel::conProvince($ordine["nazione_spedizione"]) ? ProvinceModel::sFindTitoloDaCodice($ordine["provincia_spedizione"]) : $ordine["dprovincia_spedizione"];?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtextPlain("Città", false); ?></td>
			<td><?php echo $ordine["citta_spedizione"];?></td>
		</tr>
		<?php if (trim($ordine["telefono_spedizione"])) { ?>
		<tr>
			<td class="first_column"><?php echo gtextPlain("Telefono", false); ?></td>
			<td><?php echo $ordine["telefono_spedizione"];?></td>
		</tr>
		<?php } ?>
		<?php if (trim($ordine["destinatario_spedizione"])) { ?>
		<tr>
			<td class="first_column"><?php echo gtextPlain("Destinatario", false); ?></td>
			<td><?php echo $ordine["destinatario_spedizione"];?></td>
		</tr>
		<?php } ?>
		<?php if (v("mostra_modalita_spedizione_in_resoconto")) { ?>
		<tr>
			<td class="first_column"><?php echo gtextPlain("Modalità di spedizione", false); ?></td>
			<td><?php echo gtextPlain(CorrieriModel::g()->where(array("id_corriere"=>(int)$ordine["id_corriere"]))->field("titolo"));?></td>
		</tr>
		<?php } ?>
	</table>
</div>
