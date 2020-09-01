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
	<th><?php echo gtext("Spese spedizione");?></th>
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
