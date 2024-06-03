<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<tr>
	<td><?php echo gtext("Totale");?>:</td>
	<td><b>&euro; <?php echo setPriceReverse($ordine["total"]);?></b></td>
</tr>
