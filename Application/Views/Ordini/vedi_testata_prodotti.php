<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<thead>
	<tr class="">
		<th class="text-left"><?php echo gtextPlain("Immagine");?></th>
		<th colspan="2" align="left" class=""><?php echo gtextPlain("Prodotto");?></th>
		<?php if ($ordine["da_spedire"] && v("attiva_gestione_spedizioni")) { ?>
		<th class="text-left"><?php echo gtextPlain("Spedizione");?></th>
		<?php } ?>
		<th class="text-right"><?php echo gtextPlain("Codice");?></th>
		<th class="text-right"><?php echo gtextPlain("Peso");?></th>
		<th class="text-right"><?php echo gtextPlain("Quantità");?></th>
		<?php if (v("attiva_modulo_acquisti")) { ?>
		<th class="text-right"><?php echo gtextPlain("Da ordinare");?></th>
		<?php } ?>
		<th class="text-right colonne_non_ivate"><?php echo gtextPlain("Prezzo");?><br /><?php echo gtextPlain("IVA $labelIvaInclusaEsclusa");?></th>
		<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
		<th class="text-right colonne_non_ivate"><?php echo gtextPlain("Sconto");?><br />(<i><?php echo $ordine["nome_promozione"];?></i>)</th>
		<th class="text-right colonne_non_ivate"><?php echo gtextPlain("Prezzo scontato");?><br /><?php echo gtextPlain("IVA $labelIvaInclusaEsclusa");?></th>
		<?php } ?>
		<th class="text-right colonne_non_ivate"><?php echo gtextPlain("Aliquota");?></th>
		<?php if (false) { ?>
			<?php if (v("prezzi_ivati_in_carrello")) { ?>
				<th class="text-right"><?php echo gtextPlain("Prezzo");?><br /><?php echo gtextPlain("IVA inclusa");?></th>
				<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
				<th class="text-right"><?php echo gtextPlain("Sconto");?><br />(<i><?php echo $ordine["nome_promozione"];?></i>)</th>
				<th class="text-right"><?php echo gtextPlain("Prezzo scontato");?><br /><?php echo gtextPlain("IVA inclusa");?></th>
				<?php } ?>
			<?php } ?>
			<th class="text-right"><?php echo gtextPlain("Totale IVA");?> <?php echo $labelIvaInclusaEsclusa; ?></th>
		<?php } ?>
		<th class="text-right"><?php echo gtextPlain("Totale IVA $labelIvaInclusaEsclusa");?></th>
	</tr>
</thead>
