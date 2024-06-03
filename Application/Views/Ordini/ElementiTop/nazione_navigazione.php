<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_ip_location")) { ?>
<tr>
	<td><?php echo gtext("Nazione navigazione");?>:</td>
	<td><b><?php echo findTitoloDaCodice($ordine["nazione_navigazione"]);?></b></td>
</tr>
<?php } ?>
