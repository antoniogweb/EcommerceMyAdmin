<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-overflow-auto">
	<table width="100%" class="uk-table uk-table-divider uk-table-hover" cellspacing="0">
		<thead>
			<tr class="cart_head">
				<th colspan="2" align="left" class="nome_prodotto row_left"><?php echo gtext("Prodotto", false); ?></th>
				<?php if (v("mostra_codice_in_carrello")) { ?>
				<th align="left" class="nome_prodotto"><?php echo gtext("Codice", false); ?></th>
				<?php } ?>
				<th align="left" class="prezzo_prodotto"><?php echo gtext("Prezzo", false); ?> <?php if (!v("prezzi_ivati_in_carrello")) { ?> <?php echo gtext("(Iva esclusa)", false); ?><?php } ?></th>
				<th align="left" class="quantita_prodotto"><?php echo gtext("Quantità", false); ?></th>
				<th style="text-align:right;" class="subtotal_prodotto"><?php echo gtext("Totale", false); ?><?php if (!v("prezzi_ivati_in_carrello")) { ?> <?php echo gtext("(Iva esclusa)", false); ?><?php } ?></th>
			</tr>
		</thead>
		
		<?php foreach ($righeOrdine as $p) { ?>
		<tr class="cart_item_row">
			<?php if ($p["righe"]["id_p"]) { ?>
			<td width="4%" style="vertical-align:top;">-</td>
			<?php } ?>
			<td colspan="<?php if (!$p["righe"]["id_p"]) { ?>2<?php } else { ?>1<?php } ?>" class="cart_item_product row_left"><?php echo $p["righe"]["title"];?>
			<?php if (strcmp($p["righe"]["id_c"],0) !== 0) { echo "<br />".$p["righe"]["attributi"]; } ?>
			
			<?php if ($p["righe"]["gift_card"]) {
				$promozioni = PromozioniModel::getPromoRigaOrdine($p["righe"]["id_r"]);
				
				if (count($promozioni) > 0) { ?>
					<table width="100%" class="uk-table uk-table-divider uk-table-hover uk-table-small uk-table-justify" cellspacing="0">
						<tr>
							<th style="text-align:left;"><?php echo gtext("Codice Gift Card");?></th>
							<th style="text-align:left;"><?php echo gtext("Stato");?></th>
						</tr>
				<?php } ?>
				<?php
				
				foreach ($promozioni as $promo) { 
			?>
				<tr>
					<td>
						<span class="uk-text uk-text-primary"><?php echo $promo["codice"];?></span>
						<?php $inviataA = EventiretargetingelementiModel::getElemento($promo["id_p"], "promozioni"); ?>
						<?php if (!empty($inviataA)) { ?>
						<br /><span class="uk-text-meta"><?php echo gtext("Inviato a");?>:</span> <span class="uk-text-small"><?php echo $inviataA["email"];?></span>
						<?php } ?>
					</td>
					<td>
						<?php echo PromozioniModel::g()->isActiveCoupon($promo["codice"],null,false) ? "<span class='uk-label uk-label-success'>".gtext("Attivo")."</span>" : "<span class='uk-label uk-label-warning'>".gtext("Non attivo")."</span>";?>
						<?php if (PromozioniModel::gNumeroEuroRimasti($promo["id_p"]) <= 0) { ?>
						<br /><span class="uk-text-meta"><?php echo gtext("Credito esaurito");?></span>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
				
				<?php if (count($promozioni) > 0) { ?></table><?php } ?>
			<?php } ?>
			
			</td>
			<?php if (v("mostra_codice_in_carrello")) { ?>
			<td style="vertical-align:top;" class="cart_item_product"><?php echo $p["righe"]["codice"];?></td>
			<?php } ?>
			<td style="vertical-align:top;" class="cart_item_price">
				<?php if (isset($p["righe"]["in_promozione"]) and strcmp($p["righe"]["in_promozione"],"Y")===0){ echo "<del>€ ".setPriceReverse(p($p["righe"],$p["righe"]["prezzo_intero"]))."</del>"; } ?> &euro; <span class="item_price_single"><?php echo setPriceReverse(p($p["righe"],$p["righe"]["price"]));?></span>
				<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
				<div class="scritta_iva_carrello"><?php echo gtext("Iva", false); ?>: <?php echo setPriceReverse($p["righe"]["iva"]);?> %</div>
				<?php } ?>
			</td>
			<td style="vertical-align:top;" class="cart_item_quantity"><?php echo $p["righe"]["quantity"];?></td>
			<td style="vertical-align:top;text-align:right;" class="cart_item_subtotal">&euro; <span class="item_price_subtotal"><?php echo setPriceReverse(p($p["righe"],$p["righe"]["quantity"] * $p["righe"]["price"]));?></span></td>
		</tr>
		<?php } ?>
	</table>
</div>
