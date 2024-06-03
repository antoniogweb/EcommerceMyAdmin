<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!$ordine["da_spedire"] && !empty($corriere)) { ?>
<tr>
	<td><?php echo gtext("Tipo di consegna");?>:</td>
	<td><b><?php echo $corriere["titolo"];?></b></td>
</tr>
<?php } ?>
