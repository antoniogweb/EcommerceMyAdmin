<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<tr>
	<td><?php echo gtext("Metodo di pagamento");?>:</td>
	<td><b><?php echo metodoPagamento($ordine["pagamento"]);?></b></td>
</tr>
