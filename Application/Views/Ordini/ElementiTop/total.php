<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<tr>
	<td><?php echo gtextPlain("Totale");?>:</td>
	<td><b>&euro; <?php echo setPriceReverse($ordine["total"]);?></b></td>
</tr>
