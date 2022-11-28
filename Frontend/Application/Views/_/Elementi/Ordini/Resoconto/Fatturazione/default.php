<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-overflow-auto">
	<table class="table uk-table uk-table-divider uk-table-hover">
		<?php if (strcmp($ordine["tipo_cliente"],"privato") === 0 || strcmp($ordine["tipo_cliente"],"libero_professionista") === 0) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Nome", false); ?></td>
			<td><?php echo $ordine["nome"];?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Cognome", false); ?></td>
			<td><?php echo $ordine["cognome"];?></td>
		</tr>
		<?php } ?>
		<?php if (strcmp($ordine["tipo_cliente"],"azienda") === 0) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Ragione sociale", false); ?></td>
			<td><?php echo $ordine["ragione_sociale"];?></td>
		</tr>
		<?php } ?>
		<?php if ($ordine["p_iva"]) { ?>
			<?php if (strcmp($ordine["tipo_cliente"],"azienda") === 0 || strcmp($ordine["tipo_cliente"],"libero_professionista") === 0) { ?>
			<tr>
				<td class="first_column"><?php echo gtext("P. IVA", false); ?></td>
				<td><?php echo $ordine["p_iva"];?></td>
			</tr>
			<?php } ?>
		<?php } ?>
		<?php if ($ordine["codice_fiscale"]) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Codice fiscale", false); ?></td>
			<td><?php echo $ordine["codice_fiscale"];?></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="first_column"><?php echo gtext("Indirizzo", false); ?></td>
			<td><?php echo $ordine["indirizzo"];?></td>
		</tr>
		<?php if ($ordine["cap"]) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Cap", false); ?></td>
			<td><?php echo $ordine["cap"];?></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="first_column"><?php echo gtext("Nazione", false); ?></td>
			<td><?php echo nomeNazione($ordine["nazione"]);?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Provincia", false); ?></td>
			<td><?php echo $ordine["provincia"];?></td>
		</tr>
		<tr>
			<td class="first_column"><?php echo gtext("Città", false); ?></td>
			<td><?php echo $ordine["citta"];?></td>
		</tr>
		<?php if (trim($ordine["telefono"])) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Telefono", false); ?></td>
			<td><?php echo $ordine["telefono"];?></td>
		</tr>
		<?php } ?>
		<tr>
			<td class="first_column"><?php echo gtext("Email", false); ?></td>
			<td><?php echo $ordine["email"];?></td>
		</tr>
		<?php if ($ordine["pec"]) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Pec", false); ?></td>
			<td><?php echo $ordine["pec"];?></td>
		</tr>
		<?php } ?>
		<?php if ($ordine["codice_destinatario"]) { ?>
		<tr>
			<td class="first_column"><?php echo gtext("Codice destinatario", false); ?></td>
			<td><?php echo $ordine["codice_destinatario"];?></td>
		</tr>
		<?php } ?>
	</table>
</div>
