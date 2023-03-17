<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-grid" uk-grid>
	<div class="uk-width-1-2@m"></div>
	<div class="uk-width-1-2@m">
		<table class="uk-table uk-table-divider uk-table-hover uk-margin-remove-top ">
			<?php
			$scrittaFinaleTotale = "Totale ordine";
			$strIvato = v("prezzi_ivati_in_carrello") ? "_ivato" : "";
			?>
			<?php if ($ordine["da_spedire"] || $ordine["usata_promozione"] == "Y") { ?>
			<tr>
				<td class="first_column"><?php echo gtext("Totale merce", false); ?>:</td> <td class="uk-text-right"><strong>&euro; <?php echo setPriceReverse($ordine["subtotal".$strIvato]);?></strong></td>
			</tr>
			<?php } ?>
			<?php if (strcmp($ordine["usata_promozione"],"Y") === 0 && $ordine["tipo_promozione"] == "PERCENTUALE") { ?>
			<tr>
				<td class="first_column"><?php echo gtext("Prezzo scontato", false); ?> (<i><?php echo $ordine["nome_promozione"];?></i>):</td> <td class="uk-text-right"> <strong>€ <?php echo setPriceReverse($ordine["prezzo_scontato".$strIvato]);?></strong></td>
			</tr>
			<?php } ?>
			<?php if ($ordine["costo_pagamento"] > 0) { ?>
			<tr>
				<td class="first_column"><?php echo gtext("Spese pagamento", false); ?>:</td> <td class="uk-text-right"> <strong>&euro; <?php echo setPriceReverse($ordine["costo_pagamento".$strIvato]);?></strong></td>
			</tr>
			<?php } ?>
			<?php if ($ordine["da_spedire"]) { ?>
			<tr>
				<td class="first_column"><?php echo gtext("Spese spedizione", false); ?>:</td> <td class="uk-text-right"> <strong>&euro; <?php echo setPriceReverse($ordine["spedizione".$strIvato]);?></strong></td>
			</tr>
			<?php } ?>
			<?php if (!v("prezzi_ivati_in_carrello")) { ?>
			<tr>
				<td class="first_column"><?php echo gtext("Iva", false); ?>:</td> <td class="uk-text-right"> <strong>&euro; <?php echo setPriceReverse($ordine["iva"]);?></strong></td>
			</tr>
			<?php } ?>
			<?php if ((strcmp($ordine["usata_promozione"],"Y") === 0 || $ordine["sconto"] > 0) && $ordine["tipo_promozione"] == "ASSOLUTO") {
				$scrittaFinaleTotale = "Totale da pagare";
			?>
			<tr>
				<td class="first_column"><?php echo gtext("Totale ordine", false); ?>:</td> <td class="uk-text-right"> <strong>&euro; <?php echo setPriceReverse($ordine["total_pieno"]);?></strong></td>
			</tr>
			<tr>
				<td class="first_column">
					<?php if ($ordine["nome_promozione"]) { ?>
					<?php echo gtext("Sconto coupon", false); ?> (<i><?php echo $ordine["nome_promozione"];?></i>):
					<?php } ?>
					<?php if ($ordine["sconto"] > 0) { ?>
					<?php echo gtext("Sconto", false); ?>:
					<?php } ?>
				</td>
				<td class="uk-text-right">
					<strong>&euro; <?php echo setPriceReverse($ordine["total"] - $ordine["total_pieno"]);?></strong>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td class="first_column"><?php echo gtext($scrittaFinaleTotale, false); ?>:</td> <td class="uk-text-right"> <strong>&euro; <?php echo setPriceReverse($ordine["total"]);?></strong></td>
			</tr>
			<?php if (v("prezzi_ivati_in_carrello") && $ordine["id_iva_estera"] && !$ordine["nascondi_iva_estera"]) { ?>
			<tr>
				<td class="first_column"><span style="color:#999;font-style:italic;"><?php echo gtext("Di cui IVA", false); ?> (<?php echo $ordine["stringa_iva_estera"];?>):</td> <td class="uk-text-right"> <strong>&euro; <?php echo setPriceReverse($ordine["iva"]);?></strong></span></td>
			</tr>
			<?php } ?>
		</table>
	</div>
</div>
