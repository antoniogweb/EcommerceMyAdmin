<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-overflow-auto">
	<table class="table uk-table uk-table-divider uk-table-hover">
		<tr>
			<td class="first_column"><?php echo gtext("Indirizzo", false); ?></td>
			<td><?php echo $ordine["indirizzo_spedizione"];?></td>
		</tr>
		<?php if ($ordine["cap_spedizione"]) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Cap", false); ?></td>
			<td><?php echo $ordine["cap_spedizione"];?></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="first_column"><?php echo gtext("Nazione", false); ?></td>
			<td><?php echo nomeNazione($ordine["nazione_spedizione"]);?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Provincia", false); ?></td>
			<td><?php echo $ordine["provincia_spedizione"];?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Città", false); ?></td>
			<td><?php echo $ordine["citta_spedizione"];?></td>
		</tr>
		<?php if (trim($ordine["telefono_spedizione"])) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Telefono", false); ?></td>
			<td><?php echo $ordine["telefono_spedizione"];?></td>
		</tr>
		<?php } ?>
		<?php if (trim($ordine["destinatario_spedizione"])) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Destinatario", false); ?></td>
			<td><?php echo $ordine["destinatario_spedizione"];?></td>
		</tr>
		<?php } ?>
		<?php if (v("mostra_modalita_spedizione_in_resoconto")) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Modalità di spedizione", false); ?></td>
			<td><?php echo gtext(CorrieriModel::g()->where(array("id_corriere"=>(int)$ordine["id_corriere"]))->field("titolo"));?></td>
		</tr>
		<?php } ?>
	</table>
</div>
