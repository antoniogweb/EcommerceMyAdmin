<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($ordine["acconto"] > 0) { ?>
<tr>
	<td><?php echo gtext("Acconto");?>:</td>
	<td><b>&euro; <?php echo setPriceReverse($ordine["acconto"]);?></b></td>
</tr>
<tr>
	<td><?php echo gtext("Saldo");?>:</td>
	<td><b>&euro; <?php echo setPriceReverse($ordine["saldo"]);?></b></td>
</tr>
<?php } ?>
