<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-overflow-auto">
	<table width="100%" class="" cellspacing="0">
		<thead>
			<tr class="cart_head">
				<th style="text-align:left" class="nome_prodotto row_left"><?php echo gtext("Immagine", false); ?></th>
				<th style="text-align:left" class="nome_prodotto row_left"><?php echo gtext("Prodotto", false); ?></th>
			</tr>
		</thead>
		
		<?php foreach ($righeOrdine as $p) { ?>
		<tr class="cart_item_row">
			<td style="text-align:left">
				<?php if ($p["righe"]["immagine"]) { ?>
				<img width="200px" src="<?php echo $this->baseUrl."/thumb/carrello/".$p["righe"]["immagine"];?>" />
				<?php } ?>
			</td>
			<td style="text-align:left">
				<?php echo $p["righe"]["title"];?>
				<?php if (strcmp($p["righe"]["id_c"],0) !== 0) { echo "<br />".$p["righe"]["attributi"]; } ?>
				
				<?php include(tpf("Ordini/resoconto_prodotto_gift_card.php"));?>
				
				<?php if (v("mostra_codice_in_carrello")) { ?>
				<br /><b><?php echo gtext("Codice");?>:</b> <?php echo $p["righe"]["codice"];?>
				<?php } ?>
				<br />
				<b><?php echo gtext("Prezzo");?>:</b> <?php if (isset($p["righe"]["in_promozione"]) and strcmp($p["righe"]["in_promozione"],"Y")===0){ echo "<del>€ ".setPriceReverse(p($p["righe"],$p["righe"]["prezzo_intero"]))."</del>"; } ?> &euro; <span class="item_price_single"><?php echo setPriceReverse(p($p["righe"],$p["righe"]["price"]));?></span>
				<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
				<div class="scritta_iva_carrello"><?php echo gtext("Iva", false); ?>: <?php echo setPriceReverse($p["righe"]["iva"]);?> %</div>
				<?php } ?>
				
				<br />
				<b><?php echo gtext("Quantita");?>:</b> <?php echo $p["righe"]["quantity"];?>
				<b><?php echo gtext("Totale");?>:</b> &euro; <span class="item_price_subtotal"><?php echo setPriceReverse(p($p["righe"],$p["righe"]["quantity"] * $p["righe"]["price"]));?></span>
			</td>
		</tr>
		<?php } ?>
	</table>
</div>
