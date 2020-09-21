<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

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
	<th><?php echo gtext("Iva");?></th>
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
