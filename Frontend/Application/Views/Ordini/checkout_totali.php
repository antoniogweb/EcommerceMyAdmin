<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="col-lg-5 col-md-12 col-sm-12">
	<div class="sticker">
		<h2 id="order_review_heading"><?php echo gtext("Il tuo ordine");?></h2>
		<div id="order_review" <?php if (!User::$isMobile) { ?>class="woocommerce-checkout-review-order"<?php } ?>>
		<h2 class="h2 order_review_heading"><?php echo gtext("Il tuo ordine");?></h2>
		
		<table class="shop_table woocommerce-checkout-review-order-table">
			<thead>
				<tr>
					<th class="product-name"><?php echo gtext("Thumb");?></th>
					<th class="product-name"><?php echo gtext("Prodotto");?></th>
					<th class="product-total"><?php echo gtext("Subtotale");?></th>
				</tr>
			</thead>


				<?php foreach ($pages as $p) { ?>
				<tbody>
					<tr class="cart_item">
						<td>
							<img src="<?php echo $this->baseUrl."/thumb/carrello/".$p["cart"]["immagine"];?>" />
						</td>
						<td class="product-name">
						<strong><?php echo field($p, "title");?></strong> <strong class="product-quantity">&times; <?php echo $p["cart"]["quantity"];?></strong>	
						<?php if (strcmp($p["cart"]["id_c"],0) !== 0) { echo "<br />".$p["cart"]["attributi"]; } ?>
						<br />Codice: <?php echo $p["cart"]["codice"];?><br />
						<?php if (strcmp($p["cart"]["in_promozione"],"Y")===0){ echo "<del>€ ".setPriceReverse($p["cart"]["prezzo_intero"])."</del>"; } ?> € <span class="item_price_single"><?php echo setPriceReverse($p["cart"]["price"]);?></span>
						<?php if (ImpostazioniModel::$valori["mostra_scritta_iva_inclusa"] == "Y") { ?>
						<div class="scritta_iva_carrello">Iva: <?php echo setPriceReverse($p["cart"]["iva"]);?> %</div>
						<?php } ?>
						</td>
						<td class="product-total">
						<span class="woocommerce-Price-amount amount"><?php echo setPriceReverse($p["cart"]["quantity"] * $p["cart"]["price"]);?><span class="woocommerce-Price-currencySymbol">&euro;</span></span>					
						</td>
					</tr>
				</tbody>
				<?php } ?>
			<tfoot class="blocco_totale_merce">

				<?php include($this->viewPath("totale_merce"));?>
				
			</tfoot>
		</table>
		
		<input class="button button_submit_cell" type="submit" name="invia" value="<?php echo gtext("Completa acquisto", false);?>" /></p>
		</div>
	</div>
</div>
