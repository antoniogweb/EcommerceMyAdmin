<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-visible@s">
	<div class="uk-text-meta uk-grid-small uk-child-width-1-2 uk-flex-middle uk-grid uk-text-uppercase" uk-grid="">
		<div class="uk-first-column uk-text-left">
			<div class="uk-grid-small uk-child-width-1-2 uk-flex-middle uk-grid uk-text-uppercase" uk-grid="">
				<div><?php echo gtext("Immagine");?></div>
				<div><?php echo gtext("Prodotto");?></div>
			</div>
		</div>
		<div class="uk-width-expand">
			<div class="uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-center uk-grid" uk-grid="">
				<?php if (v("mostra_codice_in_carrello")) { ?>
				<div>
					<?php echo gtext("Codice");?>
				</div>
				<?php } ?>
				<?php if (v("attiva_prezzo_fisso")) { ?>
				<div>
					<?php echo gtext("Prezzo fisso");?> <?php if (!v("prezzi_ivati_in_carrello")) { ?><?php echo gtext("IVA esclusa")?><?php } ?>
				</div>
				<?php } ?>
				<div>
					<?php echo gtext("Prezzo");?> <?php if (!v("prezzi_ivati_in_carrello")) { ?><?php echo gtext("IVA esclusa")?><?php } ?>
				</div>
				<div>
					<?php echo gtext("Quantità");?>
				</div>
				<div class="uk-text-right">
					<?php echo gtext("Totale");?>
				</div>
			</div>
		</div>
	</div>
</div>
<hr>
<?php
$indice = 1;
foreach ($righeOrdine as $p) { ?>
<div class="uk-overflow-auto lista-riga uk-grid-small uk-child-width-1-2 uk-child-width-1-2@s <?php if (!User::$isMobile) { ?>uk-flex-middle<?php } ?> uk-grid" uk-grid>
	<div class="uk-first-column">
		<div class="uk-grid-small uk-child-width-1-1 uk-child-width-1-2@s uk-flex-middle uk-grid uk-text-uppercase" uk-grid="">
			<div>
				<?php if ($p["righe"]["immagine"]) { ?>
				<img width="200px" src="<?php echo $this->baseUrl."/thumb/carrello/".$p["righe"]["immagine"];?>" />
				<?php } ?>
			</div>
			<div class="uk-visible@s">
				<?php echo $p["righe"]["title"];?>
				<?php if (strcmp($p["righe"]["id_c"],0) !== 0) { echo "<span class='uk-text-small'><br />".$p["righe"]["attributi"]."</span>"; } ?>
				<?php include(tpf("Elementi/Ordini/main_testo_disponibilita.php"));?>
			</div>
		</div>
		
		<?php include(tpf("Ordini/resoconto_prodotto_gift_card.php"));?>
	</div>
	<div class="uk-width-expand">
		<div class="uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-center@s uk-text-left uk-grid" uk-grid="">
			<div class="uk-hidden@s">
				<b><?php echo $p["righe"]["title"];?></b>
				<?php if (strcmp($p["righe"]["id_c"],0) !== 0) { echo "<span class='uk-text-small'><br />".$p["righe"]["attributi"]."</span>"; } ?>
				<?php include(tpf("Elementi/Ordini/main_testo_disponibilita.php"));?>
			</div>
			<?php if (v("mostra_codice_in_carrello")) { ?>
			<div class="uk-text-small">
				<span class="uk-hidden@s uk-text-bold"><?php echo gtext("Codice");?>:</span> <?php echo $p["righe"]["codice"];?>
			</div>
			<?php } ?>
			<?php if (v("attiva_prezzo_fisso")) { ?>
			<div class="uk-text-small uk-margin-remove-top">
				<span class="uk-hidden@s uk-text-bold"><?php echo gtext("Prezzo fisso");?>:</span>
				<?php if (isset($p["righe"]["in_promozione"]) and strcmp($p["righe"]["in_promozione"],"Y")===0 && $p["righe"]["prezzo_fisso_intero"] > 0){ echo "<del>€ ".setPriceReverse(p($p["righe"],$p["righe"]["prezzo_fisso_intero"]))."</del>"; } ?> &euro; <span class="item_price_single"><?php echo setPriceReverse(p($p["righe"],$p["righe"]["prezzo_fisso"]));?></span>
				<?php if (!v("prezzi_ivati_in_carrello")) { ?>
				<div class="scritta_iva_carrello"><?php echo gtext("Iva", false); ?>: <?php echo setPriceReverse($p["righe"]["iva"]);?> %</div>
				<?php } ?>
			</div>
			<?php } ?>
			<div class="uk-text-small uk-margin-remove-top">
				<span class="uk-hidden@s uk-text-bold"><?php echo gtext("Prezzo");?>:</span>
				<?php if (isset($p["righe"]["in_promozione"]) and strcmp($p["righe"]["in_promozione"],"Y")===0){ echo "<del>€ ".setPriceReverse(p($p["righe"],$p["righe"]["prezzo_intero"]))."</del>"; } ?> &euro; <span class="item_price_single"><?php echo setPriceReverse(p($p["righe"],$p["righe"]["price"]));?></span>
				<?php if (!v("prezzi_ivati_in_carrello")) { ?>
				<div class="scritta_iva_carrello"><?php echo gtext("Iva", false); ?>: <?php echo setPriceReverse($p["righe"]["iva"]);?> %</div>
				<?php } ?>
			</div>
			<div class="uk-text-small uk-margin-remove-top">
				<span class="uk-hidden@s uk-text-bold"><?php echo gtext("Quantità");?>:</span> <?php echo $p["righe"]["quantity"];?>
			</div>
			<div class="uk-text-small uk-text-right@s uk-text-left uk-margin-remove-top">
				<span class="uk-hidden@s uk-text-bold"><?php echo gtext("Totale");?>:</span> &euro; <span class="item_price_subtotal"><?php echo setPriceReverse(p($p["righe"],$p["righe"]["quantity"] * $p["righe"]["price"]));?></span>
			</div>
		</div>
	</div>
</div>
<hr <?php if ($indice == count($righeOrdine)) { ?>class="uk-margin-remove-bottom"<?php } ?>>
<?php $indice++; } ?>
