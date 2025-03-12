<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<thead>
	<tr class="">
		<th class="text-left"><?php echo gtext("Immagine");?></th>
		<th colspan="2" align="left" class=""><?php echo gtext("Prodotto");?></th>
		<?php if ($ordine["da_spedire"] && v("attiva_gestione_spedizioni")) { ?>
		<th class="text-left"><?php echo gtext("Spedizione");?></th>
		<?php } ?>
		<th class="text-right"><?php echo gtext("Codice");?></th>
		<th class="text-right"><?php echo gtext("Peso");?></th>
		<th class="text-right"><?php echo gtext("Quantità");?></th>
		<th class="text-right colonne_non_ivate"><?php echo gtext("Prezzo");?><br /><?php echo gtext("IVA $labelIvaInclusaEsclusa");?></th>
		<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
		<th class="text-right colonne_non_ivate"><?php echo gtext("Sconto");?><br />(<i><?php echo $ordine["nome_promozione"];?></i>)</th>
		<th class="text-right colonne_non_ivate"><?php echo gtext("Prezzo scontato");?><br /><?php echo gtext("IVA $labelIvaInclusaEsclusa");?></th>
		<?php } ?>
		<th class="text-right colonne_non_ivate"><?php echo gtext("Aliquota");?></th>
		<?php if (false) { ?>
			<?php if (v("prezzi_ivati_in_carrello")) { ?>
				<th class="text-right"><?php echo gtext("Prezzo");?><br /><?php echo gtext("IVA inclusa");?></th>
				<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
				<th class="text-right"><?php echo gtext("Sconto");?><br />(<i><?php echo $ordine["nome_promozione"];?></i>)</th>
				<th class="text-right"><?php echo gtext("Prezzo scontato");?><br /><?php echo gtext("IVA inclusa");?></th>
				<?php } ?>
			<?php } ?>
			<th class="text-right"><?php echo gtext("Totale IVA");?> <?php echo $labelIvaInclusaEsclusa; ?></th>
		<?php } ?>
		<th class="text-right"><?php echo gtext("Totale IVA $labelIvaInclusaEsclusa");?></th>
	</tr>
</thead>
