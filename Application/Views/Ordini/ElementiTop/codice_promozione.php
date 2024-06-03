<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($ordine["codice_promozione"]) { ?>
<tr>
	<td><?php echo gtext("Coupon");?>:</td>
	<td>
		<b><?php echo $ordine["codice_promozione"];?></b> (<i><?php echo $ordine["nome_promozione"];?></i>)
	</td>
</tr>
<?php } ?>
