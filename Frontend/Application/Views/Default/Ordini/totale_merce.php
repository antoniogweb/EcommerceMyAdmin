<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

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
				<div class="scritta_iva_carrello">Iva: <?php echo isset(IvaModel::$aliquotaEstera) ? setPriceReverse(IvaModel::$aliquotaEstera) : setPriceReverse($p["cart"]["iva"]);?> %</div>
				<?php } ?>
				</td>
				<td class="product-total">
				<span class="woocommerce-Price-amount amount"><?php echo setPriceReverse($p["cart"]["quantity"] * $p["cart"]["price"]);?><span class="woocommerce-Price-currencySymbol">&euro;</span></span>					
				</td>
			</tr>
		</tbody>
		<?php } ?>
	<tfoot>
		<tr class="cart-subtotal">
			<th><?php echo gtext("Totale merce");?></th>
			<td></td>
			<td><span class="woocommerce-Price-amount amount"><?php echo getSubTotal();?><span class="woocommerce-Price-currencySymbol">&euro;</span></span></td>
		</tr>
		<?php if (hasActiveCoupon()) { ?>
		<tr class="cart-subtotal">
			<th><?php echo gtext("Prezzo scontato");?> (<i><?php echo getNomePromozione();?></i>)</th>
			<td></td>
			<td><span class="woocommerce-Price-amount amount"><?php echo getPrezzoScontato();?><span class="woocommerce-Price-currencySymbol">&euro;</span></span></td>
		</tr>
		<?php } ?>
		<tr class="woocommerce-shipping-totals shipping">
			<th>
				<?php echo gtext("Spese spedizione");?>
				<?php if (isset($_POST["id_corriere"]) && isset($_POST["nazione_spedizione"]) && !spedibile($_POST["id_corriere"], $_POST["nazione_spedizione"])) { ?>
				<div style="color:red;"><?php echo gtext("Non spedibile nella nazione selezionata")?></div>
				<?php } ?>
			</th>
			<td></td>
			<td><span class="woocommerce-Price-amount amount"><?php echo getSpedizione();?><span class="woocommerce-Price-currencySymbol">&euro;</span></span></td>
		</tr>

		<tr class="cart-subtotal">
			<th>
				<?php echo gtext("Iva");?>
				<?php if (isset(IvaModel::$titoloAliquotaEstera)) { ?>
				 (<?php echo IvaModel::$titoloAliquotaEstera;?>)
				<?php } ?>
			</th>
			<td></td>
			<td><span class="woocommerce-Price-amount amount"><?php echo getIva();?><span class="woocommerce-Price-currencySymbol">&euro;</span></span></td>
		</tr>

		<tr class="order-total">
			<th><?php echo gtext("Totale ordine");?></th>
			<td></td>
			<td><strong><span class="woocommerce-Price-amount amount"><?php echo getTotal();?><span class="woocommerce-Price-currencySymbol">&euro;</span></span></strong> </td>
		</tr>

		<?php if (isset($_POST["id_corriere"]) && isset($_POST["nazione_spedizione"]) && !spedibile($_POST["id_corriere"], $_POST["nazione_spedizione"])) { ?>
		<tr>
			<td colspan="3">
				<div class="alert alert-danger"><?php echo gtext("Non spedibile nella nazione selezionata");?></div>
			</td>
		</tr>
		<?php } ?>
	</tfoot>
</table>
